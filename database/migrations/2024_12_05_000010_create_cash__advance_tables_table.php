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
        Schema::create('cash_advances', function (Blueprint $table) {
            $table->id('cash_advance_id');
            $table->unsignedBigInteger('employee_id');
            $table->date('request_date');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['approved', 'pending', 'rejected']);
            $table->timestamps();
        
            // Foreign key relationship
            $table->foreign('employee_id')
                ->references('employee_id')->on('employees')
                ->onDelete('cascade');  // If an employee is deleted, their cash advances will be deleted
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash__advance_tables');
    }
};
