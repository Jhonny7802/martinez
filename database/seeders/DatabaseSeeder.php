<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Core system seeders
        $this->call(CountryTableSeeder::class);
        $this->call(LanguageTableSeeder::class);
        $this->call(PermissionTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(AdminUserSeeder::class);
        $this->call(SettingsTableSeeder::class);
        
        // Basic configuration seeders
        $this->call(ExpenseCategoryTableSeeder::class);
        $this->call(ItemGroupTableSeeder::class);
        $this->call(PaymentModesTableSeeder::class);
        $this->call(TaxRateTableSeeder::class);
        $this->call(DepartmentTableSeeder::class);
        
        // Status and category seeders
        $this->call(TicketStatusTableSeeder::class);
        $this->call(TicketPriorityTableSeeder::class);
        $this->call(CustomerGroupTableSeeder::class);
        $this->call(LeadSourceTableSeeder::class);
        $this->call(LeadStatusTableSeeder::class);
        $this->call(ContractTypeTableSeeder::class);
        $this->call(ServiceTableSeeder::class);
        
        // Construction specific seeders
        $this->call(ConstructionDashboardSeeder::class);
        
        // Final configuration
        $this->call(AddStripePaymentModeSeeder::class);
        $this->call(SetIsAdminSeeder::class);
        $this->call(DefaultCountryCode::class);
        
        // Create Super User with all permissions
        $this->call(SuperUserSeeder::class);
    }
}
