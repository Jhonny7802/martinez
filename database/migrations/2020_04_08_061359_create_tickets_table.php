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
        Schema::create('tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject');
            $table->unsignedInteger('contact_id')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->unsignedInteger('department_id')->nullable();
            $table->string('cc')->nullable();
            $table->unsignedInteger('assign_to')->nullable();
            $table->unsignedInteger('priority_id');
            $table->unsignedInteger('service_id');
            $table->unsignedInteger('predefined_reply_id')->nullable();
            $table->text('body')->nullable();
            $table->unsignedInteger('ticket_status_id')->nullable();
            $table->timestamps();

            // Foreign key constraint removed

            // Foreign key constraint removed

            // Foreign key constraint removed

            // Foreign key constraint removed

            // Foreign key constraint removed

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
        Schema::dropIfExists('tickets');
    }
};
