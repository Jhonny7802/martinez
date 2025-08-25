<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\MaterialRequisition;
use App\Models\MaterialRequisitionItem;
use App\Models\InventoryMovement;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class InventorySeeder extends Seeder
{
    public function run()
    {
        // Update existing products with inventory data
        $products = Product::all();
        
        $inventoryData = [
            'Cemento Portland' => [
                'stock_quantity' => 150,
                'minimum_stock' => 20,
                'maximum_stock' => 300,
                'unit_of_measure' => 'sacos',
                'location' => 'Bodega A-1',
                'cost_price' => 280.00,
                'supplier' => 'Cementos del Norte',
                'barcode' => 'CEM001',
                'status' => 'active'
            ],
            'Varilla de Acero #4' => [
                'stock_quantity' => 8,
                'minimum_stock' => 15,
                'maximum_stock' => 100,
                'unit_of_measure' => 'varillas',
                'location' => 'Patio de Acero',
                'cost_price' => 45.50,
                'supplier' => 'Aceros Industriales',
                'barcode' => 'VAR004',
                'status' => 'active'
            ],
            'Arena Fina' => [
                'stock_quantity' => 25,
                'minimum_stock' => 10,
                'maximum_stock' => 50,
                'unit_of_measure' => 'm³',
                'location' => 'Patio Principal',
                'cost_price' => 450.00,
                'supplier' => 'Agregados La Ceiba',
                'barcode' => 'ARE001',
                'status' => 'active'
            ],
            'Grava' => [
                'stock_quantity' => 30,
                'minimum_stock' => 15,
                'maximum_stock' => 60,
                'unit_of_measure' => 'm³',
                'location' => 'Patio Principal',
                'cost_price' => 520.00,
                'supplier' => 'Agregados La Ceiba',
                'barcode' => 'GRA001',
                'status' => 'active'
            ],
            'Block de 15cm' => [
                'stock_quantity' => 500,
                'minimum_stock' => 100,
                'maximum_stock' => 1000,
                'unit_of_measure' => 'unidades',
                'location' => 'Bodega B-2',
                'cost_price' => 12.50,
                'supplier' => 'Bloques San Pedro',
                'barcode' => 'BLO015',
                'status' => 'active'
            ],
            'Tubería PVC 4"' => [
                'stock_quantity' => 35,
                'minimum_stock' => 20,
                'maximum_stock' => 80,
                'unit_of_measure' => 'tubos',
                'location' => 'Bodega C-1',
                'cost_price' => 85.00,
                'supplier' => 'Tuberías del Caribe',
                'barcode' => 'TUB004',
                'status' => 'active'
            ],
            'Pintura Latex Blanca' => [
                'stock_quantity' => 12,
                'minimum_stock' => 8,
                'maximum_stock' => 40,
                'unit_of_measure' => 'galones',
                'location' => 'Bodega D-1',
                'cost_price' => 320.00,
                'supplier' => 'Pinturas Centroamericanas',
                'barcode' => 'PIN001',
                'status' => 'active'
            ],
            'Alambre de Amarre' => [
                'stock_quantity' => 25,
                'minimum_stock' => 10,
                'maximum_stock' => 50,
                'unit_of_measure' => 'rollos',
                'location' => 'Bodega A-2',
                'cost_price' => 95.00,
                'supplier' => 'Ferretería Central',
                'barcode' => 'ALA001',
                'status' => 'active'
            ],
            'Clavos 3"' => [
                'stock_quantity' => 2,
                'minimum_stock' => 5,
                'maximum_stock' => 20,
                'unit_of_measure' => 'libras',
                'location' => 'Bodega A-2',
                'cost_price' => 28.00,
                'supplier' => 'Ferretería Central',
                'barcode' => 'CLA003',
                'status' => 'active'
            ],
            'Madera de Pino 2x4' => [
                'stock_quantity' => 0,
                'minimum_stock' => 20,
                'maximum_stock' => 100,
                'unit_of_measure' => 'piezas',
                'location' => 'Patio de Madera',
                'cost_price' => 125.00,
                'supplier' => 'Maderas del Bosque',
                'barcode' => 'MAD204',
                'status' => 'active'
            ]
        ];

        foreach ($products as $product) {
            if (isset($inventoryData[$product->title])) {
                $product->update($inventoryData[$product->title]);
            } else {
                // Default values for other products
                $product->update([
                    'stock_quantity' => rand(10, 100),
                    'minimum_stock' => rand(5, 15),
                    'maximum_stock' => rand(50, 200),
                    'unit_of_measure' => 'unidad',
                    'location' => 'Bodega General',
                    'cost_price' => $product->rate * 0.7, // 70% of sale price
                    'supplier' => 'Proveedor General',
                    'status' => 'active'
                ]);
            }
        }

        // Create sample material requisitions
        $projects = Project::limit(3)->get();
        $users = User::limit(2)->get();

        if ($projects->count() > 0 && $users->count() > 0) {
            // Requisition 1 - Pending
            $requisition1 = MaterialRequisition::create([
                'requisition_number' => 'REQ-202401-0001',
                'project_id' => $projects->first()->id,
                'requested_by' => $users->first()->id,
                'status' => 'pending',
                'priority' => 'high',
                'required_date' => now()->addDays(3),
                'purpose' => 'Construcción de cimientos - Fase 1',
                'notes' => 'Materiales urgentes para inicio de obra'
            ]);

            // Add items to requisition 1
            $cement = Product::where('title', 'Cemento Portland')->first();
            $steel = Product::where('title', 'Varilla de Acero #4')->first();
            $sand = Product::where('title', 'Arena Fina')->first();

            if ($cement) {
                MaterialRequisitionItem::create([
                    'requisition_id' => $requisition1->id,
                    'item_id' => $cement->id,
                    'quantity_requested' => 50,
                    'unit_cost' => $cement->cost_price,
                    'specifications' => 'Cemento Portland tipo I'
                ]);
            }

            if ($steel) {
                MaterialRequisitionItem::create([
                    'requisition_id' => $requisition1->id,
                    'item_id' => $steel->id,
                    'quantity_requested' => 30,
                    'unit_cost' => $steel->cost_price,
                    'specifications' => 'Varilla corrugada grado 40'
                ]);
            }

            if ($sand) {
                MaterialRequisitionItem::create([
                    'requisition_id' => $requisition1->id,
                    'item_id' => $sand->id,
                    'quantity_requested' => 10,
                    'unit_cost' => $sand->cost_price,
                    'specifications' => 'Arena lavada para concreto'
                ]);
            }

            // Requisition 2 - Approved
            $requisition2 = MaterialRequisition::create([
                'requisition_number' => 'REQ-202401-0002',
                'project_id' => $projects->count() > 1 ? $projects->skip(1)->first()->id : $projects->first()->id,
                'requested_by' => $users->first()->id,
                'approved_by' => $users->count() > 1 ? $users->skip(1)->first()->id : $users->first()->id,
                'status' => 'approved',
                'priority' => 'medium',
                'required_date' => now()->addDays(7),
                'purpose' => 'Levantado de paredes - Bloque',
                'notes' => 'Materiales para segunda fase',
                'approved_at' => now()->subDays(1),
                'total_cost' => 8750.00
            ]);

            // Add items to requisition 2
            $blocks = Product::where('title', 'Block de 15cm')->first();
            $wire = Product::where('title', 'Alambre de Amarre')->first();

            if ($blocks) {
                MaterialRequisitionItem::create([
                    'requisition_id' => $requisition2->id,
                    'item_id' => $blocks->id,
                    'quantity_requested' => 600,
                    'quantity_approved' => 600,
                    'unit_cost' => $blocks->cost_price,
                    'total_cost' => 600 * $blocks->cost_price,
                    'specifications' => 'Blocks de 15x20x40 cm'
                ]);
            }

            if ($wire) {
                MaterialRequisitionItem::create([
                    'requisition_id' => $requisition2->id,
                    'item_id' => $wire->id,
                    'quantity_requested' => 10,
                    'quantity_approved' => 8,
                    'unit_cost' => $wire->cost_price,
                    'total_cost' => 8 * $wire->cost_price,
                    'specifications' => 'Alambre galvanizado calibre 16'
                ]);
            }

            // Requisition 3 - Delivered
            $requisition3 = MaterialRequisition::create([
                'requisition_number' => 'REQ-202401-0003',
                'project_id' => $projects->count() > 2 ? $projects->skip(2)->first()->id : $projects->first()->id,
                'requested_by' => $users->first()->id,
                'approved_by' => $users->count() > 1 ? $users->skip(1)->first()->id : $users->first()->id,
                'status' => 'delivered',
                'priority' => 'low',
                'required_date' => now()->subDays(5),
                'purpose' => 'Acabados - Pintura',
                'notes' => 'Materiales para acabados finales',
                'approved_at' => now()->subDays(3),
                'delivered_at' => now()->subDays(1),
                'total_cost' => 1280.00
            ]);

            // Add items to requisition 3
            $paint = Product::where('title', 'Pintura Latex Blanca')->first();

            if ($paint) {
                MaterialRequisitionItem::create([
                    'requisition_id' => $requisition3->id,
                    'item_id' => $paint->id,
                    'quantity_requested' => 4,
                    'quantity_approved' => 4,
                    'quantity_delivered' => 4,
                    'unit_cost' => $paint->cost_price,
                    'total_cost' => 4 * $paint->cost_price,
                    'specifications' => 'Pintura latex interior mate'
                ]);

                // Record inventory movement for delivered paint
                InventoryMovement::create([
                    'item_id' => $paint->id,
                    'movement_type' => 'out',
                    'quantity' => 4,
                    'previous_stock' => 16,
                    'new_stock' => 12,
                    'unit_cost' => $paint->cost_price,
                    'reference_type' => 'requisition',
                    'reference_id' => $requisition3->id,
                    'user_id' => $users->first()->id,
                    'notes' => 'Entrega para requisición REQ-202401-0003',
                    'created_at' => now()->subDays(1),
                    'updated_at' => now()->subDays(1)
                ]);
            }

            // Create some additional inventory movements for history
            $this->createSampleMovements($users->first()->id);
        }

        $this->command->info('Inventory seeder completed successfully!');
    }

    private function createSampleMovements($userId)
    {
        $cement = Product::where('title', 'Cemento Portland')->first();
        $steel = Product::where('title', 'Varilla de Acero #4')->first();

        if ($cement) {
            // Cement purchase
            InventoryMovement::create([
                'item_id' => $cement->id,
                'movement_type' => 'in',
                'quantity' => 100,
                'previous_stock' => 50,
                'new_stock' => 150,
                'unit_cost' => $cement->cost_price,
                'reference_type' => 'purchase',
                'reference_id' => null,
                'user_id' => $userId,
                'notes' => 'Compra de cemento - Orden #PO-2024-001',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5)
            ]);
        }

        if ($steel) {
            // Steel adjustment (damaged goods)
            InventoryMovement::create([
                'item_id' => $steel->id,
                'movement_type' => 'adjustment',
                'quantity' => 8,
                'previous_stock' => 15,
                'new_stock' => 8,
                'unit_cost' => $steel->cost_price,
                'reference_type' => 'adjustment',
                'reference_id' => null,
                'user_id' => $userId,
                'notes' => 'Ajuste por varillas dañadas por humedad',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2)
            ]);
        }
    }
}
