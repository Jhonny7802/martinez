<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Usar DB::statement para evitar problemas de compatibilidad con Doctrine DBAL
        DB::statement('ALTER TABLE activity_log MODIFY properties TEXT');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Rollback usando DB::statement
        DB::statement('ALTER TABLE activity_log MODIFY properties JSON');
    }
};
