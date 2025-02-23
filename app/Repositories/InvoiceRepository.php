<?php

namespace App\Repositories;

use App\Models\Contact;
use App\Models\CreditNote;
use App\Models\Customer;
use App\Models\Estimate;
use App\Models\Invoice;
use App\Models\InvoiceAddress;
use App\Models\Item;
use App\Models\Note;
use App\Models\Notification;
use App\Models\PaymentMode;
use App\Models\SalesItem;
use App\Models\SalesTax;
use App\Models\Tag;
use App\Models\TaxRate;
use App\Models\User;
use Auth;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class InvoiceRepository
 *
 * @version April 8, 2020, 11:32 am UTC
 */
class InvoiceRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'customer_id',
        'title',
        'bill_to',
        'ship_to',
        'invoice_number',
        'invoice_date',
        'due_date',
        'sales_agent_id',
        'currency',
        'discount_type',
        'admin_text',
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Invoice::class;
    }

    /**
     * @param  null  $customerId
     * @return mixed
     */
    public function getInvoicesStatusCount($customerId = null)
    {
        if (! empty($customerId)) {
            return Invoice::selectRaw('count(case when payment_status = 0 then 1 end) as drafted')
                ->selectRaw('count(case when payment_status = 1 then 1 end) as unpaid')
                ->selectRaw('count(case when payment_status = 2 then 1 end) as paid')
                ->selectRaw('count(case when payment_status = 3 then 1 end) as partially_paid')
                ->selectRaw('count(case when payment_status = 4 then 1 end) as cancelled')
                ->selectRaw('count(case when payment_status != 0 then 1 end) as total_invoices')
                ->where('customer_id', '=', $customerId)->first();
        }

        return Invoice::selectRaw('count(case when payment_status = 0 then 1 end) as drafted')
            ->selectRaw('count(case when payment_status = 1 then 1 end) as unpaid')
            ->selectRaw('count(case when payment_status = 2 then 1 end) as paid')
            ->selectRaw('count(case when payment_status = 3 then 1 end) as partially_paid')
            ->selectRaw('count(case when payment_status = 4 then 1 end) as cancelled')
            ->selectRaw('count(*) as total_invoices')
            ->first();
    }

    /**
     * @return array
     */
    public function getDiscountTypes()
    {
        return $discountType = [
            '0' => 'No Discount',
            '1' => 'Before Tax',
            '2' => 'After Tax',
        ];
    }

    /**
     * @return mixed
     */
    public function getSyncList()
    {
        $data['customers'] = Customer::orderBy('company_name', 'asc')->pluck('company_name', 'id')->toArray();
        $data['tags'] = Tag::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $data['paymentModes'] = PaymentMode::orderBy('name', 'asc')->whereActive(true)->pluck('name', 'id')->toArray();
        $data['saleAgents'] = User::orderBy('first_name', 'asc')->whereIsEnable(true)->user()->get()->pluck('full_name',
            'id')->toArray();
        $data['discountType'] = $this->getDiscountTypes();
        $data['currencies'] = Customer::CURRENCIES;
        $taxRates = TaxRate::orderBy('tax_rate', 'asc')->get();
        $data['taxes'] = $taxRates;
        $data['taxesArr'] = $taxRates->pluck('tax_rate', 'id')->toArray();
        $data['items'] = Item::orderBy('title', 'asc')->pluck('title', 'id')->toArray();

        return $data;
    }

    /**
     * @param  array  $input
     * @return Invoice
     */
    public function saveInvoice($input)
    {
        $oldContactIds = Contact::where('customer_id', '=', $input['customer_id'])->where('primary_contact', '=',
            '1')->pluck('user_id')->toArray();
        $contactIds = Contact::where('customer_id', '=', $input['customer_id'])->where('primary_contact', '=',
            '1')->pluck('user_id')->toArray();
        $userContacts = User::whereIn('id', $contactIds)->get();
        /** @var Invoice $invoice */
        $invoice = $this->create($this->prepareInvoiceData($input));
        $users = User::whereId($invoice->sales_agent_id)->get();

        if ($invoice->payment_status == Invoice::STATUS_UNPAID) {
            if (! empty($input['sales_agent_id'])) {
                foreach ($users as $user) {
                    Notification::create([
                        'title' => 'New Invoice Created',
                        'description' => 'You are assigned to '.$invoice->title,
                        'type' => Invoice::class,
                        'user_id' => $user->id,
                    ]);
                }
            }
            if (! empty($input['customer_id'])) {
                foreach ($userContacts as $user) {
                    Notification::create([
                        'title' => 'New Invoice Created',
                        'description' => 'You are assigned to '.$invoice->title,
                        'type' => Invoice::class,
                        'user_id' => $user->id,
                    ]);

                    foreach ($oldContactIds as $oldUser) {
                        if ($oldUser == $user->id) {
                            continue;
                        }
                        Notification::create([
                            'title' => 'New User Assigned to Invoice',
                            'description' => $user->first_name.' '.$user->last_name.' assigned to '.$invoice->title,
                            'type' => Invoice::class,
                            'user_id' => $oldUser,
                        ]);
                    }
                }
            }
        }
        activity()->performedOn($invoice)->causedBy(getLoggedInUser())
            ->useLog('New Invoice created.')->log($invoice->title.' Invoice created.');

        if (isset($input['tags']) && ! empty($input['tags'])) {
            $invoice->tags()->sync($input['tags']);
        }

        $paymentModes = ! empty($input['payment_modes']) ? $input['payment_modes'] : [];
        $invoice->paymentModes()->sync($paymentModes);

        // Store Address
        $this->addInvoiceAddresses($input, $invoice);
        // Store Items
        $this->storeSalesItems($input, $invoice);
        // Store Applied Taxes with Amount
        $this->storeSalesTaxes($input, $invoice);

        return $invoice;
    }

    /**
     * @param  array  $input
     * @param  null|Invoice|CreditNote|Estimate  $owner
     * @return bool
     */
    public function storeSalesTaxes($input, $owner = null)
    {
        $owner->salesTaxes()->delete();

        if (! empty($input['taxes'])) {
            foreach ($input['taxes'] as $tax => $amount) {
                SalesTax::create([
                    'owner_id' => $owner->getId(),
                    'owner_type' => $owner->getOwnerType(),
                    'tax' => $tax,
                    'amount' => formatNumber($amount),
                ]);
            }
        }

        return true;
    }

    /**
     * @param  array  $input
     * @param  Invoice|CreditNote|Estimate  $owner
     * @return bool
     */
    public function storeSalesItems($input, $owner)
    {
        $owner->salesItems()->delete();

        foreach ($input['itemsArr'] as $record) {
            $data['owner_id'] = $owner->getId();
            $data['owner_type'] = $owner->getOwnerType();
            $data['rate'] = formatNumber($record['rate']);
            $data['total'] = formatNumber($record['total']);
            $data = array_merge($record, $data);
            $salesItem = SalesItem::create($data);

            if (! empty($record['tax'])) {
                $taxes = explode(',', $record['tax']);
                $taxes = (empty(array_filter($taxes))) ? [] : $taxes;
                $salesItem->taxes()->sync($taxes);
            }

            $data = [];
        }

        return true;
    }

    /**
     * @param  array  $input
     * @return array
     */
    public function prepareInvoiceData($input)
    {
        $invoiceFields = (new Invoice())->getFillable();
        $items = [];

        foreach ($input as $key => $value) {
            if (in_array($key, $invoiceFields)) {
                $items[$key] = $value;
            }
        }

        $items['total_amount'] = formatNumber($input['total_amount']);
        $items['discount'] = formatNumber(isset($input['final_discount']) ? $input['final_discount'] : 0);
        $items['sub_total'] = formatNumber($input['sub_total']);
        $items['payment_status'] = $input['payment_status'];

        return $items;
    }

    /**
     * @param  array  $input
     * @param  Invoice  $invoice
     * @return bool|void
     */
    public function addInvoiceAddresses($input, $invoice)
    {
        for ($i = 0; $i <= 2; $i++) {
            if (! isset($input['street'][$i])) {
                return;
            }

            InvoiceAddress::create([
                'street' => (isset($input['street'][$i])) ? $input['street'][$i] : null,
                'city' => (isset($input['city'][$i])) ? $input['city'][$i] : null,
                'state' => (isset($input['state'][$i])) ? $input['state'][$i] : null,
                'zip_code' => (isset($input['zip_code'][$i])) ? $input['zip_code'][$i] : null,
                'country' => (isset($input['country'][$i])) ? $input['country'][$i] : null,
                'type' => $i + 1,
                'invoice_id' => $invoice->id,
            ]);
        }

        return true;
    }

    /**
     * @param $invoiceId
     * @return Invoice
     */
    public function getInvoiceDetailClient($invoiceId)
    {
        $customerId = Auth::user()->contact->customer_id;

        /** @var Invoice $invoice */
        $invoice = Invoice::with([
            'customer', 'user', 'salesItems.taxes', 'invoiceAddresses', 'payments.paymentMode', 'salesTaxes',
        ])->whereCustomerId($customerId)->findOrFail($invoiceId);

        return $invoice;
    }

    /**
     * @param  int  $id
     * @return Builder[]|Collection
     */
    public function getInvoiceItems($id)
    {
        $invoice = Invoice::find($id);

        $invoiceItems = SalesItem::where('owner_id', '=', $invoice->getId())->where('owner_type', '=',
            $invoice->getOwnerType())->get();

        return $invoiceItems;
    }

    /**
     * @param $input
     * @param $id
     * @return Invoice
     */
    public function updateInvoice($input, $id)
    {
        /** @var Invoice $invoice */
        $invoice = Invoice::find($id);

        $oldUserIds = Invoice::whereId($invoice->id)->get()->pluck('sales_agent_id')->toArray();
        $oldContactIds = Contact::where('customer_id', '=', $invoice->customer_id)->where('primary_contact', '=',
            '1')->pluck('user_id')->toArray();

        $userId = implode(' ', $oldUserIds);
        $contactIds = invoice::whereId($id)->pluck('customer_id')->toArray();
        $contactId = implode(' ', $contactIds);

        $newUserIds = $input['sales_agent_id'];
        $newContactIds = $input['customer_id'];

        $users = User::whereId($newUserIds)->get();
        $contactUserIds = Contact::where('customer_id', '=', $input['customer_id'])->where('primary_contact', '=',
            '1')->pluck('user_id')->toArray();
        $userContacts = User::whereIn('id', $contactUserIds)->get();
        $invoice->update($this->prepareInvoiceData($input));

        //Contacts Notification
        if (! empty($oldContactIds) && $newContactIds !== $contactId) {
            foreach ($oldContactIds as $removedUser) {
                Notification::create([
                    'title' => 'Removed From Invoice',
                    'description' => 'You removed from '.$invoice->title,
                    'type' => Invoice::class,
                    'user_id' => $removedUser,
                ]);
            }
        }
        if ($userContacts->count() > 0) {
            foreach ($userContacts as $user) {
                Notification::create([
                    'title' => 'New Invoice Assigned',
                    'description' => 'You are assigned to '.$invoice->title,
                    'type' => Invoice::class,
                    'user_id' => $user->id,
                ]);
                foreach ($oldContactIds as $oldUser) {
                    if ($oldUser == $user->id) {
                        continue;
                    }
                    Notification::create([
                        'title' => 'New User Assigned to Invoice',
                        'description' => $user->first_name.' '.$user->last_name.' assigned to '.$invoice->title,
                        'type' => Invoice::class,
                        'user_id' => $oldUser,
                    ]);
                }
            }
        }

        //Members Notification
        if (! empty($oldUserIds) && $newUserIds !== $userId) {
            foreach ($oldUserIds as $removedUser) {
                Notification::create([
                    'title' => 'Removed From Invoice',
                    'description' => 'You removed from '.$invoice->title,
                    'type' => Invoice::class,
                    'user_id' => $removedUser,
                ]);
            }
        }
        if ($users->count() > 0) {
            foreach ($users as $user) {
                Notification::create([
                    'title' => 'New Invoice Assigned',
                    'description' => 'You are assigned to '.$invoice->title,
                    'type' => Invoice::class,
                    'user_id' => $user->id,
                ]);

                foreach ($oldUserIds as $oldUser) {
                    if ($oldUser == $user->id) {
                        continue;
                    }
                    Notification::create([
                        'title' => 'New User Assigned to Invoice',
                        'description' => $user->first_name.' '.$user->last_name.' assigned to '.$invoice->title,
                        'type' => Invoice::class,
                        'user_id' => $oldUser,
                    ]);
                }
            }
        }
        activity()->performedOn($invoice)->causedBy(getLoggedInUser())
            ->useLog('Invoice updated.')->log($invoice->title.' Invoice updated.');

        if (isset($input['tags']) && ! empty($input['tags'])) {
            $invoice->tags()->sync($input['tags']);
        }

        $paymentModes = ! empty($input['payment_modes']) ? $input['payment_modes'] : [];
        $invoice->paymentModes()->sync($paymentModes);

        $invoice->invoiceAddresses()->delete();
        $this->addInvoiceAddresses($input, $invoice);

        // Update Items
        $this->storeSalesItems($input, $invoice);
        // Update Applied Taxes with Amount
        $this->storeSalesTaxes($input, $invoice);

        return $invoice;
    }

    /**
     * @param $input
     * @return array
     */
    public function prepareSalesItemData($input)
    {
        $items = [];

        if (isset($input['item']) && ! empty($input['item'])) {
            foreach ($input as $key => $data) {
                foreach ($data as $index => $value) {
                    $items[$index][$key] = $value;
                }
            }

            return $items;
        }
    }

    /**
     * @param  Invoice  $invoice
     *
     * @throws Exception
     */
    public function deleteInvoice($invoice)
    {
        activity()->performedOn($invoice)->causedBy(getLoggedInUser())
            ->useLog('Invoice deleted.')->log($invoice->title.' Invoice deleted.');

        $invoice->tags()->detach();
        $invoice->invoiceAddresses()->delete();
        $invoice->paymentModes()->detach();
        $invoice->salesItems()->delete();
        $invoice->salesTaxes()->delete();
        $invoice->delete();
    }

    /**
     * @param  int  $id
     * @return mixed
     */
    public function getSyncListForInvoiceDetail($id)
    {
        $invoice = Invoice::with([
            'customer', 'user', 'salesItems.taxes', 'invoiceAddresses', 'payments.paymentMode', 'salesTaxes',
        ])->find($id);

        return $invoice;
    }

    /**
     * @param  int  $id
     * @param $paymentStatus
     * @return int
     */
    public function changePaymentStatus($id, $paymentStatus)
    {
        return Invoice::whereId($id)->update(['payment_status' => $paymentStatus]);
    }

    /**
     * @param $invoice
     * @return Builder[]|Collection
     */
    public function getNotesData($invoice)
    {
        return Note::with('user.media')->where('owner_id', '=', $invoice->id)
            ->where('owner_type', '=', Invoice::class)->orderByDesc('created_at')->get();
    }
}
