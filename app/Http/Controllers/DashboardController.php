<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Models\Contract;
use App\Models\Customer;
use App\Models\Estimate;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\InternalMessage;
use App\Models\LeadStatus;
use App\Models\MaterialRequisition;
use App\Models\Member;
use App\Models\Product;
use App\Models\Project;
use App\Models\Proposal;
use App\Models\TicketStatus;
use App\Repositories\ContractRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\EstimateRepository;
use App\Repositories\ExpenseRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\MemberRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\ProposalRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        // Obtener los datos del repositorio y convertirlos a array
        $invoiceStatusCount = $this->invoiceRepository->getInvoicesStatusCount();
        $projectStatusCount = $this->projectRepository->getProjectsStatusCount();
        
        // Convertir a array si es un objeto
        $data['invoiceStatusCount'] = is_object($invoiceStatusCount) ? $invoiceStatusCount->toArray() : (is_string($invoiceStatusCount) ? json_decode($invoiceStatusCount, true) : $invoiceStatusCount);
        $data['projectStatusCount'] = is_object($projectStatusCount) ? $projectStatusCount->toArray() : (is_string($projectStatusCount) ? json_decode($projectStatusCount, true) : $projectStatusCount);
        
        // Asegurar que los totales estén definidos
        $data['invoiceStatusCount']['total_amount'] = $data['invoiceStatusCount']['total_amount'] ?? 0;
        $data['projectStatusCount']['total'] = $data['projectStatusCount']['total_projects'] ?? 0;
        
        // Otros datos
        $proposalStatusCount = $this->proposalRepository->getProposalsStatusCount();
        $estimateStatusCount = $this->estimateRepository->getEstimatesStatusCount();
        
        $data['proposalStatusCount'] = is_object($proposalStatusCount) ? $proposalStatusCount->toArray() : $proposalStatusCount;
        $data['estimateStatusCount'] = is_object($estimateStatusCount) ? $estimateStatusCount->toArray() : $estimateStatusCount;
        
        // Obtener datos de clientes y extraer solo el total
        $customerData = $this->customerRepository->customerCount();
        $data['customerCount'] = is_object($customerData) ? ($customerData->total_customers ?? 0) : (is_array($customerData) ? ($customerData['total_customers'] ?? 0) : $customerData);
        
        // Obtener datos de miembros y extraer solo el total
        $memberData = $this->memberRepository->memberCount();
        $data['memberCount'] = is_object($memberData) ? ($memberData->total_members ?? 0) : (is_array($memberData) ? ($memberData['total_members'] ?? 0) : $memberData);
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

        // Get inventory and messaging statistics
        $inventoryAlerts = $this->getInventoryAlerts();
        $materialsStats = $this->getMaterialsStats();
        $messageStats = $this->getMessageStats();

        // Extract variables from data array
        $projectStatusCount = $data['projectStatusCount'];
        $invoiceStatusCount = $data['invoiceStatusCount'];
        $proposalStatusCount = $data['proposalStatusCount'];
        $estimateStatusCount = $data['estimateStatusCount'];
        $customerCount = $data['customerCount'];
        $memberCount = $data['memberCount'];
        $contractsCurrentMonths = $data['contractsCurrentMonths'];
        $currentMonth = $data['currentMonth'];
        $currentWeekInvoices = $data['currentWeekInvoices'];

        return view('dashboard.dashboard', compact('projectStatusCount', 'invoiceStatusCount', 'estimateStatusCount',
            'proposalStatusCount', 'memberCount', 'customerCount', 'monthWiseRecords', 'currentWeekInvoices',
            'contractsCurrentMonths', 'months', 'currentMonth', 'inventoryAlerts', 'materialsStats', 'messageStats'));
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

    /**
     * Get inventory alerts for dashboard
     */
    public function getInventoryAlerts()
    {
        $lowStockItems = Product::getLowStockItems(5);
        $outOfStockItems = Product::getOutOfStockItems(5);
        $pendingRequisitions = MaterialRequisition::where('status', 'pending')->count();
        
        return [
            'low_stock_count' => $lowStockItems->count(),
            'out_of_stock_count' => $outOfStockItems->count(),
            'pending_requisitions' => $pendingRequisitions,
            'low_stock_items' => $lowStockItems,
            'out_of_stock_items' => $outOfStockItems
        ];
    }

    /**
     * Get materials statistics
     */
    public function getMaterialsStats()
    {
        $totalMaterials = Product::where('status', 'active')->count();
        $totalValue = Product::where('status', 'active')
            ->selectRaw('SUM(stock_quantity * cost_price) as total_value')
            ->first()->total_value ?? 0;
        $lowStockMaterials = Product::getLowStockItems()->count();
        $outOfStockMaterials = Product::getOutOfStockItems()->count();
        
        return [
            'total_materials' => $totalMaterials,
            'total_value' => $totalValue,
            'low_stock_materials' => $lowStockMaterials,
            'out_of_stock_materials' => $outOfStockMaterials,
        ];
    }

    /**
     * Get messaging statistics for dashboard
     */
    public function getMessageStats()
    {
        $userId = Auth::id();
        
        // Get unread messages count
        $unreadCount = InternalMessage::where(function($query) use ($userId) {
            $query->whereJsonContains('recipients', $userId)
                  ->where(function($q) use ($userId) {
                      $q->whereNull('read_by')
                        ->orWhereJsonDoesntContain('read_by', $userId);
                  });
        })->count();

        // Get today's messages count
        $todayCount = InternalMessage::where(function($query) use ($userId) {
            $query->whereJsonContains('recipients', $userId);
        })->whereDate('created_at', today())->count();

        // Get sent messages count
        $sentCount = InternalMessage::where('sender_id', $userId)->count();

        // Get total messages count
        $totalCount = InternalMessage::where(function($query) use ($userId) {
            $query->where('sender_id', $userId)
                  ->orWhereJsonContains('recipients', $userId);
        })->count();

        return [
            'unread_count' => $unreadCount,
            'today_count' => $todayCount,
            'sent_count' => $sentCount,
            'total_count' => $totalCount,
        ];
    }
}
