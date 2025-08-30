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
        // Tabla de plantillas de diseño
        Schema::dropIfExists('design_templates');
        Schema::create('design_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category')->default('general');
            $table->string('dimensions')->default('1920x1080');
            $table->json('default_elements')->nullable();
            $table->string('preview_image')->nullable();
            $table->json('style_properties')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index(['category', 'is_active']);
        });

        // Tabla de proyectos de diseño
        Schema::dropIfExists('design_projects');
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

            $table->index(['customer_id', 'status']);
            $table->index(['priority', 'deadline']);
            $table->index(['status', 'created_at']);
        });

        // Tabla de elementos de diseño
        Schema::dropIfExists('design_elements');
        Schema::create('design_elements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('design_project_id');
            $table->enum('element_type', ['text', 'image', 'shape', 'logo', 'icon']);
            $table->text('content');
            $table->decimal('position_x', 8, 2)->default(0);
            $table->decimal('position_y', 8, 2)->default(0);
            $table->decimal('width', 8, 2);
            $table->decimal('height', 8, 2);
            $table->integer('layer_order')->default(1);
            $table->json('style_properties')->nullable();
            $table->boolean('is_locked')->default(false);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();

            $table->index(['design_project_id', 'layer_order']);
            $table->index(['element_type', 'is_visible']);
        });

        // Agregar foreign keys después de crear todas las tablas
        Schema::table('design_projects', function (Blueprint $table) {
            $table->foreign('template_id')->references('id')->on('design_templates')->onDelete('set null');
        });

        Schema::table('design_elements', function (Blueprint $table) {
            $table->foreign('design_project_id')->references('id')->on('design_projects')->onDelete('cascade');
        });

        // Insertar plantillas de ejemplo
        DB::table('design_templates')->insert([
            [
                'name' => 'Plantilla Básica',
                'description' => 'Plantilla básica para diseños generales',
                'category' => 'general',
                'dimensions' => '1920x1080',
                'default_elements' => '[]',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Flyer Promocional',
                'description' => 'Plantilla para flyers promocionales',
                'category' => 'marketing',
                'dimensions' => '210x297',
                'default_elements' => '[]',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Banner Web',
                'description' => 'Plantilla para banners web',
                'category' => 'web',
                'dimensions' => '1200x300',
                'default_elements' => '[]',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Tarjeta de Presentación',
                'description' => 'Plantilla para tarjetas de presentación',
                'category' => 'business',
                'dimensions' => '90x50',
                'default_elements' => '[]',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Poster Evento',
                'description' => 'Plantilla para posters de eventos',
                'category' => 'events',
                'dimensions' => '420x594',
                'default_elements' => '[]',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Logo Corporativo',
                'description' => 'Plantilla para diseño de logos',
                'category' => 'branding',
                'dimensions' => '500x500',
                'default_elements' => '[]',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Brochure Tríptico',
                'description' => 'Plantilla para brochures de 3 paneles',
                'category' => 'marketing',
                'dimensions' => '297x210',
                'default_elements' => '[]',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Certificado',
                'description' => 'Plantilla para certificados y diplomas',
                'category' => 'documents',
                'dimensions' => '297x210',
                'default_elements' => '[]',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('design_elements');
        Schema::dropIfExists('design_projects');
        Schema::dropIfExists('design_templates');
    }
};
