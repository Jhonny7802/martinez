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
        Schema::create('design_elements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('design_project_id');
            $table->enum('element_type', ['text', 'image', 'shape', 'logo', 'icon']);
            $table->text('content'); // Text content, image path, or shape definition
            $table->decimal('position_x', 8, 2)->default(0);
            $table->decimal('position_y', 8, 2)->default(0);
            $table->decimal('width', 8, 2);
            $table->decimal('height', 8, 2);
            $table->integer('layer_order')->default(1);
            $table->json('style_properties')->nullable(); // Colors, fonts, borders, etc.
            $table->boolean('is_locked')->default(false);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();

            $table->foreign('design_project_id')->references('id')->on('design_projects')->onDelete('cascade');
            $table->index(['design_project_id', 'layer_order']);
            $table->index(['element_type', 'is_visible']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('design_elements');
    }
};
