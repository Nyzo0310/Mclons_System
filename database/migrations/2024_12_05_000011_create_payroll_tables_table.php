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
            $table->id('payroll_id'); // Primary key for payrolls table
            $table->unique(['employee_id', 'start_date', 'end_date']);
            // Define employee_id as unsignedBigInteger to match the employees table
            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')
                ->references('employee_id') // Match the column in employees table
                ->on('employees')
                ->onDelete('cascade'); // Cascade delete if employee is deleted
        
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                
            $table->decimal('regular_pay', 10, 2);
            $table->decimal('overtime_pay', 10, 2);
            $table->decimal('holiday_pay', 10, 2);
            $table->decimal('extra_2to4_pay', 10, 2);
            $table->decimal('gross_salary', 10, 2);
            $table->decimal('cash_advance', 10, 2);
            $table->decimal('deductions', 10, 2);
            $table->decimal('net_salary', 10, 2);
            $table->timestamps();
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
