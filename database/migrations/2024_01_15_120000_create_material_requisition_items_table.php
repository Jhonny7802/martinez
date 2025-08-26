<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Ensure clean state if table exists from a previous failed run
        Schema::dropIfExists('material_requisition_items');

        Schema::create('material_requisition_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requisition_id');
            $table->unsignedInteger('item_id');
            $table->integer('quantity_requested');
            $table->integer('quantity_approved')->nullable();
            $table->integer('quantity_delivered')->default(0);
            $table->decimal('unit_cost', 15, 2)->nullable();
            $table->decimal('total_cost', 15, 2)->nullable();
            $table->text('specifications')->nullable();
            $table->timestamps();

            // Foreign keys removed to avoid constraint issues
            // // Foreign key removed: // Foreign key constraint removed
            // // Foreign key removed: // Foreign key constraint removed
        });
    }

    public function down()
    {
        Schema::dropIfExists('material_requisition_items');
    }
};
