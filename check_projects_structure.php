<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Verificar la estructura de la tabla projects
$projectsColumns = DB::select('SHOW COLUMNS FROM projects');
echo "Estructura de la tabla projects:\n";
foreach ($projectsColumns as $column) {
    echo "- {$column->Field}: {$column->Type} (Nullable: {$column->Null}, Key: {$column->Key})\n";
}

// Verificar el engine de la tabla projects
$projectsEngine = DB::select('SHOW TABLE STATUS WHERE Name = "projects"');
echo "\nEngine de la tabla projects: " . $projectsEngine[0]->Engine . "\n";

// Verificar el charset y collation de la tabla projects
echo "Charset: " . $projectsEngine[0]->Collation . "\n";

echo "\nPara resolver el problema de las migraciones, vamos a modificar las migraciones para que coincidan con la estructura de la tabla projects.\n";
