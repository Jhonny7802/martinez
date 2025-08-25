<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Verificar si la tabla projects existe
$tables = DB::select('SHOW TABLES');
$tableName = 'projects';
$tableExists = false;

foreach ($tables as $table) {
    $tableArray = (array)$table;
    if (in_array($tableName, $tableArray)) {
        $tableExists = true;
        break;
    }
}

if (!$tableExists) {
    echo "La tabla 'projects' no existe en la base de datos.\n";
    exit(1);
}

echo "La tabla 'projects' existe. Verificando estructura...\n\n";

// Obtener la definición completa de la tabla
$createTable = DB::select("SHOW CREATE TABLE $tableName");
if (!empty($createTable)) {
    $createTable = (array)$createTable[0];
    echo "CREATE TABLE statement:\n";
    echo $createTable['Create Table'] . "\n\n";
}

// Mostrar información de columnas
echo "Columnas de la tabla 'projects':\n";
$columns = DB::select("SHOW COLUMNS FROM $tableName");
foreach ($columns as $column) {
    $column = (array)$column;
    echo "- {$column['Field']}: {$column['Type']} " . 
         ($column['Null'] === 'NO' ? 'NOT NULL' : 'NULL') . " " .
         (!is_null($column['Default']) ? "DEFAULT '{$column['Default']}'" : '') . " " .
         ($column['Extra'] ?: '') . "\n";
}

// Verificar si la tabla budget_controls existe
$tableName = 'budget_controls';
$tableExists = false;

foreach ($tables as $table) {
    $tableArray = (array)$table;
    if (in_array($tableName, $tableArray)) {
        $tableExists = true;
        break;
    }
}

if ($tableExists) {
    echo "\nLa tabla 'budget_controls' existe. Verificando estructura...\n\n";
    
    // Mostrar información de columnas
    echo "Columnas de la tabla 'budget_controls':\n";
    $columns = DB::select("SHOW COLUMNS FROM $tableName");
    foreach ($columns as $column) {
        $column = (array)$column;
        echo "- {$column['Field']}: {$column['Type']} " . 
             ($column['Null'] === 'NO' ? 'NOT NULL' : 'NULL') . " " .
             (!is_null($column['Default']) ? "DEFAULT '{$column['Default']}'" : '') . " " .
             ($column['Extra'] ?: '') . "\n";
    }
    
    // Mostrar claves foráneas
    echo "\nClaves foráneas en 'budget_controls':\n";
    $foreignKeys = DB::select("
        SELECT 
            TABLE_NAME,COLUMN_NAME,CONSTRAINT_NAME, REFERENCED_TABLE_NAME,REFERENCED_COLUMN_NAME
        FROM
n            INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE
            REFERENCED_TABLE_SCHEMA = DATABASE()
                AND REFERENCED_TABLE_NAME IS NOT NULL
                AND TABLE_NAME = '$tableName'
    ");
    
    if (count($foreignKeys) > 0) {
        foreach ($foreignKeys as $fk) {
            $fk = (array)$fk;
            echo "- {$fk['CONSTRAINT_NAME']}: {$fk['COLUMN_NAME']} -> {$fk['REFERENCED_TABLE_NAME']}({$fk['REFERENCED_COLUMN_NAME']})\n";
        }
    } else {
        echo "No se encontraron claves foráneas.\n";
    }
} else {
    echo "\nLa tabla 'budget_controls' no existe.\n";
}

echo "\nVerificación completada.\n";
