<?php

namespace App\Console\Commands;

use App\Http\Controllers\Display;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\PayrollController; // Replace with your actual controller name

class CalculatePayrollCommand extends Command
{
    protected $signature = 'payroll:calculate';
    protected $description = 'Calculate and save payrolls every 15 days';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {
            // Ensure the controller is properly initialized
            $controller = new Display(); // Replace with the actual controller name
            
            // Call the Display8 method to calculate payroll
            $controller->Display8();

            $this->info('Payrolls calculated and saved successfully.');
        } catch (\Exception $e) {
            // Log the error and display a message
            Log::error('Error calculating payroll: ' . $e->getMessage());
            $this->error('An error occurred while calculating payroll. Check the logs for details.');
        }
    }
}
