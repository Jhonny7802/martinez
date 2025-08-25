<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Ensure clean state if table exists from a previous failed run
        Schema::dropIfExists('material_requisitions');

        Schema::create('material_requisitions', function (Blueprint $table) {
            $table->id();
            $table->string('requisition_number')->unique();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedInteger('requested_by');
            $table->unsignedInteger('approved_by')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'delivered', 'cancelled'])->default('pending');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->date('required_date');
            $table->text('purpose')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('total_cost', 15, 2)->default(0);
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();

            $table->index('project_id');
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('material_requisitions');
    }
};
