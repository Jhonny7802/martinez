<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\LeadStatus;
use App\Models\Project;
use App\Models\TicketStatus;
use App\Repositories\CustomerRepository;
use App\Repositories\EstimateRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\MemberRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\ProposalRepository;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class DashboardController
 */
class DashboardController extends AppBaseController
{
    /** @var InvoiceRepository */
    private $invoiceRepository;

    /** @var ProposalRepository */
    private $proposalRepository;

    /** @var EstimateRepository */
    private $estimateRepository;

    /** @var CustomerRepository */
    private $customerRepository;

    /** @var ProjectRepository */
    private $projectRepository;

    /** @var MemberRepository */
    private $memberRepository;

    public function __construct(
        InvoiceRepository $invoiceRepository,
        ProposalRepository $proposalRepository,
        EstimateRepository $estimateRepository,
        CustomerRepository $customerRepository,
        ProjectRepository $projectRepository,
        MemberRepository $memberRepository
    ) {
        $this->invoiceRepository = $invoiceRepository;
        $this->proposalRepository = $proposalRepository;
        $this->estimateRepository = $estimateRepository;
        $this->customerRepository = $customerRepository;
        $this->projectRepository = $projectRepository;
        $this->memberRepository = $memberRepository;
    }

    /**
     * @return Factory|View
     */
    public function index()
    {
        $data['invoiceStatusCount'] = $this->invoiceRepository->getInvoicesStatusCount();
        $data['proposalStatusCount'] = $this->proposalRepository->getProposalsStatusCount();
        $data['estimateStatusCount'] = $this->estimateRepository->getEstimatesStatusCount();
        $data['projectStatusCount'] = $this->projectRepository->getProjectsStatusCount();
        $data['customerCount'] = $this->customerRepository->customerCount();
        $data['memberCount'] = $this->memberRepository->memberCount();
        $leadStatuses = LeadStatus::withCount('leads')->get();
        $ticketStatus = TicketStatus::withCount('tickets')->get();
        $projectStatus = Project::STATUS;

        $data['contractsCurrentMonths'] = Contract::with('customer')->whereMonth('end_date',
            Carbon::now()->month)->get();

        $data['currentMonth'] = Carbon::now()->month;

        $weekNames = [
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
            7 => 'Sunday',
        ];

        $currentWeekInvoicePayments = Invoice::query()
            ->where('payment_status', Invoice::STATUS_PAID)
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->select(['created_at', 'total_amount'])->get()->groupBy(function ($data) {
                return Carbon::parse($data->created_at)->dayOfWeek;
            });

        $lastWeekInvoicePayments = Invoice::query()
            ->where('payment_status', Invoice::STATUS_PAID)
            ->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])
            ->select(['created_at', 'total_amount'])->get()->groupBy(function ($data) {
                return Carbon::parse($data->created_at)->dayOfWeek;
            });

        $data['currentWeekInvoices'] = [];
        $data['lastWeekInvoices'] = [];

        foreach ($weekNames as $dayOfWeek => $dayName) {
            $currentWeekInvoicePayment = $currentWeekInvoicePayments->get($dayOfWeek);
            $data['currentWeekInvoices'][$dayName] = $currentWeekInvoicePayment ? $currentWeekInvoicePayment->sum('total_amount') : 0;
            $lastWeekInvoicePayment = $lastWeekInvoicePayments->get($dayOfWeek);
            $data['lastWeekInvoices'][$dayName] = $lastWeekInvoicePayment ? $lastWeekInvoicePayment->sum('total_amount') : 0;
        }

        $invoices = Invoice::whereYear('created_at', Carbon::now()->year)
            ->select(DB::raw('MONTH(created_at) as month,invoices.*'))->get();
        $expenses = Expense::whereYear('created_at', Carbon::now()->year)
            ->select(DB::raw('MONTH(created_at) as month,expenses.*'))->get();
        $months = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        ];

        $monthWiseRecords = [];

        foreach ($months as $month => $monthName) {
            $monthWiseRecords['income'][$monthName] = $invoices->where('month', $month)
                ->where('payment_status', Invoice::STATUS_PAID)->sum('total_amount');
            $monthWiseRecords['expenses'][$monthName] = $expenses->where('month', $month)
                ->whereNotNull('payment_mode_id')->sum('amount');
        }

        return view('dashboard.dashboard',
            compact('leadStatuses', 'projectStatus', 'ticketStatus', 'monthWiseRecords', 'months'))->with($data);
    }

    /**
     * @param  Request  $request
     * @return mixed
     */
    public function contractMonthFilter(Request $request)
    {
        $filterMonth = $request->get('month');

        $contractsCurrentMonths = Contract::with('customer')->whereMonth('end_date',
            $filterMonth)->get();

        return $this->sendResponse($contractsCurrentMonths, 'Contract Month Filter retrieved successfully.');
    }
}
