<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run()
    {
        $settings = [
            // Company Information
            ['key' => 'company_name', 'value' => 'Martinez Construction'],
            ['key' => 'company_email', 'value' => 'info@martinez-construction.com'],
            ['key' => 'company_phone', 'value' => '+1-555-0123'],
            ['key' => 'company_address', 'value' => '123 Construction Ave, City, State 12345'],
            ['key' => 'company_website', 'value' => 'www.martinez-construction.com'],
            ['key' => 'company_logo', 'value' => 'assets/images/logo.png'],
            
            // System Settings
            ['key' => 'currency', 'value' => 'USD'],
            ['key' => 'currency_symbol', 'value' => '$'],
            ['key' => 'timezone', 'value' => 'America/New_York'],
            ['key' => 'date_format', 'value' => 'Y-m-d'],
            ['key' => 'time_format', 'value' => 'H:i:s'],
            ['key' => 'language', 'value' => 'es'],
            
            // Inventory Settings
            ['key' => 'low_stock_threshold', 'value' => '10'],
            ['key' => 'auto_reorder_enabled', 'value' => 'false'],
            ['key' => 'inventory_valuation_method', 'value' => 'FIFO'],
            ['key' => 'enable_barcode_scanning', 'value' => 'true'],
            
            // Messaging Settings
            ['key' => 'enable_email_notifications', 'value' => 'true'],
            ['key' => 'default_message_priority', 'value' => 'medium'],
            ['key' => 'max_attachment_size', 'value' => '10240'], // 10MB in KB
            ['key' => 'message_retention_days', 'value' => '365'],
            
            // Project Settings
            ['key' => 'default_project_status', 'value' => 'not_started'],
            ['key' => 'enable_project_notifications', 'value' => 'true'],
            ['key' => 'project_code_prefix', 'value' => 'PROJ'],
            ['key' => 'enable_budget_alerts', 'value' => 'true'],
            
            // Financial Settings
            ['key' => 'tax_rate', 'value' => '15.00'],
            ['key' => 'invoice_prefix', 'value' => 'INV'],
            ['key' => 'estimate_prefix', 'value' => 'EST'],
            ['key' => 'payment_terms_days', 'value' => '30'],
            
            // Security Settings
            ['key' => 'session_timeout', 'value' => '120'], // minutes
            ['key' => 'password_min_length', 'value' => '8'],
            ['key' => 'enable_two_factor', 'value' => 'false'],
            ['key' => 'max_login_attempts', 'value' => '5'],
            
            // Backup Settings
            ['key' => 'backup_frequency', 'value' => 'daily'],
            ['key' => 'backup_retention_days', 'value' => '30'],
            ['key' => 'enable_auto_backup', 'value' => 'true'],
            
            // Notification Settings
            ['key' => 'email_from_address', 'value' => 'noreply@martinez-construction.com'],
            ['key' => 'email_from_name', 'value' => 'Martinez Construction System'],
            ['key' => 'smtp_host', 'value' => 'smtp.gmail.com'],
            ['key' => 'smtp_port', 'value' => '587'],
            ['key' => 'smtp_encryption', 'value' => 'tls'],
            
            // Dashboard Settings
            ['key' => 'dashboard_refresh_interval', 'value' => '300'], // seconds
            ['key' => 'show_weather_widget', 'value' => 'true'],
            ['key' => 'default_dashboard_view', 'value' => 'construction'],
            ['key' => 'enable_real_time_updates', 'value' => 'true'],
            
            // Mobile App Settings
            ['key' => 'enable_mobile_app', 'value' => 'true'],
            ['key' => 'mobile_app_version', 'value' => '1.0.0'],
            ['key' => 'force_app_update', 'value' => 'false'],
            
            // Maintenance Settings
            ['key' => 'maintenance_mode', 'value' => 'false'],
            ['key' => 'maintenance_message', 'value' => 'Sistema en mantenimiento. Regrese en unos minutos.'],
            ['key' => 'last_backup_date', 'value' => now()->format('Y-m-d H:i:s')],
            ['key' => 'system_version', 'value' => '2.0.0'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info('Settings seeded successfully!');
    }
}
