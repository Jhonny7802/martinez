<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

// Verificar si la tabla projects existe
if (!Schema::hasTable('projects')) {
    echo "ERROR: La tabla 'projects' no existe.\n";
    exit(1);
}

// Verificar y modificar la columna 'id' si es necesario
$columnInfo = DB::select("SHOW COLUMNS FROM projects WHERE Field = 'id'");
$idColumn = $columnInfo[0] ?? null;

if ($idColumn) {
    echo "Columna 'id' actual: " . $idColumn->Type . " " . 
         ($idColumn->Null === 'NO' ? 'NOT NULL' : 'NULL') . " " .
         ($idColumn->Extra ?: '') . "\n";
    
    // Si el tipo no es UNSIGNED, lo cambiamos
    if (strpos($idColumn->Type, 'unsigned') === false) {
        echo "Modificando columna 'id' para que sea UNSIGNED...\n";
        try {
            DB::statement("ALTER TABLE projects MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT");
            echo "Columna 'id' modificada correctamente.\n";
        } catch (\Exception $e) {
            echo "Error al modificar la columna 'id': " . $e->getMessage() . "\n";
        }
    } else {
        echo "La columna 'id' ya es UNSIGNED.\n";
    }
} else {
    echo "ERROR: No se pudo obtener información de la columna 'id'.\n";
    exit(1);
}

// Verificar si la tabla budget_controls existe y tiene la estructura correcta
if (Schema::hasTable('budget_controls')) {
    echo "\nLa tabla 'budget_controls' ya existe. Verificando estructura...\n";
    
    // Verificar si existe la restricción de clave foránea
    $foreignKeyExists = DB::select("
        SELECT * FROM information_schema.TABLE_CONSTRAINTS 
        WHERE CONSTRAINT_SCHEMA = DATABASE() 
        AND TABLE_NAME = 'budget_controls' 
        AND CONSTRAINT_NAME = 'budget_controls_project_id_foreign'
    ");
    
    if (count($foreignKeyExists) > 0) {
        echo "Eliminando restricción de clave foránea existente...\n";
        try {
            DB::statement("ALTER TABLE budget_controls DROP FOREIGN KEY budget_controls_project_id_foreign");
            echo "Restricción eliminada correctamente.\n";
        } catch (\Exception $e) {
            echo "Error al eliminar la restricción: " . $e->getMessage() . "\n";
        }
    }
    
    // Verificar y modificar la columna project_id si es necesario
    $projectIdColumn = DB::select("SHOW COLUMNS FROM budget_controls WHERE Field = 'project_id'");
    if ($projectIdColumn) {
        $projectIdType = $projectIdColumn[0]->Type;
        echo "\nTipo de columna 'project_id': " . $projectIdType . "\n";
        
        // Si el tipo no es UNSIGNED, lo cambiamos
        if (strpos($projectIdType, 'unsigned') === false) {
            echo "Modificando columna 'project_id' para que sea UNSIGNED...\n";
            try {
                DB::statement("ALTER TABLE budget_controls MODIFY project_id BIGINT UNSIGNED NOT NULL");
                echo "Columna 'project_id' modificada correctamente.\n";
            } catch (\Exception $e) {
                echo "Error al modificar la columna 'project_id': " . $e->getMessage() . "\n";
            }
        } else {
            echo "La columna 'project_id' ya es UNSIGNED.\n";
        }
    }
}

echo "\nProceso completado. Ahora puedes ejecutar las migraciones nuevamente.\n";
echo "Ejecuta: php artisan migrate\n";
