<?php

namespace Database\Seeders;

use App\Models\Contract;
use App\Models\Customer;
use App\Models\Estimate;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Member;
use App\Models\Project;
use App\Models\Proposal;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ConstructionDashboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear clientes de construcción
        $customers = [
            [
                'company_name' => 'Constructora San Miguel S.A.',
                'vat_number' => '08011999000123',
                'phone' => '+504 2234-5678',
                'website' => 'www.sanmiguel.com',
                'currency' => 'HNL',
                'country' => 'Honduras',
                'default_language' => 'es',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_name' => 'Desarrollos Inmobiliarios del Norte',
                'vat_number' => '08011999000456',
                'phone' => '+504 2556-7890',
                'website' => 'www.dinorte.com',
                'currency' => 'HNL',
                'country' => 'Honduras',
                'default_language' => 'es',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_name' => 'Proyectos Residenciales La Ceiba',
                'vat_number' => '08011999000789',
                'phone' => '+504 2443-1234',
                'website' => 'www.prceiba.com',
                'currency' => 'HNL',
                'country' => 'Honduras',
                'default_language' => 'es',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($customers as $customerData) {
            Customer::create($customerData);
        }

        // Crear proyectos de construcción
        $projects = [
            [
                'project_name' => 'Residencial Las Torres',
                'customer_id' => 1,
                'start_date' => Carbon::now()->addDays(30),
                'deadline' => Carbon::now()->addMonths(10),
                'description' => 'Complejo residencial de 120 apartamentos en 4 torres de 15 pisos cada una.',
                'status' => 2, // En progreso
                'billing_type' => 1,
                'send_email' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_name' => 'Centro Comercial Plaza Norte',
                'customer_id' => 2,
                'start_date' => Carbon::now()->subMonths(4),
                'deadline' => Carbon::now()->addMonths(12),
                'description' => 'Centro comercial de 3 niveles con 80 locales comerciales.',
                'status' => 2, // En progreso
                'billing_type' => 1,
                'send_email' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_name' => 'Complejo Industrial La Ceiba',
                'customer_id' => 3,
                'start_date' => Carbon::now()->subMonths(2),
                'deadline' => Carbon::now()->addMonths(8),
                'description' => 'Complejo industrial con 5 bodegas de 2000m² cada una.',
                'status' => 1, // Planificado
                'billing_type' => 1,
                'send_email' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_name' => 'Remodelación Hospital San Pedro',
                'customer_id' => 1,
                'start_date' => Carbon::now()->addDays(15),
                'deadline' => Carbon::now()->addMonths(5),
                'description' => 'Remodelación completa del ala norte del hospital.',
                'status' => 0, // Pendiente
                'billing_type' => 1,
                'send_email' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($projects as $projectData) {
            Project::create($projectData);
        }

        // Crear facturas
        $invoices = [
            [
                'invoice_id' => 'INV-2024-001',
                'customer_id' => 1,
                'invoice_date' => Carbon::now()->subDays(15),
                'due_date' => Carbon::now()->addDays(15),
                'amount' => 2500000.00,
                'final_amount' => 2500000.00,
                'note' => 'Pago parcial Residencial Las Torres - Fase 1',
                'status' => 2, // Parcialmente pagada
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'invoice_id' => 'INV-2024-002',
                'customer_id' => 2,
                'invoice_date' => Carbon::now()->subDays(30),
                'due_date' => Carbon::now()->subDays(15),
                'amount' => 3750000.00,
                'final_amount' => 3750000.00,
                'note' => 'Avance Centro Comercial Plaza Norte - Cimentación',
                'status' => 1, // Pagada
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'invoice_id' => 'INV-2024-003',
                'customer_id' => 3,
                'invoice_date' => Carbon::now()->subDays(7),
                'due_date' => Carbon::now()->addDays(23),
                'amount' => 1200000.00,
                'final_amount' => 1200000.00,
                'note' => 'Complejo Industrial La Ceiba - Materiales',
                'status' => 4, // Enviada
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($invoices as $invoiceData) {
            Invoice::create($invoiceData);
        }

        // Crear propuestas
        $proposals = [
            [
                'proposal_id' => 'PROP-2024-001',
                'customer_id' => 1,
                'proposal_date' => Carbon::now()->subDays(45),
                'amount' => 18000000.00,
                'final_amount' => 15000000.00,
                'note' => 'Propuesta para Residencial Las Torres - Versión Final',
                'status' => 3, // Aceptada
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'proposal_id' => 'PROP-2024-002',
                'customer_id' => 2,
                'proposal_date' => Carbon::now()->subDays(60),
                'amount' => 28000000.00,
                'final_amount' => 25000000.00,
                'note' => 'Centro Comercial Plaza Norte - Propuesta Técnica',
                'status' => 3, // Aceptada
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'proposal_id' => 'PROP-2024-003',
                'customer_id' => 3,
                'proposal_date' => Carbon::now()->subDays(20),
                'amount' => 8500000.00,
                'final_amount' => 8000000.00,
                'note' => 'Complejo Industrial La Ceiba - Propuesta Económica',
                'status' => 3, // Aceptada
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($proposals as $proposalData) {
            Proposal::create($proposalData);
        }

        // Crear estimaciones
        $estimates = [
            [
                'estimate_id' => 'EST-2024-001',
                'customer_id' => 1,
                'estimate_date' => Carbon::now()->subDays(10),
                'amount' => 750000.00,
                'final_amount' => 750000.00,
                'note' => 'Estimación adicional - Acabados especiales Torre A',
                'status' => 2, // Enviada
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'estimate_id' => 'EST-2024-002',
                'customer_id' => 2,
                'estimate_date' => Carbon::now()->subDays(5),
                'amount' => 1250000.00,
                'final_amount' => 1250000.00,
                'note' => 'Estimación cambios en área de comidas',
                'status' => 1, // Borrador
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($estimates as $estimateData) {
            Estimate::create($estimateData);
        }

        // Crear contratos
        $contracts = [
            [
                'subject' => 'Contrato Residencial Las Torres',
                'customer_id' => 1,
                'start_date' => Carbon::now()->subMonths(2),
                'end_date' => Carbon::now()->addMonths(8),
                'contract_value' => 15000000.00,
                'description' => 'Contrato principal para construcción de Residencial Las Torres',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subject' => 'Contrato Centro Comercial Plaza Norte',
                'customer_id' => 2,
                'start_date' => Carbon::now()->subMonths(4),
                'end_date' => Carbon::now()->addMonths(12),
                'contract_value' => 25000000.00,
                'description' => 'Contrato para construcción de Centro Comercial Plaza Norte',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subject' => 'Contrato Complejo Industrial',
                'customer_id' => 3,
                'start_date' => Carbon::now()->subMonths(1),
                'end_date' => Carbon::now()->addDays(20), // Próximo a vencer
                'contract_value' => 8000000.00,
                'description' => 'Contrato para Complejo Industrial La Ceiba',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($contracts as $contractData) {
            Contract::create($contractData);
        }

        // Crear gastos
        $expenses = [
            [
                'title' => 'Cemento Portland - Residencial Las Torres',
                'amount' => 450000.00,
                'expense_date' => Carbon::now()->subDays(20),
                'expense_category_id' => 1,
                'payment_mode_id' => 1,
                'note' => 'Compra de 500 sacos de cemento Portland',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Acero de refuerzo - Plaza Norte',
                'amount' => 850000.00,
                'expense_date' => Carbon::now()->subDays(15),
                'expense_category_id' => 1,
                'payment_mode_id' => 2,
                'note' => 'Varillas de acero #4 y #6 para estructura',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Alquiler de grúa torre',
                'amount' => 125000.00,
                'expense_date' => Carbon::now()->subDays(10),
                'expense_category_id' => 2,
                'payment_mode_id' => 1,
                'note' => 'Alquiler mensual grúa torre 40m',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Nómina personal de obra',
                'amount' => 680000.00,
                'expense_date' => Carbon::now()->subDays(5),
                'expense_category_id' => 3,
                'payment_mode_id' => 1,
                'note' => 'Pago quincenal personal de construcción',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($expenses as $expenseData) {
            Expense::create($expenseData);
        }

        // Crear miembros del equipo
        $members = [
            [
                'first_name' => 'Juan Carlos',
                'last_name' => 'Pérez',
                'email' => 'jperez@martinez.com',
                'phone' => '+504 9876-5432',
                'designation' => 'Ingeniero Civil Senior',
                'department_id' => 1,
                'role' => 'supervisor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Ana Sofía',
                'last_name' => 'López',
                'email' => 'alopez@martinez.com',
                'phone' => '+504 8765-4321',
                'designation' => 'Arquitecta de Proyectos',
                'department_id' => 2,
                'role' => 'manager',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Miguel Ángel',
                'last_name' => 'Hernández',
                'email' => 'mhernandez@martinez.com',
                'phone' => '+504 7654-3210',
                'designation' => 'Maestro de Obra',
                'department_id' => 1,
                'role' => 'worker',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($members as $memberData) {
            Member::create($memberData);
        }

        $this->command->info('✅ Datos de construcción creados exitosamente para el dashboard');
        $this->command->info('📊 Creados: 3 clientes, 4 proyectos, 3 facturas, 3 propuestas, 2 estimaciones, 3 contratos, 4 gastos, 3 miembros');
    }
}
