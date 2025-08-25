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
        Schema::create('budget_alerts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('budget_control_id');
            $table->unsignedInteger('project_id')->nullable(); // Cambiado de bigint a int
            $table->string('title');
            $table->text('description');
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('status', ['active', 'acknowledged', 'resolved', 'dismissed'])->default('active');
            $table->decimal('threshold_percent', 8, 2)->nullable();
            $table->decimal('current_percent', 8, 2)->nullable();
            $table->decimal('budget_amount', 15, 2)->nullable();
            $table->decimal('spent_amount', 15, 2)->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('acknowledged_by')->nullable();
            $table->timestamp('acknowledged_at')->nullable();
            $table->unsignedBigInteger('resolved_by')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->timestamps();

            // Índices
            $table->index('budget_control_id');
            $table->index('project_id');
            $table->index('created_by');
            $table->index('acknowledged_by');
            $table->index('resolved_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('budget_alerts');
    }
};