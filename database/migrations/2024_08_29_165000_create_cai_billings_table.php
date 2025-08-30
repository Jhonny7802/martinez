<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cai_billings', function (Blueprint $table) {
            $table->id();
            $table->string('cai_number')->unique();
            $table->string('invoice_number')->unique();
            
            // Reference to customers table using company_name
            $table->string('company_name');
            
            // Customer information
            $table->string('customer_rtn')->nullable();
            $table->text('customer_address')->nullable();
            
            // Billing details
            $table->json('items');
            $table->decimal('subtotal', 15, 2);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2);
            
            // Dates
            $table->date('issue_date');
            $table->date('due_date')->nullable();
            
            // Status and payment
            $table->enum('status', ['draft', 'issued', 'paid', 'cancelled'])->default('draft');
            $table->text('notes')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->text('terms')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Index for better performance
            $table->index('company_name');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cai_billings');
    }
};