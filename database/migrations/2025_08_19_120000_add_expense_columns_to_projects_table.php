<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExpenseColumnsToProjectsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->decimal('last_expense_amount', 15, 2)->nullable();
            $table->date('last_expense_date')->nullable();
            $table->text('last_expense_description')->nullable();
            $table->decimal('total_expenses', 15, 2)->default(0.00);
            $table->string('last_receipt_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'last_expense_amount',
                'last_expense_date', 
                'last_expense_description',
                'total_expenses',
                'last_receipt_path'
            ]);
        });
    }
}
