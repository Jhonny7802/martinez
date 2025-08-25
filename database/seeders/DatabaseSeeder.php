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
        $this->call(PermissionTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(AdminUserSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(ExpenseCategoryTableSeeder::class);
        $this->call(ItemGroupTableSeeder::class);
        $this->call(PaymentModesTableSeeder::class);
        $this->call(TaxRateTableSeeder::class);
        $this->call(CountryTableSeeder::class);
        $this->call(LanguageTableSeeder::class);
        $this->call(ConstructionDashboardSeeder::class);
        $this->call(UpdatePermissionsSeeder::class); 
        $this->call(SuperAdminUserSeeder::class); 
        $this->call(BudgetTablesSeeder::class); // Agregar datos de presupuestos
        $this->call(TicketStatusTableSeeder::class);
        $this->call(CustomerGroupTableSeeder::class);
        $this->call(TicketPriorityTableSeeder::class);
        $this->call(TagTableSeeder::class);
        $this->call(LeadSourceTableSeeder::class);
        $this->call(ContractTypeTableSeeder::class);
        $this->call(DepartmentTableSeeder::class);
        $this->call(LeadStatusTableSeeder::class);
        $this->call(ServiceTableSeeder::class);
        $this->call(ApplicationNameTableSeeder::class);
        $this->call(AddStripePaymentModeSeeder::class);
        $this->call(CountryTableSeeder::class);
        $this->call(SetIsAdminSeeder::class);
        $this->call(DefaultCountryCode::class);
    }
}
