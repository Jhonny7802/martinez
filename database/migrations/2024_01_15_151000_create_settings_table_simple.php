<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insert basic settings
        DB::table('settings')->insert([
            ['key' => 'company_name', 'value' => 'Martinez Construction', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'company_email', 'value' => 'info@martinez.com', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'currency', 'value' => 'USD', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
