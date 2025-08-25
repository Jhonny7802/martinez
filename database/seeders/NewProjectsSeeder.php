
<?php


use App\Models\Contact;
use App\Models\Customer;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class NewProjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear nuevos clientes
        $customers = [
            [
                'company_name' => 'Inversiones Tegucigalpa S.A.',
                'vat_number' => '08011999001234',
                'phone' => '+504 2240-5678',
                'website' => 'www.inversionestegus.com',
                'currency' => 'HNL',
                'country' => 'Honduras',
                'default_language' => 'es',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_name' => 'Grupo Constructor Atlántida',
                'vat_number' => '08011999005678',
                'phone' => '+504 2445-9012',
                'website' => 'www.atlantidaconstructor.com',
                'currency' => 'HNL',
                'country' => 'Honduras',
                'default_language' => 'es',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_name' => 'Desarrollos Valle de Sula',
                'vat_number' => '08011999009012',
                'phone' => '+504 2550-3456',
                'website' => 'www.vallesula.com',
                'currency' => 'HNL',
                'country' => 'Honduras',
                'default_language' => 'es',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_name' => 'Constructora Copán Ruinas',
                'vat_number' => '08011999003456',
                'phone' => '+504 2651-7890',
                'website' => 'www.copanruinas.com',
                'currency' => 'HNL',
                'country' => 'Honduras',
                'default_language' => 'es',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_name' => 'Inmobiliaria Costa Caribe',
                'vat_number' => '08011999007890',
                'phone' => '+504 2440-1234',
                'website' => 'www.costacaribe.com',
                'currency' => 'HNL',
                'country' => 'Honduras',
                'default_language' => 'es',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        $customerIds = [];
        foreach ($customers as $customerData) {
            $customer = Customer::create($customerData);
            $customerIds[] = $customer->id;
        }

        // Crear usuarios para los contactos
        $users = [
            [
                'first_name' => 'Roberto',
                'last_name' => 'Mendoza',
                'email' => 'rmendoza@inversionestegus.com',
                'phone' => '+504 9988-7766',
                'password' => bcrypt('password123'),
                'is_enable' => 1,
                'owner_type' => Customer::class,
                'owner_id' => $customerIds[0],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'María Elena',
                'last_name' => 'Castillo',
                'email' => 'mcastillo@atlantidaconstructor.com',
                'phone' => '+504 9877-6655',
                'password' => bcrypt('password123'),
                'is_enable' => 1,
                'owner_type' => Customer::class,
                'owner_id' => $customerIds[1],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Carlos Alberto',
                'last_name' => 'Flores',
                'email' => 'cflores@vallesula.com',
                'phone' => '+504 9766-5544',
                'password' => bcrypt('password123'),
                'is_enable' => 1,
                'owner_type' => Customer::class,
                'owner_id' => $customerIds[2],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Ana Lucía',
                'last_name' => 'Reyes',
                'email' => 'areyes@copanruinas.com',
                'phone' => '+504 9655-4433',
                'password' => bcrypt('password123'),
                'is_enable' => 1,
                'owner_type' => Customer::class,
                'owner_id' => $customerIds[3],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'José Manuel',
                'last_name' => 'Vargas',
                'email' => 'jvargas@costacaribe.com',
                'phone' => '+504 9544-3322',
                'password' => bcrypt('password123'),
                'is_enable' => 1,
                'owner_type' => Customer::class,
                'owner_id' => $customerIds[4],
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        $userIds = [];
        foreach ($users as $userData) {
            $user = \App\Models\User::create($userData);
            $userIds[] = $user->id;
        }

        // Crear contactos asociados a los usuarios
        $contacts = [
            [
                'customer_id' => $customerIds[0],
                'user_id' => $userIds[0],
                'position' => 'Gerente de Proyectos',
                'primary_contact' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_id' => $customerIds[1],
                'user_id' => $userIds[1],
                'position' => 'Directora de Desarrollo',
                'primary_contact' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_id' => $customerIds[2],
                'user_id' => $userIds[2],
                'position' => 'Coordinador de Obras',
                'primary_contact' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_id' => $customerIds[3],
                'user_id' => $userIds[3],
                'position' => 'Jefa de Ingeniería',
                'primary_contact' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_id' => $customerIds[4],
                'user_id' => $userIds[4],
                'position' => 'Gerente Comercial',
                'primary_contact' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($contacts as $contactData) {
            Contact::create($contactData);
        }

        // Crear 5 nuevos proyectos
        $projects = [
            [
                'project_name' => 'Condominio Residencial Miraflores',
                'customer_id' => $customerIds[0],
                'start_date' => Carbon::now()->addDays(30),
                'deadline' => Carbon::now()->addMonths(14),
                'project_summary' => 'Desarrollo residencial de lujo con 85 unidades habitacionales en 3 torres de 12 pisos.',
                'notes' => 'Incluye amenidades: piscina infinity, gimnasio, salón de eventos, área de juegos infantiles y seguridad 24/7.',
                'status' => 0, // No iniciado
                'project_budget' => 22000000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_name' => 'Complejo Comercial Atlántida Plaza',
                'customer_id' => $customerIds[1],
                'start_date' => Carbon::now()->addDays(15),
                'deadline' => Carbon::now()->addMonths(18),
                'project_summary' => 'Centro comercial regional con 120 locales, supermercado ancla y área de entretenimiento.',
                'notes' => 'Proyecto incluye cines multiplex, food court, estacionamiento subterráneo para 800 vehículos.',
                'status' => 1, // En progreso
                'project_budget' => 35000000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_name' => 'Parque Industrial Valle Verde',
                'customer_id' => $customerIds[2],
                'start_date' => Carbon::now()->subDays(10),
                'deadline' => Carbon::now()->addMonths(10),
                'project_summary' => 'Complejo industrial con 8 naves industriales de 3000m² cada una para manufactura.',
                'notes' => 'Incluye subestación eléctrica, planta de tratamiento de aguas y oficinas administrativas.',
                'status' => 1, // En progreso
                'project_budget' => 18000000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_name' => 'Hotel Boutique Copán Heritage',
                'customer_id' => $customerIds[3],
                'start_date' => Carbon::now()->addDays(45),
                'deadline' => Carbon::now()->addMonths(16),
                'project_summary' => 'Hotel boutique de 45 habitaciones con arquitectura colonial moderna cerca de las ruinas.',
                'notes' => 'Diseño eco-friendly con materiales locales, spa, restaurante gourmet y centro de convenciones.',
                'status' => 0, // No iniciado
                'project_budget' => 12000000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'project_name' => 'Residencial Costa Azul',
                'customer_id' => $customerIds[4],
                'start_date' => Carbon::now()->addDays(20),
                'deadline' => Carbon::now()->addMonths(12),
                'project_summary' => 'Desarrollo residencial frente al mar con 60 casas de playa y villas de lujo.',
                'notes' => 'Proyecto incluye club de playa privado, marina, campo de golf de 9 hoyos y centro ecuestre.',
                'status' => 0, // No iniciado
                'project_budget' => 28000000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($projects as $projectData) {
            Project::create($projectData);
        }

        $this->command->info('✅ Nuevos proyectos creados exitosamente');
        $this->command->info('📊 Creados: 5 clientes nuevos, 5 contactos y 5 proyectos');
        $this->command->info('🏗️ Proyectos: Miraflores, Atlántida Plaza, Valle Verde, Copán Heritage, Costa Azul');
        $this->command->info('💰 Valor total de proyectos: L.115,000,000');
    }
}
