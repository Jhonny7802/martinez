<?php

namespace App\Http\Controllers;

use App\Models\BudgetExpense;
use App\Models\BudgetControl;
use App\Models\ExpenseCategory;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BudgetExpenseController extends Controller
{
    /**
     * Display a listing of the budget expenses.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Obtener proyectos que tienen gastos registrados
        $projects = Project::whereNotNull('last_expense_amount')
            ->orderBy('updated_at', 'desc')
            ->paginate(15);
            
        return view('budget_expenses.index', compact('projects'));
    }

    /**
     * Show the form for creating a new budget expense.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // Obtener proyectos directamente
        $projects = Project::pluck('project_name', 'id');
        
        // Obtener categorías de gastos
        $categories = ExpenseCategory::pluck('name', 'id');
        
        // Verificar si se proporcionó un project_id en la URL
        $selectedProjectId = $request->query('project_id');
        
        return view('budget_expenses.create', compact('categories', 'projects', 'selectedProjectId'));
    }

    /**
     * Store a newly created budget expense in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
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

        try {
            // Verificar que el proyecto existe
            $project = Project::findOrFail($request->project_id);
            
            // Actualizar el proyecto directamente con la información del gasto
            // Esto es una solución temporal hasta que se creen las tablas de presupuesto
            $project->last_expense_amount = $request->amount;
            $project->last_expense_date = $request->expense_date ?? now();
            $project->last_expense_description = $request->description;
            $project->total_expenses = ($project->total_expenses ?? 0) + $request->amount;
            $project->updated_at = now();
            $project->save();
            
            // Guardar el recibo si se proporcionó uno
            $receiptPath = null;
            if ($request->hasFile('receipt')) {
                $file = $request->file('receipt');
                $receiptPath = $file->store('receipts', 'public');
                $project->last_receipt_path = $receiptPath;
                $project->save();
            }
            
            // Registrar la categoría del gasto
            $categoryName = ExpenseCategory::find($request->category_id)->name ?? 'Sin categoría';
            
            // Guardar los datos del gasto en una sesión para mostrarlos en la vista
            session()->flash('expense_data', [
                'project_name' => $project->project_name,
                'amount' => $request->amount,
                'description' => $request->description,
                'category' => $categoryName,
                'expense_date' => $request->expense_date ?? now(),
                'receipt_path' => $receiptPath,
                'notes' => $request->notes
            ]);

            return redirect()->route('budget-expenses.index')
                ->with('success', 'Gasto registrado exitosamente para el proyecto "' . $project->project_name . '".');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al registrar el gasto: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified budget expense.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Verificar si hay datos de gasto en la sesión
        if (session()->has('expense_data')) {
            $expenseData = session('expense_data');
            return view('budget_expenses.show', ['expenseData' => $expenseData]);
        }
        
        // Si no hay datos en la sesión, redirigir al listado
        return redirect()->route('budget-expenses.index')
            ->with('warning', 'No se encontraron datos del gasto solicitado.');
    }

    /**
     * Show the form for editing the specified budget expense.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $expense = BudgetExpense::findOrFail($id);
        
        // Obtener proyectos directamente
        $projects = \App\Models\Project::pluck('project_name', 'id');
        
        // Obtener controles de presupuesto con sus proyectos
        $budgetControls = BudgetControl::with('project')
            ->get()
            ->pluck('project.project_name', 'id');
        $categories = ExpenseCategory::pluck('name', 'id');
        
        return view('budget_expenses.edit', compact('expense', 'budgetControls', 'categories', 'projects'));
    }

    /**
     * Update the specified budget expense in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
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

        try {
            $expense = BudgetExpense::findOrFail($id);
            
            // Update expense details (not amount, as it would affect budget calculations)
            $expense->description = $request->description;
            $expense->category_id = $request->category_id;
            $expense->expense_date = $request->expense_date;
            $expense->notes = $request->notes;
            
            // Handle receipt upload
            if ($request->hasFile('receipt')) {
                // Delete old receipt if exists
                if ($expense->receipt_path) {
                    Storage::disk('public')->delete($expense->receipt_path);
                }
                
                $file = $request->file('receipt');
                $path = $file->store('receipts', 'public');
                $expense->receipt_path = $path;
            }
            
            $expense->save();
            
            return redirect()->route('budget-expenses.show', $expense->id)
                ->with('success', 'Gasto actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar el gasto: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified budget expense from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $expense = BudgetExpense::findOrFail($id);
            $budgetControl = $expense->budgetControl;
            
            // Update budget control
            $budgetControl->current_spent -= $expense->amount;
            $budgetControl->remaining_budget = min($budgetControl->total_budget, $budgetControl->total_budget - $budgetControl->current_spent);
            $budgetControl->last_updated = now();
            $budgetControl->save();
            
            // Update budget status
            $budgetControl->updateBudgetStatus();
            
            // Delete receipt if exists
            if ($expense->receipt_path) {
                Storage::disk('public')->delete($expense->receipt_path);
            }
            
            // Delete expense
            $expense->delete();
            
            return redirect()->route('budget-expenses.index')
                ->with('success', 'Gasto eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar el gasto: ' . $e->getMessage());
        }
    }

    /**
     * Download the receipt for the specified budget expense.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function downloadReceipt($id)
    {
        try {
            $expense = BudgetExpense::findOrFail($id);
            
            if (!$expense->receipt_path) {
                return redirect()->back()
                    ->with('error', 'Este gasto no tiene un recibo adjunto.');
            }
            
            return Storage::disk('public')->download($expense->receipt_path);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al descargar el recibo: ' . $e->getMessage());
        }
    }
    
    /**
     * Download the receipt/media for the specified budget expense.
     * This is an alias for downloadReceipt to match the route name used in views.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function downloadMedia($id)
    {
        return $this->downloadReceipt($id);
    }
}
