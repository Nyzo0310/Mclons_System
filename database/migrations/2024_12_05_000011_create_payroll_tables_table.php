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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id('payroll_id');
            $table->unsignedBigInteger('employee_id');
            $table->decimal('gross_salary', 10, 2);
            $table->unsignedBigInteger('deduction_id')->nullable();  // Allowing null for foreign key
            $table->decimal('net_salary', 10, 2);
            $table->timestamps();
        
            // Foreign key relationship
            $table->foreign('employee_id')
                ->references('employee_id')->on('employees')
                ->onDelete('cascade');  // When an employee is deleted, their payroll is deleted
        
            $table->foreign('deduction_id')
                ->references('deduction_id')->on('deductions')
                ->onDelete('set null');  // If a deduction is deleted, set deduction_id to null in payroll
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');  // Correct the table name here
    }
};
