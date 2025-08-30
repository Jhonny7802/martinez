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
        // Tabla principal de facturación CAI
        Schema::create('cai_billings', function (Blueprint $table) {
            $table->id();
            $table->string('cai_number')->unique();
            $table->string('invoice_number')->unique();
            $table->unsignedBigInteger('customer_id');
            $table->string('customer_name');
            $table->string('customer_rtn')->nullable();
            $table->text('customer_address')->nullable();
            $table->decimal('subtotal', 15, 2);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2);
            $table->date('issue_date');
            $table->date('due_date')->nullable();
            $table->enum('status', ['draft', 'issued', 'paid', 'cancelled'])->default('draft');
            $table->text('notes')->nullable();
            $table->string('payment_method')->nullable();
            $table->date('payment_date')->nullable();
            $table->json('items')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->index(['cai_number', 'status']);
            $table->index(['customer_id', 'issue_date']);
            $table->index(['status', 'issue_date']);
        });

        // Tabla de items de facturación CAI
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
        Schema::dropIfExists('cai_billings');
    }
};
