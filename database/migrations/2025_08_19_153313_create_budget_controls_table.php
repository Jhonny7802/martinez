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
        Schema::create('budget_controls', function (Blueprint $table) {
            $table->bigIncrements('id');
            // Usando unsignedInteger porque la tabla projects usa increments() que crea int(10) unsigned
            $table->unsignedInteger('project_id')->nullable();
            $table->string('name');
            $table->decimal('initial_budget', 15, 2)->default(0.00);
            $table->decimal('current_budget', 15, 2)->default(0.00);
            $table->decimal('spent_amount', 15, 2)->default(0.00);
            $table->decimal('remaining_amount', 15, 2)->default(0.00);
            $table->decimal('budget_percent', 8, 2)->default(0.00);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['active', 'completed', 'on_hold', 'cancelled'])->default('active');
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Agregar índice
            $table->index('project_id');
            
            // Agregar llave foránea
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('budget_controls');
    }
};