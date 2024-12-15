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
        Schema::create('employees', function (Blueprint $table) {
            $table->id('employee_id');
            $table->string('first_name', 255);
            $table->string('last_name', 255);
            $table->string('address', 255);
            $table->date('birthdate');
            $table->string('contact_no', 255);
            $table->enum('gender', ['male', 'female', 'other']);
            $table->unsignedBigInteger('position_id')->nullable();
            $table->unsignedBigInteger('schedule_id')->nullable(); // Add schedule_id column
            $table->string('photo')->nullable();
            $table->string('statutory_benefits', 255);
            $table->timestamps();

            // Foreign key relationships
            $table->foreign('position_id')
                ->references('position_id')->on('positions')
                ->onDelete('set null');

            $table->foreign('schedule_id')
                ->references('schedule_id')->on('schedules')
                ->onDelete('set null'); // Set to NULL if the schedule is deleted
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees'); // Drop the employees table
    }
};
