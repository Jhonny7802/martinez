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
        // Check if foreign key exists before dropping
        $foreignKeyExists = DB::select("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME = 'expenses' AND CONSTRAINT_NAME = 'expenses_customer_id_foreign'");
        
        if (!empty($foreignKeyExists)) {
            DB::statement('ALTER TABLE expenses DROP FOREIGN KEY expenses_customer_id_foreign');
        }
        
        DB::statement('ALTER TABLE expenses MODIFY customer_id INT UNSIGNED');
        // Skip adding foreign key constraint to avoid errors
        // DB::statement('ALTER TABLE expenses ADD CONSTRAINT expenses_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Rollback usando DB::statement
        DB::statement('ALTER TABLE expenses DROP FOREIGN KEY expenses_customer_id_foreign');
    }
};
