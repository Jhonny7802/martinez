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
        Schema::create('user_departments', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('department_id');

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
        Schema::table('user_departments', function (Blueprint $table) {
            // Dropped foreign key: // Foreign key constraint removed
            // Dropped foreign key: // Foreign key constraint removed
        });

        Schema::dropIfExists('user_departments');
    }
};
