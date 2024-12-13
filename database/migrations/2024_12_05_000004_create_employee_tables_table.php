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
            $table->string('photo')->nullable();  // This will store the photo's file path
            $table->enum('statutory_benefits', ['SSS', 'Pag-Ibig', 'PhilHealth', 'SSS,Pag-Ibig', 'SSS,PhilHealth', 'SSS,Pag-Ibig,PhilHealth', 'Pag-Ibig,PhilHealth']);
            $table->timestamps();
        
            // Foreign key relationship
            $table->foreign('position_id')
                ->references('position_id')->on('positions')
                ->onDelete('set null');  // If position is deleted, set position_id to null
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees'); // Corrected table name
    }
};
