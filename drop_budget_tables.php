<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Desactivar temporalmente las restricciones de clave foránea
DB::statement('SET FOREIGN_KEY_CHECKS=0');

// Eliminar las tablas si existen
DB::statement('DROP TABLE IF EXISTS budget_alerts');
DB::statement('DROP TABLE IF EXISTS budget_expenses');
DB::statement('DROP TABLE IF EXISTS budget_controls');

// Reactivar las restricciones de clave foránea
DB::statement('SET FOREIGN_KEY_CHECKS=1');

// Eliminar los registros de migración relacionados con estas tablas
DB::table('migrations')
    ->whereIn('migration', [
        '2025_08_19_153313_create_budget_controls_table',
        '2025_08_19_153318_create_budget_expenses_table',
        '2025_08_19_153326_create_budget_alerts_table'
    ])
    ->delete();

echo "Tablas de presupuesto y registros de migración eliminados correctamente.\n";
echo "Ahora puede ejecutar 'php artisan migrate' nuevamente.\n";
