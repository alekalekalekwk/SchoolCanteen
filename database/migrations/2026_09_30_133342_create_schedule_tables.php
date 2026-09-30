<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('time_slots', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['senin', 'reguler', 'override']);
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('schedule_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('override_active')->default(false);
            $table->timestamps();
        });

        // Insert Default Seeds
        DB::table('time_slots')->insert([
            // Jadwal Senin
            ['type' => 'senin', 'start_time' => '09:40:00', 'end_time' => '10:00:00', 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'senin', 'start_time' => '11:40:00', 'end_time' => '12:30:00', 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            
            // Jadwal Reguler (Selasa-Jumat)
            ['type' => 'reguler', 'start_time' => '10:10:00', 'end_time' => '10:30:00', 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'reguler', 'start_time' => '11:40:00', 'end_time' => '12:30:00', 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('schedule_settings')->insert([
            ['override_active' => false, 'created_at' => now(), 'updated_at' => now()]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_settings');
        Schema::dropIfExists('time_slots');
    }
};
