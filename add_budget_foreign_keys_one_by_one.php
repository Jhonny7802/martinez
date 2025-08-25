<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Función para verificar si una tabla existe
function tableExists($tableName) {
    return Schema::hasTable($tableName);
}

// Función para mostrar la estructura de una columna
function showColumnStructure($tableName, $columnName) {
    $columns = DB::select("SHOW COLUMNS FROM {$tableName} WHERE Field = '{$columnName}'");
    if (count($columns) > 0) {
        $column = $columns[0];
        echo "Columna {$tableName}.{$columnName}: {$column->Type} (Nullable: {$column->Null}, Key: {$column->Key})\n";
        return $column;
    } else {
        echo "Columna {$tableName}.{$columnName} no encontrada.\n";
        return null;
    }
}

// Función para agregar una clave foránea
function addForeignKey($tableName, $columnName, $referencedTable, $referencedColumn, $onDelete = 'CASCADE') {
    $constraintName = "{$tableName}_{$columnName}_foreign";
    
    try {
        // Verificar que las tablas y columnas existan
        if (!tableExists($tableName)) {
            echo "ERROR: La tabla {$tableName} no existe.\n";
            return false;
        }
        
        if (!tableExists($referencedTable)) {
            echo "ERROR: La tabla {$referencedTable} no existe.\n";
            return false;
        }
        
        // Mostrar la estructura de las columnas
        $column = showColumnStructure($tableName, $columnName);
        $referencedCol = showColumnStructure($referencedTable, $referencedColumn);
        
        if (!$column || !$referencedCol) {
            return false;
        }
        
        // Verificar si la clave foránea ya existe
        $existingFK = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = '{$tableName}'
              AND COLUMN_NAME = '{$columnName}'
              AND REFERENCED_TABLE_NAME = '{$referencedTable}'
              AND REFERENCED_COLUMN_NAME = '{$referencedColumn}'
        ");
        
        if (count($existingFK) > 0) {
            echo "La clave foránea {$constraintName} ya existe.\n";
            return true;
        }
        
        // Intentar agregar la clave foránea
        DB::statement("ALTER TABLE `{$tableName}` ADD CONSTRAINT `{$constraintName}` FOREIGN KEY (`{$columnName}`) REFERENCES `{$referencedTable}` (`{$referencedColumn}`) ON DELETE {$onDelete}");
        
        echo "✓ Clave foránea agregada: {$tableName}.{$columnName} -> {$referencedTable}.{$referencedColumn}\n";
        return true;
    } catch (Exception $e) {
        echo "ERROR al agregar clave foránea {$constraintName}: " . $e->getMessage() . "\n";
        return false;
    }
}

// Verificar que las tablas existan
$requiredTables = ['projects', 'users', 'expense_categories', 'budget_controls', 'budget_expenses', 'budget_alerts'];
$allTablesExist = true;

echo "Verificando tablas requeridas...\n";
foreach ($requiredTables as $table) {
    $exists = tableExists($table);
    echo "- {$table}: " . ($exists ? "EXISTE" : "NO EXISTE") . "\n";
    if (!$exists) {
        $allTablesExist = false;
    }
}

if (!$allTablesExist) {
    echo "\nNo todas las tablas requeridas existen. Por favor, cree las tablas primero.\n";
    exit(1);
}

// Desactivar verificación de claves foráneas temporalmente
DB::statement('SET FOREIGN_KEY_CHECKS=0');

echo "\nAgregando claves foráneas...\n";

// Claves foráneas para budget_controls
addForeignKey('budget_controls', 'project_id', 'projects', 'id');

// Claves foráneas para budget_expenses
addForeignKey('budget_expenses', 'budget_control_id', 'budget_controls', 'id');
addForeignKey('budget_expenses', 'project_id', 'projects', 'id');
addForeignKey('budget_expenses', 'category_id', 'expense_categories', 'id', 'SET NULL');
addForeignKey('budget_expenses', 'created_by', 'users', 'id');
addForeignKey('budget_expenses', 'approved_by', 'users', 'id', 'SET NULL');

// Claves foráneas para budget_alerts
addForeignKey('budget_alerts', 'budget_control_id', 'budget_controls', 'id');
addForeignKey('budget_alerts', 'project_id', 'projects', 'id');
addForeignKey('budget_alerts', 'created_by', 'users', 'id');
addForeignKey('budget_alerts', 'acknowledged_by', 'users', 'id', 'SET NULL');
addForeignKey('budget_alerts', 'resolved_by', 'users', 'id', 'SET NULL');

// Reactivar verificación de claves foráneas
DB::statement('SET FOREIGN_KEY_CHECKS=1');

echo "\nProceso completado.\n";
