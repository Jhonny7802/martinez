<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\MaterialRequisition;
use App\Models\MaterialRequisitionItem;
use App\Models\InventoryMovement;
use App\Models\User;
use Carbon\Carbon;

class EnhancedInventorySeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run()
    {
        // Enhanced construction materials with realistic data
        $materials = [
            // Cemento y Concreto
            ['title' => 'Cemento Portland Tipo I - 50kg', 'description' => 'Cemento Portland para uso general en construcción', 'rate' => 8.50, 'cost_price' => 7.20, 'stock_quantity' => 150, 'minimum_stock' => 50, 'maximum_stock' => 300, 'unit_of_measure' => 'sacos', 'location' => 'Almacén A-1', 'supplier' => 'Cementos Nacionales', 'barcode' => 'CEM001', 'status' => 'active'],
            ['title' => 'Cemento Portland Tipo II - 50kg', 'description' => 'Cemento con resistencia moderada a sulfatos', 'rate' => 9.00, 'cost_price' => 7.65, 'stock_quantity' => 80, 'minimum_stock' => 30, 'maximum_stock' => 200, 'unit_of_measure' => 'sacos', 'location' => 'Almacén A-1', 'supplier' => 'Cementos Nacionales', 'barcode' => 'CEM002', 'status' => 'active'],
            ['title' => 'Concreto Premezclado 3000 PSI', 'description' => 'Concreto premezclado para estructuras', 'rate' => 85.00, 'cost_price' => 72.00, 'stock_quantity' => 25, 'minimum_stock' => 10, 'maximum_stock' => 50, 'unit_of_measure' => 'm³', 'location' => 'Patio Exterior', 'supplier' => 'Concretos del Valle', 'barcode' => 'CON001', 'status' => 'active'],
            
            // Acero y Varillas
            ['title' => 'Varilla Corrugada #3 (3/8") - 12m', 'description' => 'Varilla de acero corrugada para refuerzo', 'rate' => 12.50, 'cost_price' => 10.80, 'stock_quantity' => 200, 'minimum_stock' => 100, 'maximum_stock' => 500, 'unit_of_measure' => 'unidades', 'location' => 'Almacén B-2', 'supplier' => 'Aceros del Norte', 'barcode' => 'VAR003', 'status' => 'active'],
            ['title' => 'Varilla Corrugada #4 (1/2") - 12m', 'description' => 'Varilla de acero corrugada para refuerzo', 'rate' => 18.75, 'cost_price' => 16.20, 'stock_quantity' => 150, 'minimum_stock' => 80, 'maximum_stock' => 400, 'unit_of_measure' => 'unidades', 'location' => 'Almacén B-2', 'supplier' => 'Aceros del Norte', 'barcode' => 'VAR004', 'status' => 'active'],
            ['title' => 'Varilla Corrugada #5 (5/8") - 12m', 'description' => 'Varilla de acero corrugada para refuerzo', 'rate' => 28.50, 'cost_price' => 24.60, 'stock_quantity' => 100, 'minimum_stock' => 50, 'maximum_stock' => 300, 'unit_of_measure' => 'unidades', 'location' => 'Almacén B-2', 'supplier' => 'Aceros del Norte', 'barcode' => 'VAR005', 'status' => 'active'],
            ['title' => 'Alambre de Amarre #16', 'description' => 'Alambre galvanizado para amarre de varillas', 'rate' => 2.25, 'cost_price' => 1.95, 'stock_quantity' => 50, 'minimum_stock' => 20, 'maximum_stock' => 100, 'unit_of_measure' => 'kg', 'location' => 'Almacén B-1', 'supplier' => 'Alambres SA', 'barcode' => 'ALA001', 'status' => 'active'],
            
            // Blocks y Ladrillos
            ['title' => 'Block de Concreto 15x20x40cm', 'description' => 'Block hueco para mampostería', 'rate' => 1.85, 'cost_price' => 1.55, 'stock_quantity' => 2000, 'minimum_stock' => 500, 'maximum_stock' => 5000, 'unit_of_measure' => 'unidades', 'location' => 'Patio B', 'supplier' => 'Blocks Modernos', 'barcode' => 'BLK001', 'status' => 'active'],
            ['title' => 'Block de Concreto 10x20x40cm', 'description' => 'Block hueco para divisiones', 'rate' => 1.45, 'cost_price' => 1.25, 'stock_quantity' => 1500, 'minimum_stock' => 300, 'maximum_stock' => 3000, 'unit_of_measure' => 'unidades', 'location' => 'Patio B', 'supplier' => 'Blocks Modernos', 'barcode' => 'BLK002', 'status' => 'active'],
            ['title' => 'Ladrillo de Barro Rojo', 'description' => 'Ladrillo tradicional para mampostería', 'rate' => 0.85, 'cost_price' => 0.72, 'stock_quantity' => 3000, 'minimum_stock' => 1000, 'maximum_stock' => 8000, 'unit_of_measure' => 'unidades', 'location' => 'Patio C', 'supplier' => 'Ladrillera Central', 'barcode' => 'LAD001', 'status' => 'active'],
            
            // Arena y Grava
            ['title' => 'Arena de Río Lavada', 'description' => 'Arena fina para mezclas y acabados', 'rate' => 25.00, 'cost_price' => 21.50, 'stock_quantity' => 45, 'minimum_stock' => 15, 'maximum_stock' => 100, 'unit_of_measure' => 'm³', 'location' => 'Patio D', 'supplier' => 'Agregados del Valle', 'barcode' => 'ARE001', 'status' => 'active'],
            ['title' => 'Grava 3/4"', 'description' => 'Grava triturada para concreto', 'rate' => 28.00, 'cost_price' => 24.00, 'stock_quantity' => 35, 'minimum_stock' => 10, 'maximum_stock' => 80, 'unit_of_measure' => 'm³', 'location' => 'Patio D', 'supplier' => 'Agregados del Valle', 'barcode' => 'GRA001', 'status' => 'active'],
            ['title' => 'Piedrín 1/2"', 'description' => 'Piedrín para concreto y rellenos', 'rate' => 22.00, 'cost_price' => 19.00, 'stock_quantity' => 40, 'minimum_stock' => 12, 'maximum_stock' => 90, 'unit_of_measure' => 'm³', 'location' => 'Patio D', 'supplier' => 'Agregados del Valle', 'barcode' => 'PIE001', 'status' => 'active'],
            
            // Tubería y Plomería
            ['title' => 'Tubería PVC 4" SDR-35', 'description' => 'Tubería para drenajes sanitarios', 'rate' => 8.50, 'cost_price' => 7.25, 'stock_quantity' => 80, 'minimum_stock' => 30, 'maximum_stock' => 200, 'unit_of_measure' => 'metros', 'location' => 'Almacén C-1', 'supplier' => 'Tuberías Modernas', 'barcode' => 'TUB001', 'status' => 'active'],
            ['title' => 'Tubería PVC 2" SDR-35', 'description' => 'Tubería para drenajes', 'rate' => 4.25, 'cost_price' => 3.65, 'stock_quantity' => 120, 'minimum_stock' => 40, 'maximum_stock' => 300, 'unit_of_measure' => 'metros', 'location' => 'Almacén C-1', 'supplier' => 'Tuberías Modernas', 'barcode' => 'TUB002', 'status' => 'active'],
            ['title' => 'Codo PVC 4" x 90°', 'description' => 'Codo para tubería de 4 pulgadas', 'rate' => 3.50, 'cost_price' => 2.95, 'stock_quantity' => 45, 'minimum_stock' => 20, 'maximum_stock' => 100, 'unit_of_measure' => 'unidades', 'location' => 'Almacén C-2', 'supplier' => 'Tuberías Modernas', 'barcode' => 'COD001', 'status' => 'active'],
            
            // Madera
            ['title' => 'Tabla de Pino 1"x12"x12\'', 'description' => 'Tabla de pino para formaletas', 'rate' => 18.50, 'cost_price' => 15.75, 'stock_quantity' => 200, 'minimum_stock' => 80, 'maximum_stock' => 500, 'unit_of_measure' => 'unidades', 'location' => 'Almacén D-1', 'supplier' => 'Maderas del Bosque', 'barcode' => 'MAD001', 'status' => 'active'],
            ['title' => 'Regla de Pino 2"x4"x12\'', 'description' => 'Regla de pino para estructura', 'rate' => 12.75, 'cost_price' => 10.85, 'stock_quantity' => 150, 'minimum_stock' => 60, 'maximum_stock' => 400, 'unit_of_measure' => 'unidades', 'location' => 'Almacén D-1', 'supplier' => 'Maderas del Bosque', 'barcode' => 'MAD002', 'status' => 'active'],
            
            // Materiales con stock crítico (para alertas)
            ['title' => 'Cal Hidratada - 25kg', 'description' => 'Cal para mezclas y acabados', 'rate' => 4.50, 'cost_price' => 3.85, 'stock_quantity' => 8, 'minimum_stock' => 25, 'maximum_stock' => 100, 'unit_of_measure' => 'sacos', 'location' => 'Almacén A-2', 'supplier' => 'Cales del Sur', 'barcode' => 'CAL001', 'status' => 'active'],
            ['title' => 'Clavos 3" - 50lb', 'description' => 'Clavos para construcción', 'rate' => 85.00, 'cost_price' => 72.50, 'stock_quantity' => 0, 'minimum_stock' => 10, 'maximum_stock' => 50, 'unit_of_measure' => 'cajas', 'location' => 'Almacén E-1', 'supplier' => 'Ferretería Industrial', 'barcode' => 'CLA001', 'status' => 'active'],
            ['title' => 'Tornillos Autorroscantes 2"', 'description' => 'Tornillos para drywall', 'rate' => 0.15, 'cost_price' => 0.12, 'stock_quantity' => 5, 'minimum_stock' => 50, 'maximum_stock' => 500, 'unit_of_measure' => 'unidades', 'location' => 'Almacén E-2', 'supplier' => 'Ferretería Industrial', 'barcode' => 'TOR001', 'status' => 'active'],
        ];

        foreach ($materials as $material) {
            Product::create($material);
        }

        // Create sample requisitions
        $users = User::all();
        if ($users->count() > 0) {
            $this->createSampleRequisitions($users);
        }

        $this->command->info('Enhanced inventory seeded successfully!');
    }

    private function createSampleRequisitions($users)
    {
        $materials = Product::all();
        
        $requisitions = [
            [
                'requisition_number' => 'REQ-2024-001',
                'project_id' => null,
                'requested_by' => $users->random()->id,
                'status' => 'pending',
                'priority' => 'medium',
                'required_date' => Carbon::now()->addDays(5),
                'purpose' => 'Materiales para inicio de cimentación Proyecto Villa Hermosa',
                'notes' => 'Materiales urgentes para no retrasar cronograma',
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'requisition_number' => 'REQ-2024-002',
                'project_id' => null,
                'requested_by' => $users->random()->id,
                'approved_by' => $users->random()->id,
                'status' => 'approved',
                'priority' => 'high',
                'required_date' => Carbon::now()->addDays(3),
                'purpose' => 'Materiales para estructura de segundo nivel',
                'notes' => 'Aprobado por gerencia - proceder con entrega',
                'approved_at' => Carbon::now()->subDays(1),
                'created_at' => Carbon::now()->subDays(3),
            ],
            [
                'requisition_number' => 'REQ-2024-003',
                'project_id' => null,
                'requested_by' => $users->random()->id,
                'approved_by' => $users->random()->id,
                'status' => 'delivered',
                'priority' => 'medium',
                'required_date' => Carbon::now()->subDays(2),
                'purpose' => 'Materiales para acabados interiores',
                'notes' => 'Entregado completo según especificaciones',
                'approved_at' => Carbon::now()->subDays(4),
                'delivered_at' => Carbon::now()->subDays(2),
                'created_at' => Carbon::now()->subDays(5),
            ],
        ];

        foreach ($requisitions as $reqData) {
            $requisition = MaterialRequisition::create($reqData);
            
            // Add items to requisition
            $itemCount = rand(3, 6);
            $selectedMaterials = $materials->random($itemCount);
            $totalCost = 0;
            
            foreach ($selectedMaterials as $material) {
                $quantity = rand(5, 50);
                $unitCost = $material->cost_price;
                $itemTotal = $quantity * $unitCost;
                $totalCost += $itemTotal;
                
                MaterialRequisitionItem::create([
                    'requisition_id' => $requisition->id,
                    'item_id' => $material->id,
                    'quantity_requested' => $quantity,
                    'quantity_delivered' => $requisition->status === 'delivered' ? $quantity : 0,
                    'unit_cost' => $unitCost,
                    'total_cost' => $itemTotal,
                    'notes' => 'Material requerido según especificaciones técnicas',
                ]);

                // Create inventory movements for delivered items
                if ($requisition->status === 'delivered') {
                    InventoryMovement::create([
                        'item_id' => $material->id,
                        'movement_type' => 'out',
                        'quantity' => $quantity,
                        'reference_type' => 'requisition',
                        'reference_id' => $requisition->id,
                        'notes' => "Salida por requisición {$requisition->requisition_number}",
                        'user_id' => $requisition->approved_by,
                        'created_at' => $requisition->delivered_at,
                    ]);
                }
            }
            
            $requisition->update(['total_cost' => $totalCost]);
        }

        // Create some inventory movements for stock adjustments
        $this->createInventoryMovements($materials, $users);
    }

    private function createInventoryMovements($materials, $users)
    {
        $movements = [
            ['type' => 'in', 'reason' => 'Compra de materiales', 'days_ago' => 10],
            ['type' => 'in', 'reason' => 'Devolución de obra', 'days_ago' => 8],
            ['type' => 'out', 'reason' => 'Ajuste por inventario físico', 'days_ago' => 6],
            ['type' => 'in', 'reason' => 'Compra urgente', 'days_ago' => 4],
            ['type' => 'out', 'reason' => 'Material dañado', 'days_ago' => 2],
        ];

        foreach ($movements as $movement) {
            $material = $materials->random();
            $quantity = rand(10, 100);
            
            InventoryMovement::create([
                'item_id' => $material->id,
                'movement_type' => $movement['type'],
                'quantity' => $quantity,
                'reference_type' => 'adjustment',
                'reference_id' => null,
                'notes' => $movement['reason'],
                'user_id' => $users->random()->id,
                'created_at' => Carbon::now()->subDays($movement['days_ago']),
            ]);
        }
    }
}
