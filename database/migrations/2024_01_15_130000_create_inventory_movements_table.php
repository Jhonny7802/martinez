<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->enum('movement_type', ['in', 'out', 'adjustment', 'transfer']);
            $table->integer('quantity');
            $table->integer('previous_stock');
            $table->integer('new_stock');
            $table->decimal('unit_cost', 15, 2)->nullable();
            $table->string('reference_type')->nullable(); // 'requisition', 'purchase', 'adjustment', etc.
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Foreign keys removed to avoid constraint issues
            // // Foreign key removed: // Foreign key constraint removed
            // // Foreign key removed: // Foreign key constraint removed
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_movements');
    }
};
