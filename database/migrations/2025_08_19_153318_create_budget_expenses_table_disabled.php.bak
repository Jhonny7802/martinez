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
        // Paso 1: Crear tabla sin restricciones foráneas
        Schema::create('budget_expenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('budget_control_id');
            $table->unsignedInteger('project_id')->nullable();
            $table->string('expense_name');
            $table->decimal('amount', 15, 2);
            $table->date('expense_date');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->unsignedInteger('category_id')->nullable();
            $table->string('receipt_number')->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('attachment')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            // Solo crear índices
            $table->index('budget_control_id');
            $table->index('project_id');
            $table->index('category_id');
            $table->index('created_by');
            $table->index('approved_by');
        });

        // Paso 2: Agregar restricciones foráneas una por una
        try {
            Schema::table('budget_expenses', function (Blueprint $table) {
                $table->foreign('budget_control_id')->references('id')->on('budget_controls')->onDelete('cascade');
            });
        } catch (\Exception $e) {
            // Si falla, continúa con las demás
        }

        try {
            Schema::table('budget_expenses', function (Blueprint $table) {
                $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            });
        } catch (\Exception $e) {
            // Si falla, continúa con las demás
        }

        try {
            Schema::table('budget_expenses', function (Blueprint $table) {
                $table->foreign('category_id')->references('id')->on('expense_categories')->onDelete('set null');
            });
        } catch (\Exception $e) {
            // Si falla, continúa con las demás
        }

        try {
            Schema::table('budget_expenses', function (Blueprint $table) {
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            });
        } catch (\Exception $e) {
            // Si falla, continúa con las demás
        }

        try {
            Schema::table('budget_expenses', function (Blueprint $table) {
                $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            });
        } catch (\Exception $e) {
            // Si falla, continúa con las demás
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('budget_expenses');
    }
};