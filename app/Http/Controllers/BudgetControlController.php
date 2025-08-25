<?php

namespace App\Http\Controllers;

use App\Models\BudgetControl;
use App\Models\BudgetExpense;
use App\Models\ExpenseCategory;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BudgetControlController extends Controller
{
    /**
     * Display a listing of the budget controls.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $budgetControls = BudgetControl::with(['project'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('budget_controls.index', compact('budgetControls'));
    }

    /**
     * Show the form for creating a new budget control.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $projects = Project::pluck('project_name', 'id');
        
        return view('budget_controls.create', compact('projects'));
    }

    /**
     * Store a newly created budget control in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'total_budget' => 'required|numeric|min:0',
            'alert_threshold' => 'required|numeric|min:1|max:100',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if budget control already exists for this project
        $existingBudget = BudgetControl::where('project_id', $request->project_id)->first();
        if ($existingBudget) {
            return redirect()->back()
                ->with('error', 'Ya existe un control de presupuesto para este proyecto.')
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $budgetControl = new BudgetControl();
            $budgetControl->project_id = $request->project_id;
            $budgetControl->total_budget = $request->total_budget;
            $budgetControl->remaining_budget = $request->total_budget;
            $budgetControl->current_spent = 0;
            $budgetControl->budget_status = BudgetControl::STATUS_HEALTHY;
            $budgetControl->alert_threshold = $request->alert_threshold;
            $budgetControl->notes = $request->notes;
            $budgetControl->created_by = Auth::id();
            $budgetControl->last_updated = now();
            $budgetControl->save();

            DB::commit();
            
            return redirect()->route('budget-controls.show', $budgetControl->id)
                ->with('success', 'Control de presupuesto creado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al crear el control de presupuesto: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified budget control.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $budgetControl = BudgetControl::with(['project', 'expenses.category', 'alerts'])
            ->findOrFail($id);
            
        $expenses = $budgetControl->expenses()
            ->with(['category', 'creator'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        $alerts = $budgetControl->alerts()
            ->orderBy('created_at', 'desc')
            ->paginate(5);
            
        return view('budget_controls.show', compact('budgetControl', 'expenses', 'alerts'));
    }

    /**
     * Show the form for editing the specified budget control.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $budgetControl = BudgetControl::findOrFail($id);
        $projects = Project::pluck('project_name', 'id');
        
        return view('budget_controls.edit', compact('budgetControl', 'projects'));
    }

    /**
     * Update the specified budget control in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'total_budget' => 'required|numeric|min:0',
            'alert_threshold' => 'required|numeric|min:1|max:100',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $budgetControl = BudgetControl::findOrFail($id);
            
            // Calculate the difference in budget
            $budgetDifference = $request->total_budget - $budgetControl->total_budget;
            
            $budgetControl->total_budget = $request->total_budget;
            $budgetControl->remaining_budget = $budgetControl->remaining_budget + $budgetDifference;
            $budgetControl->alert_threshold = $request->alert_threshold;
            $budgetControl->notes = $request->notes;
            $budgetControl->last_updated = now();
            $budgetControl->save();
            
            // Update budget status based on new values
            $budgetControl->updateBudgetStatus();

            DB::commit();
            
            return redirect()->route('budget-controls.show', $budgetControl->id)
                ->with('success', 'Control de presupuesto actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al actualizar el control de presupuesto: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified budget control from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $budgetControl = BudgetControl::findOrFail($id);
            
            // Check if there are expenses
            if ($budgetControl->expenses()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'No se puede eliminar el control de presupuesto porque tiene gastos asociados.');
            }
            
            $budgetControl->delete();
            
            return redirect()->route('budget-controls.index')
                ->with('success', 'Control de presupuesto eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar el control de presupuesto: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for adding a new expense to the budget control.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addExpenseForm($id)
    {
        $budgetControl = BudgetControl::with('project')->findOrFail($id);
        $categories = ExpenseCategory::pluck('name', 'id');
        
        return view('budget_controls.add_expense', compact('budgetControl', 'categories'));
    }

    /**
     * Store a newly created expense in the budget control.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function storeExpense(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'category_id' => 'required|exists:expense_categories,id',
            'expense_date' => 'nullable|date',
            'receipt' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $budgetControl = BudgetControl::findOrFail($id);
            
            // Create the expense
            $expense = new BudgetExpense();
            $expense->budget_control_id = $budgetControl->id;
            $expense->amount = $request->amount;
            $expense->description = $request->description;
            $expense->category_id = $request->category_id;
            $expense->expense_date = $request->expense_date ?? now();
            $expense->created_by = Auth::id();
            $expense->notes = $request->notes;
            $expense->save();
            
            // Handle receipt upload
            if ($request->hasFile('receipt')) {
                $file = $request->file('receipt');
                $path = $file->store('receipts', 'public');
                $expense->receipt_path = $path;
                $expense->save();
            }
            
            // Update budget control
            $budgetControl->current_spent += $expense->amount;
            $budgetControl->remaining_budget = max(0, $budgetControl->total_budget - $budgetControl->current_spent);
            $budgetControl->last_updated = now();
            $budgetControl->save();
            
            // Update budget status
            $budgetControl->updateBudgetStatus();
            
            // Check if budget threshold is exceeded
            $budgetControl->checkBudgetThreshold();

            DB::commit();
            
            return redirect()->route('budget-controls.show', $budgetControl->id)
                ->with('success', 'Gasto agregado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al agregar el gasto: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Generate a budget report for the specified budget control.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function generateReport($id)
    {
        $budgetControl = BudgetControl::with(['project', 'expenses.category'])
            ->findOrFail($id);
            
        // Group expenses by category
        $expensesByCategory = $budgetControl->expenses()
            ->with('category')
            ->get()
            ->groupBy('category_id')
            ->map(function ($items, $key) {
                $category = $items->first()->category;
                return [
                    'category_name' => $category->name,
                    'total_amount' => $items->sum('amount'),
                    'count' => $items->count(),
                    'percentage' => 0 // Will be calculated below
                ];
            })
            ->sortByDesc('total_amount')
            ->values();
            
        // Calculate percentages
        $totalExpenses = $budgetControl->current_spent;
        $expensesByCategory = $expensesByCategory->map(function ($item) use ($totalExpenses) {
            $item['percentage'] = $totalExpenses > 0 ? round(($item['total_amount'] / $totalExpenses) * 100, 2) : 0;
            return $item;
        });
        
        return view('budget_controls.report', compact('budgetControl', 'expensesByCategory'));
    }

    /**
     * Export the budget report to PDF.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function exportReportPdf($id)
    {
        $budgetControl = BudgetControl::with(['project', 'expenses.category'])
            ->findOrFail($id);
            
        // Group expenses by category
        $expensesByCategory = $budgetControl->expenses()
            ->with('category')
            ->get()
            ->groupBy('category_id')
            ->map(function ($items, $key) {
                $category = $items->first()->category;
                return [
                    'category_name' => $category->name,
                    'total_amount' => $items->sum('amount'),
                    'count' => $items->count(),
                    'percentage' => 0 // Will be calculated below
                ];
            })
            ->sortByDesc('total_amount')
            ->values();
            
        // Calculate percentages
        $totalExpenses = $budgetControl->current_spent;
        $expensesByCategory = $expensesByCategory->map(function ($item) use ($totalExpenses) {
            $item['percentage'] = $totalExpenses > 0 ? round(($item['total_amount'] / $totalExpenses) * 100, 2) : 0;
            return $item;
        });
        
        $pdf = \PDF::loadView('budget_controls.report_pdf', compact('budgetControl', 'expensesByCategory'));
        
        return $pdf->download('reporte_presupuesto_' . $budgetControl->project->project_name . '.pdf');
    }
}
