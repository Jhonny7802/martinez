<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BudgetTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Obtener IDs de proyectos existentes
        $projectIds = DB::table('projects')->pluck('id')->toArray();
        
        // Si no hay proyectos, crear uno de ejemplo
        if (empty($projectIds)) {
            $projectId = DB::table('projects')->insertGetId([
                'name' => 'Proyecto Residencial Las Torres',
                'client_id' => 1, // Asumiendo que hay al menos un cliente
                'start_date' => Carbon::now()->subMonths(2),
                'deadline' => Carbon::now()->addMonths(10),
                'status' => 'in_progress',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            $projectIds[] = $projectId;
        }
        
        // Obtener ID de usuario administrador
        $adminId = DB::table('users')->where('email', 'admin@gmail.com')->value('id');
        if (!$adminId) {
            $adminId = DB::table('users')->first()->id;
        }
        
        // Crear controles de presupuesto
        $budgetControlIds = [];
        $budgetNames = [
            'Presupuesto de Construcción - Fase 1',
            'Presupuesto de Acabados',
            'Presupuesto de Instalaciones Eléctricas',
            'Presupuesto de Plomería'
        ];
        
        foreach ($projectIds as $index => $projectId) {
            foreach ($budgetNames as $i => $name) {
                $initialBudget = rand(500000, 2000000);
                $spentAmount = rand(100000, $initialBudget * 0.8);
                $remainingAmount = $initialBudget - $spentAmount;
                $budgetPercent = ($spentAmount / $initialBudget) * 100;
                
                $budgetControlId = DB::table('budget_controls')->insertGetId([
                    'project_id' => $projectId,
                    'name' => $name,
                    'initial_budget' => $initialBudget,
                    'current_budget' => $initialBudget,
                    'spent_amount' => $spentAmount,
                    'remaining_amount' => $remainingAmount,
                    'budget_percent' => $budgetPercent,
                    'start_date' => Carbon::now()->subMonths(1),
                    'end_date' => Carbon::now()->addMonths(6),
                    'status' => 'active',
                    'description' => 'Presupuesto para ' . $name,
                    'notes' => 'Notas adicionales sobre el presupuesto',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                
                $budgetControlIds[] = $budgetControlId;
            }
        }
        
        // Crear gastos de presupuesto
        $expenseNames = [
            'Compra de cemento',
            'Compra de varillas de acero',
            'Pago a trabajadores',
            'Alquiler de maquinaria',
            'Compra de materiales eléctricos',
            'Compra de tuberías'
        ];
        
        foreach ($budgetControlIds as $budgetControlId) {
            $projectId = DB::table('budget_controls')->where('id', $budgetControlId)->value('project_id');
            
            // Obtener categoría de gastos
            $categoryId = DB::table('expense_categories')->first()->id ?? null;
            
            foreach ($expenseNames as $index => $name) {
                if ($index < 3) { // Solo crear algunos gastos
                    $amount = rand(10000, 100000);
                    
                    DB::table('budget_expenses')->insert([
                        'budget_control_id' => $budgetControlId,
                        'project_id' => $projectId,
                        'expense_name' => $name,
                        'amount' => $amount,
                        'expense_date' => Carbon::now()->subDays(rand(1, 30)),
                        'status' => ['pending', 'approved', 'rejected'][rand(0, 2)],
                        'payment_status' => ['unpaid', 'partial', 'paid'][rand(0, 2)],
                        'category_id' => $categoryId,
                        'receipt_number' => 'R-' . rand(1000, 9999),
                        'invoice_number' => 'INV-' . rand(1000, 9999),
                        'notes' => 'Notas sobre el gasto ' . $name,
                        'created_by' => $adminId,
                        'approved_by' => rand(0, 1) ? $adminId : null,
                        'approved_at' => rand(0, 1) ? Carbon::now() : null,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }
        }
        
        // Crear alertas de presupuesto
        foreach ($budgetControlIds as $budgetControlId) {
            $budgetControl = DB::table('budget_controls')->where('id', $budgetControlId)->first();
            
            if ($budgetControl->budget_percent > 70) {
                // Crear alerta de presupuesto si el porcentaje usado es alto
                $severity = $budgetControl->budget_percent > 90 ? 'critical' : ($budgetControl->budget_percent > 80 ? 'high' : 'medium');
                
                DB::table('budget_alerts')->insert([
                    'budget_control_id' => $budgetControlId,
                    'project_id' => $budgetControl->project_id,
                    'title' => 'Alerta de presupuesto excedido',
                    'description' => 'El presupuesto "' . $budgetControl->name . '" ha alcanzado el ' . number_format($budgetControl->budget_percent, 2) . '% de uso.',
                    'severity' => $severity,
                    'status' => 'active',
                    'threshold_percent' => 70.00,
                    'current_percent' => $budgetControl->budget_percent,
                    'budget_amount' => $budgetControl->initial_budget,
                    'spent_amount' => $budgetControl->spent_amount,
                    'created_by' => $adminId,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
        
        $this->command->info('Datos de presupuestos creados correctamente.');
    }
}
