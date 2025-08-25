<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Leer el archivo SQL
$sql = file_get_contents(__DIR__ . '/create_budget_tables.sql');

// Dividir en consultas individuales
$queries = explode(';', $sql);

// Ejecutar cada consulta
foreach ($queries as $query) {
    $query = trim($query);
    if (!empty($query)) {
        try {
            DB::statement($query);
            echo "Consulta ejecutada con éxito: " . substr($query, 0, 50) . "...\n";
        } catch (\Exception $e) {
            echo "Error al ejecutar consulta: " . $e->getMessage() . "\n";
            echo "Consulta: " . $query . "\n\n";
        }
    }
}

echo "\nVerificando tablas creadas:\n";
$tables = ['budget_controls', 'budget_expenses', 'budget_alerts'];

foreach ($tables as $table) {
    $exists = DB::select("SHOW TABLES LIKE '{$table}'");
    echo "- Tabla {$table}: " . (count($exists) > 0 ? "Creada correctamente" : "No existe") . "\n";
    
    if (count($exists) > 0) {
        $columns = DB::select("SHOW COLUMNS FROM {$table}");
        echo "  Columnas: " . count($columns) . "\n";
        
        // Mostrar algunas columnas para verificar
        echo "  Primeras columnas: ";
        for ($i = 0; $i < min(5, count($columns)); $i++) {
            echo $columns[$i]->Field . ", ";
        }
        echo "...\n";
    }
}

echo "\nProceso completado.\n";
