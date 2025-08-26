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
        Schema::create('internal_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id');
            $table->json('recipients'); // Array of user IDs
            $table->string('subject');
            $table->text('message');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->json('attachments')->nullable(); // Array of file paths
            $table->json('read_by')->nullable(); // Array of user IDs who have read the message
            $table->boolean('is_broadcast')->default(false);
            $table->unsignedBigInteger('parent_message_id')->nullable(); // For replies and forwards
            $table->enum('message_type', ['normal', 'reply', 'forward', 'broadcast'])->default('normal');
            $table->timestamps();
            $table->softDeletes();

            // Foreign key constraints removed to avoid constraint issues
            // // Foreign key removed: // Foreign key constraint removed
            // // Foreign key removed: // Foreign key constraint removed

            // Indexes for better performance
            $table->index('sender_id');
            $table->index('priority');
            $table->index('is_broadcast');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('internal_messages');
    }
};
