<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Invoice;
use App\Models\Setting;
use App\Models\Task;
use App\Repositories\InvoiceRepository;
use App\Repositories\TicketRepository;
use Barryvdh\DomPDF\Facade as PDF;
use Exception;
use Flash;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Redirect;
use Throwable;

class InvoiceController extends AppBaseController
{
    /** @var InvoiceRepository */
    private $invoiceRepository;

    public function __construct(InvoiceRepository $invoiceRepo)
    {
        $this->invoiceRepository = $invoiceRepo;
    }

    /**
     * Display a listing of the Invoice.
     *
     * @return Factory|Application|View
     */
    public function index()
    {
        $paymentStatuses = Invoice::PAYMENT_STATUS;

        return view('invoices.index', compact('paymentStatuses'));
    }

    /**
     * Show the form for creating a new Invoice.
     *
     * @param  null  $customerId
     * @return Application|Factory|View
     */
    public function create($customerId = null)
    {
        $data = $this->invoiceRepository->getSyncList();
        $settings = Setting::pluck('value', 'key');

        return view('invoices.create', compact('data', 'customerId', 'settings'));
    }

    /**
     * Store a newly created Invoice in storage.
     *
     * @param  CreateInvoiceRequest  $request
     * @return RedirectResponse|Redirector
     *
     * @throws Throwable
     */
    public function store(CreateInvoiceRequest $request)
    {
        try {
            DB::beginTransaction();
            $input = $request->all();

            if (array_sum($input['quantity']) > 9999999) {
                return $this->sendError(__('messages.common.quantity_is_not_greater_than'));
            }

            $invoice = $this->invoiceRepository->saveInvoice($input);
            DB::commit();

            Flash::success(__('messages.invoice.invoice_saved_successfully'));

            return $this->sendResponse($invoice, __('messages.invoice.invoice_saved_successfully'));
        } catch (Exception $e) {
            DB::rollBack();

            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Display the specified Invoice.
     *
     * @param  Invoice  $invoice
     * @return Application|Factory|View
     */
    public function show(Invoice $invoice)
    {
        /** @var Invoice $invoice */
        $invoice = $this->invoiceRepository->getSyncListForInvoiceDetail($invoice->id);
        $paymentModes = $invoice->paymentModes->where('active', true)->pluck('name', 'id')->toArray();

        /** @var TicketRepository $ticketRepo */
        $ticketRepo = App::make(TicketRepository::class);
        $data = $ticketRepo->getReminderData($invoice->id, Invoice::class);
        $status = Task::STATUS;
        $priorities = Task::PRIORITY;
        $notes = $this->invoiceRepository->getNotesData($invoice);
        $groupName = (request('group') == null) ? 'invoice_details' : (request('group'));

        return view("invoices.views.$groupName",
            compact('invoice', 'paymentModes', 'data', 'status', 'priorities', 'notes',
                'groupName'));
    }

    /**
     * Show the form for editing the specified Invoice.
     *
     * @param  Invoice  $invoice
     * @return Application|Factory|View|RedirectResponse
     */
    public function edit(Invoice $invoice)
    {
        if ($invoice->payment_status == Invoice::STATUS_PAID || $invoice->payment_status == Invoice::STATUS_PARTIALLY_PAID || $invoice->payment_status == Invoice::STATUS_CANCELLED) {
            return redirect()->back();
        }

        $data = $this->invoiceRepository->getSyncList();
        $invoice = $this->invoiceRepository->getSyncListForInvoiceDetail($invoice->id);
        $addresses = [];

        foreach ($invoice->invoiceAddresses as $index => $address) {
            $addresses[$index] = $address;
        }

        return view('invoices.edit', compact('data', 'invoice', 'addresses'));
    }

    /**
     * Update the specified Invoice in storage.
     *
     * @param  Invoice  $invoice
     * @param  UpdateInvoiceRequest  $request
     * @return JsonResponse
     *
     * @throws Throwable
     */
    public function update(Invoice $invoice, UpdateInvoiceRequest $request)
    {
        try {
            DB::beginTransaction();
            $input = $request->all();

            if (array_sum($input['quantity']) > 9999999) {
                return $this->sendError(__('messages.common.quantity_is_not_greater_than'));
            }

            $invoice = $this->invoiceRepository->updateInvoice($request->all(), $invoice->id);
            DB::commit();

            Flash::success(__('messages.invoice.invoice_updated_successfully'));

            return $this->sendResponse($invoice, __('messages.invoice.invoice_updated_successfully'));
        } catch (Exception $e) {
            DB::rollBack();

            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Remove the specified Invoice from storage.
     *
     * @param  Invoice  $invoice
     * @return JsonResponse|RedirectResponse
     *
     * @throws Throwable
     */
    public function destroy(Invoice $invoice)
    {
        try {
            DB::beginTransaction();
            $this->invoiceRepository->deleteInvoice($invoice);
            DB::commit();

            return $this->sendSuccess('Invoice deleted successfully.');
        } catch (Exception $e) {
            DB::rollBack();

            return Redirect::back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * @param  Invoice  $invoice
     * @return Application|Factory|View
     */
    public function viewAsCustomer(Invoice $invoice)
    {
        $invoice = $this->invoiceRepository->getSyncListForInvoiceDetail($invoice->id);
        $settings = Setting::pluck('value', 'key')->toArray();
        $totalPaid = 0;

        foreach ($invoice->payments as $payment) {
            $totalPaid += $payment->amount_received;
        }

        return view('invoices.view_as_customer', compact('invoice', 'totalPaid', 'settings'));
    }

    /**
     * @param  Invoice  $invoice
     * @return mixed
     */
    public function covertToPdf(Invoice $invoice)
    {
        $invoice = $this->invoiceRepository->getSyncListForInvoiceDetail($invoice->id);
        $totalPaid = 0;

        foreach ($invoice->payments as $payment) {
            $totalPaid += $payment->amount_received;
        }

        $settings = Setting::all()->pluck('value', 'key')->toArray();
        $pdf = PDF::loadView('invoices.invoice_pdf', compact(['invoice', 'settings', 'totalPaid']));

        return $pdf->download(__('messages.invoice.invoice_prefix').$invoice->invoice_number.'.pdf');
    }

    /**
     * @param  Invoice  $invoice
     * @param  Request  $request
     * @return mixed
     */
    public function changeStatus(Invoice $invoice, Request $request)
    {
        $this->invoiceRepository->changePaymentStatus($invoice->id, $request->get('paymentStatus'));

        return $this->sendSuccess('Payment status updated successfully.');
    }

    /**
     * @param  Invoice  $invoice
     * @return mixed
     */
    public function getNotesCount(Invoice $invoice)
    {
        return $this->sendResponse($invoice->notes()->count(), 'Notes count retrieved successfully.');
    }

    /**
     * @param  Request  $request
     * @return mixed
     */
    public function getCustomerAddress(Request $request)
    {
        $address = getAddressOfCustomer($request->customer_id);
        if (! empty($address[0])) {
            $address[0]->country = $address[0]->country != null ? $address[0]->addressCountry->name : 'null';
        }
        if (! empty($address[1])) {
            $address[1]->country = $address[1]->country != null ? $address[1]->addressCountry->name : 'null';
        }

        return $this->sendResponse($address, 'Address retrieved successfully');
    }
}
