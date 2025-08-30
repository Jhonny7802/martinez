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
        Schema::create('design_projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_name');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('template_id')->nullable();
            $table->text('description')->nullable();
            $table->string('dimensions')->default('1920x1080');
            $table->string('color_scheme')->default('#000000,#FFFFFF');
            $table->date('deadline')->nullable();
            $table->decimal('budget', 10, 2)->default(0);
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['draft', 'in_progress', 'review', 'completed', 'cancelled'])->default('draft');
            $table->string('preview_image')->nullable();
            $table->string('final_design')->nullable();
            $table->timestamp('preview_generated_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('template_id')->references('id')->on('design_templates')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            
            $table->index(['customer_id', 'status']);
            $table->index(['priority', 'deadline']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('design_projects');
    }
};
