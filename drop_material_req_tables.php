<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0');
    \Illuminate\Support\Facades\DB::statement('DROP TABLE IF EXISTS material_requisition_items');
    \Illuminate\Support\Facades\DB::statement('DROP TABLE IF EXISTS material_requisitions');
    \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1');
    echo "Dropped tables: material_requisition_items, material_requisitions\n";
    exit(0);
} catch (\Throwable $e) {
    echo "Error: ".$e->getMessage()."\n";
    exit(1);
}
