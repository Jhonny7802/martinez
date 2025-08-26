<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject')->unique();
            $table->text('description')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->double('contract_value')->nullable();
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('contract_type_id');
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('contracts');
    }
};
