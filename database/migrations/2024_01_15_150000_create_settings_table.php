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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, integer, boolean, json
            $table->string('group')->default('general');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['key', 'group']);
        });

        // Insert default settings
        DB::table('settings')->insert([
            [
                'key' => 'company_name',
                'value' => 'Martinez Construction',
                'type' => 'string',
                'group' => 'company',
                'description' => 'Company name',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'company_email',
                'value' => 'info@martinez-construction.com',
                'type' => 'string',
                'group' => 'company',
                'description' => 'Company email address',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'company_phone',
                'value' => '+1-555-0123',
                'type' => 'string',
                'group' => 'company',
                'description' => 'Company phone number',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'company_address',
                'value' => '123 Construction Ave, City, State 12345',
                'type' => 'string',
                'group' => 'company',
                'description' => 'Company address',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'currency',
                'value' => 'USD',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Default currency',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'timezone',
                'value' => 'America/New_York',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Default timezone',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
