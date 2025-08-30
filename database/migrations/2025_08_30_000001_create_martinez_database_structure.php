<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Core system tables
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('status', 20)->default('active');
            $table->string('password');
            $table->string('remember_token', 100)->nullable();
            $table->boolean('is_admin')->default(false);
            $table->timestamps();
        });

        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 3)->nullable();
            $table->string('phone_code', 10)->nullable();
            $table->timestamps();
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('website')->nullable();
            $table->timestamps();
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_name');
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('cascade');
            $table->boolean('calculate_progress_through_tasks')->default(false);
            $table->string('progress')->nullable();
            $table->integer('billing_type')->default(0);
            $table->integer('status')->default(0);
            $table->string('estimated_hours')->nullable();
            $table->date('start_date');
            $table->date('deadline')->nullable();
            $table->text('description')->nullable();
            $table->boolean('send_email')->default(false);
            $table->timestamps();
        });

        Schema::create('item_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('unit')->nullable();
            $table->decimal('price', 15, 2)->default(0.00);
            $table->decimal('cost_price', 15, 2)->default(0.00);
            $table->foreignId('item_group_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('stock_quantity')->default(0);
            $table->integer('minimum_stock')->default(0);
            $table->string('status', 20)->default('active');
            $table->timestamps();
        });

        Schema::create('cai_billings', function (Blueprint $table) {
            $table->id();
            $table->string('cai_number')->unique();
            $table->string('invoice_number')->unique();
            $table->unsignedBigInteger('customer_id');
            $table->string('customer_name');
            $table->string('customer_rtn')->nullable();
            $table->text('customer_address')->nullable();
            $table->decimal('subtotal', 15, 2);
            $table->decimal('tax_amount', 15, 2)->default(0.00);
            $table->decimal('total_amount', 15, 2);
            $table->date('issue_date');
            $table->enum('status', ['draft', 'issued', 'paid', 'cancelled'])->default('draft');
            $table->timestamps();
            
            $table->index(['cai_number', 'status']);
            $table->index(['customer_id', 'issue_date']);
        });

        Schema::create('design_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category')->default('general');
            $table->string('dimensions')->default('1920x1080');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('design_projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_name');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('template_id')->nullable()->constrained('design_templates')->onDelete('set null');
            $table->enum('status', ['draft', 'in_progress', 'review', 'completed', 'cancelled'])->default('draft');
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type', 50)->default('string');
            $table->string('group', 100)->default('general');
            $table->timestamps();
        });

        // Insert initial data
        DB::table('users')->insert([
            'name' => 'Administrator',
            'email' => 'admin@martinez-construction.com',
            'status' => 'active',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'is_admin' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('countries')->insert([
            ['name' => 'Honduras', 'code' => 'HN', 'phone_code' => '+504', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'United States', 'code' => 'US', 'phone_code' => '+1', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('departments')->insert([
            ['name' => 'Construcción', 'description' => 'Departamento de construcción y obras', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Administración', 'description' => 'Departamento administrativo', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Diseño', 'description' => 'Departamento de diseño gráfico', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('item_groups')->insert([
            ['name' => 'Cemento y Concreto', 'description' => 'Materiales de cemento y concreto', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Acero y Hierro', 'description' => 'Materiales de acero y hierro', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Herramientas', 'description' => 'Herramientas de construcción', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('design_templates')->insert([
            ['name' => 'Plantilla Básica', 'description' => 'Plantilla básica para diseños generales', 'category' => 'general', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Logo Corporativo', 'description' => 'Plantilla para diseño de logos', 'category' => 'branding', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('settings')->insert([
            ['key' => 'company_name', 'value' => 'Martinez Construction', 'type' => 'string', 'group' => 'company', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'company_email', 'value' => 'info@martinez-construction.com', 'type' => 'string', 'group' => 'company', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'currency', 'value' => 'HNL', 'type' => 'string', 'group' => 'general', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('design_projects');
        Schema::dropIfExists('design_templates');
        Schema::dropIfExists('cai_billings');
        Schema::dropIfExists('items');
        Schema::dropIfExists('item_groups');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('countries');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('users');
    }
};
