<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop the table if it already exists to avoid conflicts
        if (Schema::hasTable('schedules')) {
            Schema::dropIfExists('schedules');
        }

        // Create the 'schedules' table
        Schema::create('schedules', function (Blueprint $table) {
            $table->id('schedule_id'); // Primary Key
            $table->string('description'); // Description field (lowercase for convention)
            $table->time('check_in_time'); // Check-in time (snake_case)
            $table->time('check_out_time'); // Check-out time (snake_case)
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules'); // Drop the table if the migration is rolled back
    }
};
