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
        Schema::create('sales_item_taxes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sales_item_id');
            $table->unsignedInteger('tax_id');
            $table->timestamps();

            // Foreign key constraint removed

            // Foreign key constraint removed
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_item_taxes');
    }
};
