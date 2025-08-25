<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->integer('stock_quantity')->default(0)->after('rate');
            $table->integer('minimum_stock')->default(10)->after('stock_quantity');
            $table->integer('maximum_stock')->nullable()->after('minimum_stock');
            $table->string('unit_of_measure')->default('unidad')->after('maximum_stock');
            $table->string('location')->nullable()->after('unit_of_measure');
            $table->decimal('cost_price', 15, 2)->nullable()->after('location');
            $table->string('supplier')->nullable()->after('cost_price');
            $table->string('barcode')->nullable()->after('supplier');
            $table->enum('status', ['active', 'inactive', 'discontinued'])->default('active')->after('barcode');
            $table->text('notes')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn([
                'stock_quantity',
                'minimum_stock', 
                'maximum_stock',
                'unit_of_measure',
                'location',
                'cost_price',
                'supplier',
                'barcode',
                'status',
                'notes'
            ]);
        });
    }
};
