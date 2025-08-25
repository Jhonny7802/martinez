<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Verificar estructura detallada de la tabla projects
echo "Estructura detallada de la tabla projects:\n";

// Verificar si la tabla existe
$tableExists = DB::select("SHOW TABLES LIKE 'projects'");
if (empty($tableExists)) {
    echo "ERROR: La tabla 'projects' no existe.\n";
    exit(1);
}

// Mostrar la definición CREATE TABLE
echo "\nDefinición CREATE TABLE:\n";
$createTable = DB::select("SHOW CREATE TABLE projects");
echo $createTable[0]->{'Create Table'} . "\n\n";

// Mostrar las columnas
echo "Columnas de la tabla projects:\n";
$columns = DB::select("SHOW COLUMNS FROM projects");
foreach ($columns as $column) {
    echo "- {$column->Field}: {$column->Type} (Nullable: {$column->Null}, Key: {$column->Key}, Default: " . 
         (is_null($column->Default) ? "NULL" : $column->Default) . ", Extra: {$column->Extra})\n";
}

// Mostrar los índices
echo "\nÍndices de la tabla projects:\n";
$indices = DB::select("SHOW INDEX FROM projects");
foreach ($indices as $index) {
    echo "- {$index->Key_name}: Columna: {$index->Column_name}, No único: {$index->Non_unique}, Secuencia: {$index->Seq_in_index}\n";
}

// Mostrar las claves foráneas
echo "\nClaves foráneas en la tabla projects:\n";
$foreignKeys = DB::select("
    SELECT 
        CONSTRAINT_NAME,
        COLUMN_NAME,
        REFERENCED_TABLE_NAME,
        REFERENCED_COLUMN_NAME
    FROM
        information_schema.KEY_COLUMN_USAGE
    WHERE
        TABLE_SCHEMA = DATABASE() AND
        TABLE_NAME = 'projects' AND
        REFERENCED_TABLE_NAME IS NOT NULL
");

if (count($foreignKeys) > 0) {
    foreach ($foreignKeys as $fk) {
        echo "- {$fk->CONSTRAINT_NAME}: projects.{$fk->COLUMN_NAME} -> {$fk->REFERENCED_TABLE_NAME}.{$fk->REFERENCED_COLUMN_NAME}\n";
    }
} else {
    echo "- No se encontraron claves foráneas.\n";
}

// Mostrar el motor y charset
$tableStatus = DB::select("SHOW TABLE STATUS LIKE 'projects'");
echo "\nInformación adicional:\n";
echo "- Engine: {$tableStatus[0]->Engine}\n";
echo "- Charset: {$tableStatus[0]->Collation}\n";
echo "- Rows: {$tableStatus[0]->Rows}\n";

echo "\nVerificación completada.\n";
