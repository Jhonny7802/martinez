<?php

namespace App\Http\Controllers;

use App\Models\BudgetAlert;
use App\Models\BudgetControl;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BudgetAlertController extends Controller
{
    /**
     * Display a listing of the budget alerts.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = BudgetAlert::with(['budgetControl.project', 'project']);
        
        // Filter by project if provided
        if ($request->has('project_id') && $request->project_id) {
            $query->where('project_id', $request->project_id);
        }
        
        // Filter by severity if provided
        if ($request->has('severity') && $request->severity) {
            $query->where('severity', $request->severity);
        }
        
        // Filter by acknowledgment status if provided
        if ($request->has('is_acknowledged')) {
            $query->where('is_acknowledged', $request->is_acknowledged == '1');
        }
        
        $alerts = $query->orderBy('created_at', 'desc')->paginate(15);
        $projects = Project::select('id', 'project_name')->get();
        $severities = BudgetAlert::SEVERITY_TEXT;
        
        // Calculate alert statistics for the view
        $allAlerts = BudgetAlert::all();
        $alertStats = [
            'critical' => $allAlerts->where('severity', BudgetAlert::SEVERITY_CRITICAL)->count(),
            'high' => $allAlerts->where('severity', BudgetAlert::SEVERITY_HIGH)->count(),
            'medium' => $allAlerts->where('severity', BudgetAlert::SEVERITY_MEDIUM)->count(),
            'low' => $allAlerts->where('severity', BudgetAlert::SEVERITY_LOW)->count()
        ];
        
        // Get projects with critical alerts
        $criticalProjects = $allAlerts->where('severity', '>=', BudgetAlert::SEVERITY_HIGH)
                                    ->groupBy('project_id')
                                    ->map(function ($items) {
                                        $project = $items->first()->project;
                                        if (!$project) return null;
                                        
                                        $budgetControl = $items->first()->budgetControl;
                                        $budgetUsage = $budgetControl ? 
                                            round(($budgetControl->current_spent / $budgetControl->total_budget) * 100, 1) : 0;
                                        
                                        return [
                                            'name' => $project->project_name,
                                            'alerts_count' => $items->count(),
                                            'critical_count' => $items->where('severity', '>=', BudgetAlert::SEVERITY_CRITICAL)->count(),
                                            'budget_usage' => $budgetUsage,
                                            'total_budget' => $budgetControl ? $budgetControl->total_budget : 0,
                                            'current_spent' => $budgetControl ? $budgetControl->current_spent : 0,
                                            'budget_status' => $budgetControl ? $budgetControl->budget_status : 'Sin control'
                                        ];
                                    })
                                    ->filter()
                                    ->sortByDesc('critical_count')
                                    ->take(5)
                                    ->values();
        
        return view('budget_alerts.index', compact('alerts', 'projects', 'severities', 'alertStats', 'criticalProjects'));
    }

    /**
     * Display the specified budget alert.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $alert = BudgetAlert::with(['budgetControl.project', 'project', 'creator', 'acknowledgedBy'])
            ->findOrFail($id);
            
        return view('budget_alerts.show', compact('alert'));
    }

    /**
     * Acknowledge the specified budget alert.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function acknowledge($id)
    {
        try {
            $alert = BudgetAlert::findOrFail($id);
            
            if ($alert->is_acknowledged) {
                return redirect()->back()
                    ->with('info', 'Esta alerta ya ha sido reconocida.');
            }
            
            $alert->acknowledge(Auth::id());
            
            return redirect()->route('budget-alerts.show', $alert->id)
                ->with('success', 'Alerta reconocida exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al reconocer la alerta: ' . $e->getMessage());
        }
    }

    /**
     * Acknowledge multiple budget alerts.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function acknowledgeMultiple(Request $request)
    {
        if (!$request->has('alert_ids') || !is_array($request->alert_ids) || count($request->alert_ids) == 0) {
            return redirect()->back()
                ->with('error', 'No se seleccionaron alertas para reconocer.');
        }
        
        try {
            $count = 0;
            foreach ($request->alert_ids as $alertId) {
                $alert = BudgetAlert::find($alertId);
                if ($alert && !$alert->is_acknowledged) {
                    $alert->acknowledge(Auth::id());
                    $count++;
                }
            }
            
            return redirect()->back()
                ->with('success', "{$count} alertas reconocidas exitosamente.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al reconocer las alertas: ' . $e->getMessage());
        }
    }

    /**
     * Generate a report of budget alerts.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function report(Request $request)
    {
        $query = BudgetAlert::with(['budgetControl.project', 'project']);
        
        // Filter by project if provided
        if ($request->has('project_id') && $request->project_id) {
            $query->where('project_id', $request->project_id);
        }
        
        // Filter by date range if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }
        
        $alerts = $query->orderBy('created_at', 'desc')->get();
        
        // Group alerts by project
        $alertsByProject = $alerts->groupBy('project_id')
            ->map(function ($items, $key) {
                $project = $items->first()->project;
                return [
                    'project_name' => $project->project_name,
                    'total_alerts' => $items->count(),
                    'acknowledged' => $items->where('is_acknowledged', true)->count(),
                    'unacknowledged' => $items->where('is_acknowledged', false)->count(),
                    'by_severity' => [
                        BudgetAlert::SEVERITY_INFO => $items->where('severity', BudgetAlert::SEVERITY_INFO)->count(),
                        BudgetAlert::SEVERITY_WARNING => $items->where('severity', BudgetAlert::SEVERITY_WARNING)->count(),
                        BudgetAlert::SEVERITY_CRITICAL => $items->where('severity', BudgetAlert::SEVERITY_CRITICAL)->count(),
                        BudgetAlert::SEVERITY_EMERGENCY => $items->where('severity', BudgetAlert::SEVERITY_EMERGENCY)->count(),
                    ]
                ];
            })
            ->sortByDesc('total_alerts')
            ->values();
            
        $projects = Project::pluck('project_name', 'id');
        $severities = BudgetAlert::SEVERITY_TEXT;
        
        return view('budget_alerts.report', compact('alertsByProject', 'projects', 'severities'));
    }

    /**
     * Export the budget alerts report to PDF.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportReportPdf(Request $request)
    {
        $query = BudgetAlert::with(['budgetControl.project', 'project']);
        
        // Filter by project if provided
        if ($request->has('project_id') && $request->project_id) {
            $query->where('project_id', $request->project_id);
        }
        
        // Filter by date range if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }
        
        $alerts = $query->orderBy('created_at', 'desc')->get();
        
        // Calculate alert statistics
        $alertStats = [
            'total' => $alerts->count(),
            'critical' => $alerts->where('severity', BudgetAlert::SEVERITY_CRITICAL)->count(),
            'high' => $alerts->where('severity', BudgetAlert::SEVERITY_HIGH)->count(),
            'medium' => $alerts->where('severity', BudgetAlert::SEVERITY_MEDIUM)->count(),
            'low' => $alerts->where('severity', BudgetAlert::SEVERITY_LOW)->count(),
            'acknowledged' => $alerts->where('is_acknowledged', true)->count(),
            'unacknowledged' => $alerts->where('is_acknowledged', false)->count(),
            'projects_affected' => $alerts->pluck('project_id')->unique()->count()
        ];
        
        // Get projects with critical alerts
        $criticalProjects = $alerts->where('severity', '>=', BudgetAlert::SEVERITY_HIGH)
                                  ->groupBy('project_id')
                                  ->map(function ($items) {
                                      $project = $items->first()->project;
                                      if (!$project) return null;
                                      
                                      $budgetControl = $items->first()->budgetControl;
                                      $budgetUsage = $budgetControl ? 
                                          round(($budgetControl->current_spent / $budgetControl->total_budget) * 100, 1) : 0;
                                      
                                      return [
                                          'name' => $project->project_name,
                                          'alerts_count' => $items->count(),
                                          'critical_count' => $items->where('severity', '>=', BudgetAlert::SEVERITY_CRITICAL)->count(),
                                          'budget_usage' => $budgetUsage,
                                          'budget_status' => $budgetControl ? $budgetControl->budget_status : null
                                      ];
                                  })
                                  ->filter()
                                  ->sortByDesc('critical_count')
                                  ->take(5)
                                  ->values();
        
        $pdf = \PDF::loadView('budget_alerts.report_pdf', compact('alerts', 'alertStats', 'criticalProjects'));
        
        return $pdf->download('reporte_alertas_presupuesto.pdf');
    }
    
    /**
     * Generate a detailed PDF report for budget alerts.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reportPdf(Request $request)
    {
        // Get all alerts with related data
        $query = BudgetAlert::with(['budgetControl.project', 'project', 'creator', 'acknowledgedBy']);
        
        // Apply filters if provided
        if ($request->has('project_id') && $request->project_id) {
            $query->where('project_id', $request->project_id);
        }
        
        if ($request->has('severity') && $request->severity) {
            $query->where('severity', $request->severity);
        }
        
        if ($request->has('is_acknowledged')) {
            $query->where('is_acknowledged', $request->is_acknowledged == '1');
        }
        
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }
        
        $alerts = $query->orderBy('severity', 'desc')
                        ->orderBy('created_at', 'desc')
                        ->get();
        
        // Calculate alert statistics
        $alertStats = [
            'total' => $alerts->count(),
            'critical' => $alerts->where('severity', BudgetAlert::SEVERITY_CRITICAL)->count(),
            'high' => $alerts->where('severity', BudgetAlert::SEVERITY_HIGH)->count(),
            'medium' => $alerts->where('severity', BudgetAlert::SEVERITY_MEDIUM)->count(),
            'low' => $alerts->where('severity', BudgetAlert::SEVERITY_LOW)->count(),
            'acknowledged' => $alerts->where('is_acknowledged', true)->count(),
            'unacknowledged' => $alerts->where('is_acknowledged', false)->count(),
            'projects_affected' => $alerts->pluck('project_id')->unique()->count()
        ];
        
        // Get projects with critical alerts
        $criticalProjects = $alerts->where('severity', '>=', BudgetAlert::SEVERITY_HIGH)
                                  ->groupBy('project_id')
                                  ->map(function ($items) {
                                      $project = $items->first()->project;
                                      $budgetControl = $items->first()->budgetControl;
                                      $budgetUsage = $budgetControl ? 
                                          round(($budgetControl->current_spent / $budgetControl->total_budget) * 100, 1) : 0;
                                      
                                      return [
                                          'name' => $project->project_name,
                                          'alerts_count' => $items->count(),
                                          'critical_count' => $items->where('severity', '>=', BudgetAlert::SEVERITY_CRITICAL)->count(),
                                          'budget_usage' => $budgetUsage,
                                          'budget_status' => $budgetControl ? $budgetControl->budget_status : null
                                      ];
                                  })
                                  ->sortByDesc('critical_count')
                                  ->take(5)
                                  ->values();
        
        // Generate PDF
        $pdf = \PDF::loadView('budget_alerts.report_pdf', compact('alerts', 'alertStats', 'criticalProjects'));
        $pdf->setPaper('a4');
        
        // Set filename with date
        $filename = 'reporte_alertas_presupuesto_' . now()->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }
}
