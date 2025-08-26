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
        Schema::create('invoice_payment_modes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('payment_mode_id');
            $table->unsignedInteger('invoice_id');
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
        Schema::dropIfExists('invoice_payment_modes');
    }
};
