<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Verificando estructura de la tabla projects...\n";

// Verificar si la tabla projects existe
$tableExists = DB::select("SHOW TABLES LIKE 'projects'");
if (empty($tableExists)) {
    echo "ERROR: La tabla 'projects' no existe.\n";
    exit(1);
}

// Mostrar la estructura de la tabla projects
echo "\nEstructura de la tabla projects:\n";
$columns = DB::select("SHOW COLUMNS FROM projects");
foreach ($columns as $column) {
    echo "- {$column->Field}: {$column->Type} " . 
         (strtoupper($column->Null) === 'YES' ? 'NULL' : 'NOT NULL') . 
         (isset($column->Default) ? " DEFAULT '{$column->Default}'" : '') . 
         "\n";
}

// Mostrar los índices
echo "\nÍndices de la tabla projects:\n";
$indexes = DB::select("SHOW INDEX FROM projects");
foreach ($indexes as $index) {
    echo "- {$index->Key_name} ({$index->Column_name}) - " . 
         ($index->Non_unique ? 'NO UNICO' : 'UNICO') . "\n";
}

echo "\nVerificación completada.\n";
