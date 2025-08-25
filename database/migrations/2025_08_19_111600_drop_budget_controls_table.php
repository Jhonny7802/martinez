<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropBudgetControlsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::dropIfExists('budget_controls');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // This is a destructive operation, so the down method is left empty
        // as we can't reliably recreate the table with the same structure
    }
}
