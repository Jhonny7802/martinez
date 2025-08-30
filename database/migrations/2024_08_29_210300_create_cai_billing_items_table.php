<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cai_billing_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cai_billing_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('description');
            $table->decimal('quantity', 10, 3);
            $table->decimal('unit_price', 15, 2);
            $table->decimal('tax_rate', 5, 2)->default(15.00);
            $table->decimal('subtotal', 15, 2);
            $table->decimal('tax_amount', 15, 2);
            $table->decimal('total', 15, 2);
            $table->timestamps();

            $table->foreign('cai_billing_id')->references('id')->on('cai_billings')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('items')->onDelete('set null');
            $table->index(['cai_billing_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cai_billing_items');
    }
};
