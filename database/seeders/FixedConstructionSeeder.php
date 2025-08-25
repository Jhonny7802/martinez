<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Project;
use App\Models\Invoice;
use App\Models\Proposal;
use App\Models\Estimate;
use App\Models\Contract;
use App\Models\Expense;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FixedConstructionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear clientes usando solo columnas existentes
        $customers = [
            [
                'company_name' => 'Constructora San Miguel S.A.',
                'vat_number' => '08011999000123',
                'phone' => '+504 2234-5678',
                'website' => 'www.sanmiguel.com',
                'currency' => 'HNL',
                'country' => 'Honduras',
                'default_language' => 'es',
            ],
            [
                'company_name' => 'Desarrollos del Norte',
                'vat_number' => '08011999000456',
                'phone' => '+504 2556-7890',
                'website' => 'www.dinorte.com',
                'currency' => 'HNL',
                'country' => 'Honduras',
                'default_language' => 'es',
            ],
            [
                'company_name' => 'Proyectos La Ceiba',
                'vat_number' => '08011999000789',
                'phone' => '+504 2443-1234',
                'website' => 'www.prceiba.com',
                'currency' => 'HNL',
                'country' => 'Honduras',
                'default_language' => 'es',
            ],
        ];

        foreach ($customers as $customerData) {
            Customer::firstOrCreate(
                ['company_name' => $customerData['company_name']], 
                $customerData
            );
        }

        // Obtener IDs de clientes creados
        $customer1 = Customer::where('company_name', 'Constructora San Miguel S.A.')->first();
        $customer2 = Customer::where('company_name', 'Desarrollos del Norte')->first();
        $customer3 = Customer::where('company_name', 'Proyectos La Ceiba')->first();

        // Crear proyectos si existen los clientes
        if ($customer1 && $customer2 && $customer3) {
            $projects = [
                [
                    'project_name' => 'Residencial Las Torres',
                    'customer_id' => $customer1->id,
                    'start_date' => Carbon::now()->subMonths(2),
                    'deadline' => Carbon::now()->addMonths(8),
                    'project_summary' => 'Complejo residencial de 120 apartamentos',
                    'notes' => 'Incluye áreas verdes y parqueo',
                    'status' => 2,
                    'project_budget' => 15000000.00,
                ],
                [
                    'project_name' => 'Centro Comercial Plaza Norte',
                    'customer_id' => $customer2->id,
                    'start_date' => Carbon::now()->subMonths(4),
                    'deadline' => Carbon::now()->addMonths(12),
                    'project_summary' => 'Centro comercial de 3 niveles',
                    'notes' => 'Incluye área de comidas y cines',
                    'status' => 2,
                    'project_budget' => 25000000.00,
                ],
                [
                    'project_name' => 'Complejo Industrial La Ceiba',
                    'customer_id' => $customer3->id,
                    'start_date' => Carbon::now()->subMonths(1),
                    'deadline' => Carbon::now()->addMonths(6),
                    'project_summary' => 'Complejo industrial con 5 bodegas',
                    'notes' => 'Incluye oficinas administrativas',
                    'status' => 1,
                    'project_budget' => 8000000.00,
                ],
            ];

            foreach ($projects as $projectData) {
                Project::firstOrCreate(
                    ['project_name' => $projectData['project_name']], 
                    $projectData
                );
            }
        }

        $this->command->info('✅ Datos de construcción creados exitosamente');
        $this->command->info('📊 Creados: 3 clientes y 3 proyectos');
    }
}
