<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Overtime;
use App\Models\Holiday;
use App\Models\CashAdvance;
use App\Models\Position;
use App\Models\Payroll;
use Illuminate\Support\Facades\Auth;
use App\Models\Deduction;
use App\Models\Schedule;

class Display extends Controller
{
    public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/'); // Redirect to the home page after logout
}
    
    public function Display11()
    {
        return view('login');
    }

    public function Display9()
    {
        $deduction = Deduction::all();
        return view('Deduction', compact('deduction'));
    }
    public function Display8()
    {
        $currentDate = Carbon::now();
    
        // Determine payroll period (1st-15th or 16th-end of month)
        $startDate = $currentDate->day <= 15
            ? $currentDate->copy()->startOfMonth()
            : $currentDate->copy()->startOfMonth()->addDays(15);
    
        $endDate = $currentDate->day <= 15
            ? $startDate->copy()->addDays(14)
            : $startDate->copy()->endOfMonth();
    
        // Current Payroll
        $employees = Employee::with(['position', 'deduction'])
            ->get()
            ->map(function ($employee) use ($startDate, $endDate) {
                // Automatically save payroll data and fetch the breakdown
                $payroll = $this->calculatePayroll($employee->employee_id, true, $startDate, $endDate);
    
                return [
                    'employee_id'       => $employee->employee_id,
                    'name'              => $employee->first_name . ' ' . $employee->last_name,
                    'position_name'     => $employee->position->position_name ?? 'N/A',
                    'regular_pay'       => round($payroll['regular_pay'] ?? 0, 2),
                    'overtime_pay'      => round($payroll['overtime_pay'] ?? 0, 2),
                    'holiday_pay'       => round($payroll['holiday_pay'] ?? 0, 2),
                    'extra_2to4_pay'    => round($payroll['extra_2to4_pay'] ?? 0, 2),
                    'gross_salary'      => round($payroll['gross_salary'] ?? 0, 2),
                    'cash_advance'      => round($payroll['cash_advance'] ?? 0, 2),
                    'deduction_name'    => $employee->deduction->name ?? 'None',
                    'deductions'        => round($payroll['deductions'] ?? 0, 2),
                    'total_deductions'  => round(($payroll['deductions'] ?? 0) + ($payroll['cash_advance'] ?? 0), 2),
                    'net_salary'        => round($payroll['net_salary'] ?? 0, 2),
                    'start_date'        => $startDate->toDateString(),
                    'end_date'          => $endDate->toDateString(),
                ];
            });
    
        // Payroll History
        $payrollHistory = Payroll::with('employee')
            ->orderBy('start_date', 'desc')
            ->get()
            ->map(function ($payroll) {
                $employee = $payroll->employee; // Handle null checks for relationships
                return [
                    'employee_id'       => $payroll->employee_id,
                    'name'              => $employee ? ($employee->first_name . ' ' . $employee->last_name) : 'N/A',
                    'position_name'     => $employee?->position?->position_name ?? 'N/A',
                    'regular_pay'       => round($payroll->regular_pay, 2),
                    'overtime_pay'      => round($payroll->overtime_pay, 2),
                    'holiday_pay'       => round($payroll->holiday_pay, 2),
                    'extra_2to4_pay'    => round($payroll->extra_2to4_pay, 2),
                    'gross_salary'      => round($payroll->gross_salary, 2),
                    'cash_advance'      => round($payroll->cash_advance, 2),
                    'deductions'        => round($payroll->deductions, 2),
                    'net_salary'        => round($payroll->net_salary, 2),
                    'start_date'        => $payroll->start_date,
                    'end_date'          => $payroll->end_date,
                ];
            });
    
        return view('payroll', ['employees' => $employees, 'payrollHistory' => $payrollHistory]);
    }
    
    public function viewEmployeeHistory($employee_id)
{
    $employee = Employee::findOrFail($employee_id);

    // Fetch payroll history for the specific employee
    $payrollHistory = Payroll::where('employee_id', $employee_id)
        ->orderBy('start_date', 'desc')
        ->get();

    return response()->json([
        'employee' => [
            'name' => $employee->first_name . ' ' . $employee->last_name,
            'position' => $employee->position->position_name ?? 'N/A',
        ],
        'payrolls' => $payrollHistory,
    ]);
}

    
    
    public function Display7()
    {
        $overtimes = Overtime::all();
        return view('overtime', compact('overtimes'));
    }

    public function addHoliday(Request $request)
    {
        try {
            $validated = $request->validate([
                'description' => 'required|string|max:255',
                'type' => 'required|string|max:255',
                'holiday_date' => 'required|date',
            ]);
    
            Holiday::create([
                'description' => $validated['description'],
                'type' => $validated['type'],
                'holiday_date' => $validated['holiday_date'],
            ]);
    
            return response()->json(['success' => true, 'message' => 'Holiday added successfully!']);
        } catch (\Exception $e) {
            Log::error("Add Holiday Failed: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to add holiday.'], 500);
        }
    }
    
    public function updateHoliday(Request $request, $holiday_id)
    {
        try {
            $validated = $request->validate([
                'description' => 'required|string|max:255',
                'type' => 'required|string|max:255',
                'holiday_date' => 'required|date',
            ]);
    
            $holiday = Holiday::where('holiday_id', $holiday_id)->firstOrFail(); // Use holiday_id
            $holiday->update($validated);
    
            return response()->json(['success' => true, 'message' => 'Holiday updated successfully!']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Holiday not found.'], 404);
        } catch (\Exception $e) {
            Log::error("Update Failed: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update holiday.'], 500);
        }
    }
    
    
    public function deleteHoliday($holiday_id)
    {
        try {
            $holiday = Holiday::where('holiday_id', $holiday_id)->firstOrFail(); // Use holiday_id
            $holiday->delete();
    
            return response()->json(['success' => true, 'message' => 'Holiday deleted successfully!']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Holiday not found.'], 404);
        } catch (\Exception $e) {
            Log::error("Delete Failed: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to delete holiday.'], 500);
        }
    }
    
    
    public function Display6()
    {
        $holidays = Holiday::all()->map(function ($holiday) {
            return [
                'id' => $holiday->holiday_id, // Use holiday_id as the identifier
                'title' => $holiday->description,
                'start' => $holiday->holiday_date,
                'type' => $holiday->type, // Custom extended property
            ];
        });
        
        return view('holiday', compact('holidays'));
    }
    

    public function Display4()
    {
        $position = Position::all();
        return view('position', compact('position'));
    }

    public function Display3(Request $request)
    {
        // Total Employees
        $totalEmployees = Employee::count();
    
        // Today's Date
        $today = now()->toDateString();
    
        // Handle selected month and year from the request
        $selectedMonth = $request->input('month', now()->month); // Defaults to current month if not selected
        $selectedYear = now()->year; // Defaults to current year
    
        // Fetch today's attendance with schedules
        $attendancesToday = Attendance::with('employee.schedule')
            ->whereDate('check_in_time', $today)
            ->whereNotNull('check_in_time')
            ->get();
    
        // On-Time and Late Today
        $onTimeToday = 0;
        $lateToday = 0;
    
        foreach ($attendancesToday as $attendance) {
            $schedule = optional($attendance->employee->schedule);
        
            if ($schedule && $schedule->check_in_time && $attendance->check_in_time) {
                $scheduledCheckIn = Carbon::parse($schedule->check_in_time)->setTimezone('Asia/Manila');
                $gracePeriod = $scheduledCheckIn->addMinutes(10); // Add grace period
                
                $actualCheckIn = Carbon::parse($attendance->check_in_time)->setTimezone('Asia/Manila');
        
                if ($actualCheckIn->lte($gracePeriod)) {
                    $onTimeToday++;
                } else {
                    $lateToday++;
                }
            }
        }
    
        // Fetch attendance for the selected month
        $monthlyAttendance = Attendance::with('employee.schedule')
            ->whereYear('check_in_time', $selectedYear)
            ->whereMonth('check_in_time', $selectedMonth)
            ->whereNotNull('check_in_time')
            ->get();
    
        // Group Monthly Attendance by Day
        $daysInMonth = Carbon::createFromDate($selectedYear, $selectedMonth)->daysInMonth;
        $monthlyAttendanceGrouped = $monthlyAttendance->groupBy(function ($attendance) {
            return Carbon::parse($attendance->check_in_time)->day; // Group by day
        });
    
        // Initialize counters for on-time and late
        $onTimeSelectedMonth = 0;
        $lateSelectedMonth = 0;
    
        // Calculate daily on-time and late counts
        $attendanceCountsSelectedMonth = collect(range(1, $daysInMonth))->mapWithKeys(function ($day) use ($monthlyAttendanceGrouped) {
            $dailyAttendance = $monthlyAttendanceGrouped->get($day, collect());
    
            $ontime = 0;
            $late = 0;
    
            foreach ($dailyAttendance as $attendance) {
                $schedule = optional($attendance->employee->schedule);
    
                if ($schedule && $attendance->check_in_time) {
                    $scheduledCheckIn = Carbon::parse($schedule->check_in_time)->setTimezone('Asia/Manila');
                    $actualCheckIn = Carbon::parse($attendance->check_in_time)->setTimezone('Asia/Manila');
    
                    if ($actualCheckIn->lte($scheduledCheckIn)) {
                        $ontime++;
                    } else {
                        $late++;
                    }
                }
            }
    
            return [$day => ['ontime' => $ontime, 'late' => $late]];
        })->sortKeys();
    
        // Calculate totals for the selected month
        $onTimeSelectedMonth = $attendanceCountsSelectedMonth->sum('ontime');
        $lateSelectedMonth = $attendanceCountsSelectedMonth->sum('late');
    
        // Calculate on-time percentage
        $onTimePercentage = ($onTimeSelectedMonth + $lateSelectedMonth) > 0
            ? ($onTimeSelectedMonth / ($onTimeSelectedMonth + $lateSelectedMonth)) * 100
            : 0;
    
            // Check if the selected month has any attendance data
    if ($monthlyAttendance->isEmpty()) {
        // Set KPI values to 0 if there is no attendance data
        $onTimeToday = 0;
        $lateToday = 0;
        $onTimePercentage = 0;
        $attendanceCountsSelectedMonth = collect(range(1, $daysInMonth))->mapWithKeys(function ($day) {
            return [$day => ['ontime' => 0, 'late' => 0]];
        });
    } else {
    // Existing logic to calculate KPIs if attendance exists
    // (This part of the code remains as is)
}

    
        // Prepare chart data for Blade view
        $attendanceCountsSelectedMonth = $attendanceCountsSelectedMonth->map(function ($data, $day) {
            return [
                'day' => $day,
                'ontime' => $data['ontime'] ?? 0,
                'late' => $data['late'] ?? 0,
            ];
        })->values();
    
        // Pass the data to the view
        return view('admindash', compact(
            'totalEmployees',
            'onTimeToday',
            'lateToday',
            'attendanceCountsSelectedMonth',
            'selectedMonth',
            'onTimePercentage'
        ));
    }
    
  
    
    public function Display10()
    {
        // Fetching cash advances with employee details using Eloquent relationship
        $cashAdvances = CashAdvance::with('employee')->get(); 
    
        return view('cashadvance', compact('cashAdvances'));
    }

    public function store(Request $request)
{
    // Validate the form input
    $validated = $request->validate([
        'employee_id' => 'required|exists:employees,employee_id',
        'request_date' => 'required|date',
        'amount' => 'required|numeric',
        'status' => 'required|in:approved,pending,rejected',
    ]);

    // Create and store the new cash advance
    CashAdvance::create([
        'employee_id' => $validated['employee_id'],
        'request_date' => $validated['request_date'],
        'amount' => $validated['amount'],
        'status' => $validated['status'],
    ]);
    
    // Redirect with a success message
    return redirect()->route('admin.cashadvance')->with('success', 'Cash Advance created successfully!');
}    

public function update(Request $request, $id)
{
    $validated = $request->validate([
        'employee_id' => 'required|exists:employees,employee_id',
        'request_date' => 'required|date',
        'amount' => 'required|numeric',
        'status' => 'required|in:approved,pending,rejected',
    ]);

    $cashAdvance = CashAdvance::findOrFail($id);
    $cashAdvance->update($validated);

    return redirect()->route('admin.cashadvance')->with('success', 'Cash Advance updated successfully!');
}
 
public function destroy($id)
{
    try {
        // Find by the correct primary key
        $cashAdvance = CashAdvance::where('cash_advance_id', $id)->first();

        if (!$cashAdvance) {
            return response()->json(['success' => false, 'message' => 'Cash Advance not found.'], 404);
        }

        $cashAdvance->delete();

        return response()->json(['success' => true, 'message' => 'Cash Advance deleted successfully!']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'An error occurred while deleting.'], 500);
    }
}

    public function Display2()
    {
        return view('Employee');
    }

    public function DisplayAddEmployeeList()
    {
        $deduction = Deduction::all();
        $employees = Employee::with('deduction', 'schedule')->get(); // Load relationships
        $positions = Position::all();
        $schedules = Schedule::all(); // Fetch all schedules from the database
    
        return view('EmployeeList', compact('employees', 'positions', 'deduction', 'schedules'));
    }
    
    
    public function Display1($month = null)
    {
        $month = $month ?: Carbon::now()->month; // Default to current month if not provided
        $startDate = Carbon::now()->month($month)->startOfMonth(); // Start of the selected month
        $endDate = Carbon::now()->startOfDay()->addDay(); // Current date plus one day
        $employees = Employee::all();
    
        $attendanceData = [];
        $attendances = Attendance::with('employee')
            ->whereBetween('check_in_time', [$startDate, $endDate])
            ->get()
            ->map(function ($attendance) {
                if ($attendance->check_out_time) {
                    $hoursWorked = Carbon::parse($attendance->check_in_time)
                        ->diffInHours(Carbon::parse($attendance->check_out_time));
                    $attendance->overtime_hours = $hoursWorked > 8 ? $hoursWorked - 8 : 0;
                } else {
                    $attendance->overtime_hours = 0;
                }
                return $attendance;
            });
    
        foreach ($employees as $employee) {
            $workingDays = [];
            $saturdaysCount = 0;
    
            for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
                if (!$date->isSunday()) { // Include Saturdays but exclude Sundays
                    $workingDays[] = $date->toDateString();
                }
                if ($date->isSaturday()) {
                    $saturdaysCount++;
                }
            }
    
            $presentDays = Attendance::where('employee_id', $employee->id)
                ->whereBetween('check_in_time', [$startDate, $endDate])
                ->pluck('check_in_time')
                ->map(fn($date) => Carbon::parse($date)->toDateString())
                ->toArray();
    
            $absentDays = array_diff($workingDays, $presentDays);
    
            $attendanceData[] = [
                'employee' => $employee,
                'total_present' => count($presentDays),
                'total_absent' => count($absentDays),
                'absent_days' => $absentDays,
                'saturdays_count' => $saturdaysCount,
            ];
        }
    
        return view('attendanceDash', compact('attendances', 'attendanceData', 'month'));
    }

    public function generateReport($id, $month = null)
    {
        try {
            $month = $month ?: Carbon::now()->month; // Default to current month if not provided
            $startDate = Carbon::now()->month($month)->startOfMonth();
            $endDate = Carbon::now()->startOfDay()->addDay(); // End date is the current day plus one
    
            $attendanceRecords = Attendance::where('employee_id', $id)
                ->whereBetween('check_in_time', [$startDate, $endDate])
                ->get();
    
            $presentDays = $attendanceRecords->pluck('check_in_time')
                ->map(fn($date) => Carbon::parse($date)->toDateString())
                ->toArray();
    
            $workingDays = [];
            $saturdaysCount = 0;
    
            for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
                if (!$date->isSunday()) {
                    $workingDays[] = $date->toDateString();
                }
                if ($date->isSaturday()) {
                    $saturdaysCount++;
                }
            }
    
            $absentDays = array_diff($workingDays, $presentDays);
    
            $expectedStartTime = "08:00:00";
            $lateCheckIns = $attendanceRecords->filter(function ($attendance) use ($expectedStartTime) {
                $checkIn = Carbon::parse($attendance->check_in_time);
                return $checkIn->format('H:i:s') > $expectedStartTime;
            })->count();
    
            $totalOvertime = 0;
            foreach ($attendanceRecords as $attendance) {
                if ($attendance->check_out_time) {
                    $hoursWorked = Carbon::parse($attendance->check_in_time)
                        ->diffInHours(Carbon::parse($attendance->check_out_time));
                    if ($hoursWorked > 8) {
                        $totalOvertime += $hoursWorked - 8;
                    }
                }
            }
    
            return response()->json([
                'total_present' => count($presentDays),
                'total_absent' => count($absentDays),
                'absent_days' => array_values($absentDays),
                'total_late' => $lateCheckIns,
                'total_overtime' => $totalOvertime,
                'saturdays_count' => $saturdaysCount,
            ]);
    
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    
    public function Display()
    {
        return view('Attendance');
    }

    public function saveposition(Request $request)
    {
        $validated = $request->validate([
            'position_name' => 'required|string|max:255',
            'rate_per_hour' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
        ]);

        Position::create($validated);

        return response()->json(['success' => true, 'message' => 'Position added successfully.']);
    }

    public function AddDeduction(Request $deduction)
    {
        $validated = $deduction->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric',
        ]);

        Deduction::create($validated);

        return response()->json(['success' => true, 'message' => 'Deduction added successfully.']);
    }

        // Display all schedules
        public function Display5()
        {
            $schedules = Schedule::all();
            return view('schedule', compact('schedules'));
        }
    
        // Add a new schedule
        public function addSchedule(Request $request)
        {
            Log::info('Request Data:', $request->all());
        
            try {
                $validated = $request->validate([
                    'description' => 'required|string',
                    'check_in_time' => 'required|date_format:H:i',
                    'check_out_time' => 'required|date_format:H:i',
                ]);
        
                // Handle overnight shift: check if check_out_time is before check_in_time
                $checkInTime = Carbon::createFromFormat('H:i', $validated['check_in_time']);
                $checkOutTime = Carbon::createFromFormat('H:i', $validated['check_out_time']);
        
                // If check-out time is less than or equal to check-in time, assume next day
                if ($checkOutTime <= $checkInTime) {
                    $checkOutTime->addDay();
                }
        
                // Store the schedule
                Schedule::create([
                    'description' => $validated['description'],
                    'check_in_time' => $checkInTime->format('H:i'),  // Save as 'H:i'
                    'check_out_time' => $checkOutTime->format('H:i'), // Save as 'H:i'
                ]);
        
                Log::info('Schedule created successfully.');
        
                return redirect()->route('admin.schedule')->with('success', 'Schedule added successfully!');
            } catch (\Exception $e) {
                Log::error('Add Schedule Error: ' . $e->getMessage());
                return back()->withErrors(['error' => 'Failed to add schedule.']);
            }
        }
        
        public function updateSchedule(Request $request, $schedule_id)
        {
            try {
                // Allow more flexible time input
                $validated = $request->validate([
                    'description' => 'required|string',
                    'check_in_time' => 'required',
                    'check_out_time' => 'required',
                ]);
        
                // Parse times in flexible formats (supports AM/PM or H:i)
                $checkInTime = Carbon::parse($validated['check_in_time']);
                $checkOutTime = Carbon::parse($validated['check_out_time']);
        
                // Handle overnight shifts: add a day to check-out if earlier than check-in
                if ($checkOutTime <= $checkInTime) {
                    $checkOutTime->addDay();
                }
        
                // Update the schedule
                $schedule = Schedule::findOrFail($schedule_id);
                $schedule->update([
                    'description' => $validated['description'],
                    'check_in_time' => $checkInTime->format('H:i'),  // Store in H:i (24-hour format)
                    'check_out_time' => $checkOutTime->format('H:i'),
                ]);
        
                return response()->json(['success' => true, 'message' => 'Schedule updated successfully!']);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return response()->json(['success' => false, 'message' => 'Schedule not found.'], 404);
            } catch (\Exception $e) {
                Log::error('Update Schedule Error: ' . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Failed to update schedule.'], 500);
            }
        }
        
        // Delete a schedule
        public function deleteSchedule($schedule_id)
        {
            try {
                $schedule = Schedule::findOrFail($schedule_id);
                $schedule->delete();
    
                return response()->json(['success' => true, 'message' => 'Schedule deleted successfully!']);
            } catch (\Exception $e) {
                Log::error('Delete Schedule Error: ' . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Failed to delete schedule.'], 500);
            }
        }

    public function updateDeduction(Request $request, $id)
    {
        try {
            Log::info('Incoming request data: ', $request->all());
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'amount' => 'required|numeric|min:0',
            ]);

            $deduction = Deduction::findOrFail($id);
            $deduction->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Deduction updated successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating deduction: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.',
            ], 500);
        }
    }

    public function editDeduction($id)
    {
        try {
            // Fetch the deduction record by ID
            $deduction = Deduction::findOrFail($id);

            // Return the deduction data as JSON
            return response()->json($deduction);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Deduction not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.'
            ], 500);
        }
    }

    public function addOvertime(Request $request)
    {
        $validatedData = $request->validate([
            'Overtime_Type' => 'required|string|max:255',
            'Rate_Per_Hour' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
        ]);
    
        try {
            Overtime::create($validatedData);
    
            return response()->json(['success' => true, 'message' => 'Overtime type added successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to add overtime type.'], 500);
        }
    }

    
public function updateOvertime(Request $request, $id)
{
    try {
        $validatedData = $request->validate([
            'Overtime_Type' => 'required|string|max:255',
            'Rate_Per_Hour' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
        ]);

        $overtime = Overtime::findOrFail($id); // Ensure it exists
        $overtime->update($validatedData); // Update the record

        Log::info("Overtime with ID {$id} updated successfully.");
        return response()->json(['success' => true, 'message' => 'Overtime updated successfully!']);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        Log::error("Overtime with ID {$id} not found.");
        return response()->json(['success' => false, 'message' => 'Overtime not found.'], 404);
    } catch (\Exception $e) {
        Log::error("Error updating overtime with ID {$id}: " . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'An unexpected error occurred.'], 500);
    }
}

public function deleteOvertime($id)
{
    Log::info("Attempting to delete overtime with ID: {$id}");

    try {
        $overtime = Overtime::findOrFail($id); // Correct primary key
        Log::info("Found overtime record: " . json_encode($overtime));

        $overtime->delete();
        Log::info("Overtime with ID {$id} deleted successfully.");

        return response()->json(['success' => true, 'message' => 'Overtime deleted successfully!']);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        Log::error("Overtime with ID {$id} not found.");
        return response()->json(['success' => false, 'message' => 'Overtime not found.'], 404);
    } catch (\Exception $e) {
        Log::error("Error deleting overtime with ID {$id}: " . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'An unexpected error occurred.'], 500);
    }
}

public function add(Request $request)
{
    // Validate incoming request
    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'birthdate' => 'required|date',
        'contact_no' => 'required|string|max:20',
        'gender' => 'required|in:Male,Female',
        'position_id' => 'nullable|exists:positions,position_id',
        'statutory_benefits' => 'required|exists:deductions,deduction_id',
        'photo' => 'nullable|image|max:2048',
    ]);

    // Store statutory_benefits as deduction_id directly
    $employee = new Employee([
        'first_name' => $validated['first_name'],
        'last_name' => $validated['last_name'],
        'address' => $validated['address'],
        'birthdate' => $validated['birthdate'],
        'contact_no' => $validated['contact_no'],
        'gender' => $validated['gender'],
        'position_id' => $validated['position_id'],
        'statutory_benefits' => $validated['statutory_benefits'], // Store as deduction_id
    ]);

    // Handle photo upload
    if ($request->hasFile('photo')) {
        $path = $request->file('photo')->store('photos', 'public');
        $employee->photo = $path;
    }

    $employee->save();

    return redirect()->route('admin.addEmployeeList')->with('success', 'Employee added successfully!');
}


    public function loginAuth(Request $request)
    {
        $IncomingFields = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt([
            'username' => $IncomingFields['username'],
            'password' => $IncomingFields['password']
        ])) {
            $request->session()->regenerate();
            session()->flash('login_success', 'You have successfully logged in!');
            return redirect()->route('admin.dashboard');
        } else {
            return redirect('/')->with('incorrect_msg', 'Incorrect Credentials. Login Unsuccessful.');
        }
    }

    public function Submit(Request $data)
    {
        // Validate input
        $validated = $data->validate([
            'employee_id' => 'required|integer',
            'attendancestatus' => 'required|in:timein,timeout',
            'check_in_time' => 'required|date',
        ]);
    
        $employeeId = $validated['employee_id'];
        $attendanceStatus = $validated['attendancestatus'];
        $checkInTime = Carbon::parse($validated['check_in_time']); // Submitted check-in/check-out time
    
        // Fetch employee and schedule
        $employee = Employee::with('schedule')->find($employeeId);
        if (!$employee) {
            return back()->with('error', 'Employee not found!');
        }
    
        if (!$employee->schedule) {
            return back()->with('error', 'No schedule assigned for this employee!');
        }
    
        $schedule = $employee->schedule;
    
        // Schedule details
        $rawCheckInTime = $schedule->check_in_time; // Scheduled start
        $rawCheckOutTime = $schedule->check_out_time; // Scheduled end
    
        // Determine schedule start and end times
        $scheduleStart = Carbon::parse($rawCheckInTime)->setDate($checkInTime->year, $checkInTime->month, $checkInTime->day);
        $scheduleEnd = Carbon::parse($rawCheckOutTime)->setDate($checkInTime->year, $checkInTime->month, $checkInTime->day);
    
        // If it's an overnight shift, adjust check-out time to the next day
        if ($scheduleEnd <= $scheduleStart) {
            $scheduleEnd->addDay();
        }
    
        // Restrict attendance earlier than 1 hour before schedule
        $earliestAllowedCheckIn = $scheduleStart->copy()->subHour(); // 1 hour before schedule start
    
        // Restrict attendance past the schedule end time
        if ($checkInTime->lt($earliestAllowedCheckIn)) {
            return back()->with('error', 'Attendance denied: You cannot check in more than 1 hour before your scheduled time.');
        }
    
        if ($checkInTime->gt($scheduleEnd)) {
            return back()->with('error', 'Attendance denied: You cannot check in after your scheduled check-out time.');
        }
    
        if ($attendanceStatus === 'timein') {
            // Debugging logs
            Log::info("Check-in Time: {$checkInTime}");
            Log::info("Schedule Start: {$scheduleStart}, Earliest Allowed: {$earliestAllowedCheckIn}, Schedule End: {$scheduleEnd}");
    
            // Check if employee is late (10-minute threshold)
            $lateThreshold = $scheduleStart->copy()->addMinutes(10);
            $status = $checkInTime->greaterThan($lateThreshold) ? 'Late' : 'On-Time';
    
            // Prevent duplicate check-in
            $attendance = Attendance::where('employee_id', $employeeId)
                ->whereDate('check_in_time', $scheduleStart->toDateString())
                ->first();
    
            if ($attendance) {
                return back()->with('error', 'You already checked in for today!');
            }
    
            // Save check-in with status and schedule_id
            Attendance::create([
                'employee_id' => $employeeId,
                'schedule_id' => $schedule->id, // Save the schedule ID
                'check_in_time' => $checkInTime,
                'check_out_time' => null,
                'status' => $status,
            ]);
    
            return back()->with('success', "Time In recorded successfully! Status: {$status}");
        }
    
        // Handle check-out
        if ($attendanceStatus === 'timeout') {
            // Find existing attendance record for today
            $attendance = Attendance::where('employee_id', $employeeId)
                ->whereDate('check_in_time', $checkInTime->toDateString())
                ->first();
    
            if (!$attendance) {
                return back()->with('error', 'Cannot check out without checking in first!');
            }
    
            if ($attendance->check_out_time) {
                return back()->with('error', 'You have already checked out for today!');
            }
    
            // Save check-out time
            $attendance->update([
                'check_out_time' => $checkInTime,
            ]);
    
            return back()->with('success', 'Time Out recorded successfully!');
        }
    
        return back()->with('error', 'Invalid attendance status.');
    }
    

    public function updateEmployee(Request $request, $id)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'contact_no' => 'required|string|max:20',
            'gender' => 'required|in:Male,Female',
            'position_id' => 'nullable|exists:positions,position_id',
            'schedule_id' => 'required|exists:schedules,schedule_id', // Validate foreign key
            'statutory_benefits' => 'required|string|max:255',
            'photo' => 'nullable|image|max:2048',
        ]);
    
        // Find the employee and update fields
        $employee = Employee::findOrFail($id);
        $employee->update($validated);
    
        // Handle photo upload
        if ($request->hasFile('photo')) {
            if ($employee->photo && Storage::disk('public')->exists($employee->photo)) {
                Storage::disk('public')->delete($employee->photo);
            }
            $employee->photo = $request->file('photo')->store('photos', 'public');
            $employee->save();
        }
    
        return redirect()->route('admin.addEmployeeList')->with('success', 'Employee updated successfully!');
    }
    
     
    public function deleteEmployee($id)
{
    $employee = Employee::find($id);

    if (!$employee) {
        return response()->json(['success' => false, 'message' => 'Employee not found!'], 404);
    }

    $employee->delete();

    return response()->json(['success' => true, 'message' => 'Employee deleted successfully!']);
}

    
    public function edit($id)
    {
        $cashAdvance = CashAdvance::where('cash_advance_id', $id)->first();
    
        if (!$cashAdvance) {
            return response()->json(['success' => false, 'message' => 'Cash Advance not found.'], 404);
        }
    
        return response()->json($cashAdvance);
    }

    public function deleteDeduction($id)
    {
        Log::info("Received request to delete deduction with ID: $id");

        $deduction = Deduction::find($id);

        if ($deduction) {
            Log::info("Found deduction: " . json_encode($deduction));
            $deduction->delete();
            Log::info("Deduction with ID $id deleted successfully.");
            return response()->json(['success' => true, 'message' => 'Deduction deleted successfully.']);
        }

        Log::error("Deduction with ID $id not found.");
        return response()->json(['success' => false, 'message' => 'Deduction not found.']);
    }

    public function deletePosition($id)
    {
        Log::info("Attempting to delete position with ID: $id");
        try {
            $position = Position::findOrFail($id);
            $position->delete();

            Log::info("Deleted position with ID: $id successfully.");
            return response()->json(['success' => true, 'message' => 'Position deleted successfully.']);
        } catch (\Exception $e) {
            Log::error("Error deleting position with ID: $id - " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An unexpected error occurred.']);
        }
    }

    public function getEmployee($id)
    {
        $employee = Employee::findOrFail($id);
        return response()->json($employee);
    }
    
    public function updatePosition(Request $request, $id)
    {
        // Validate the input
        $validated = $request->validate([
            'position_name' => 'required|string|max:255',
            'rate_per_hour' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
        ]);
    
        // Find the position by its ID
        $position = Position::find($id);
    
        if (!$position) {
            return response()->json(['success' => false, 'message' => 'Position not found!'], 404);
        }
    
        // Update the position details
        $position->update($validated);
    
        return response()->json(['success' => true, 'message' => 'Position updated successfully!']);
    }
    public function saveAll(Request $request)
{
    // Get all employees
    $employees = Employee::all();

    // Loop through each employee and save payroll
    foreach ($employees as $employee) {
        $this->calculatePayroll($employee->employee_id, true);
    }

    // Redirect back with a success message
    return redirect()->back()->with('success', 'Payroll data saved successfully!');
}

    
public function calculatePayroll($employee_id, $saveToDatabase = false, $startDate = null, $endDate = null)
{
    $employee = Employee::with('position')->find($employee_id);
    if (!$employee || !$employee->position) {
        return null;
    }

    // Rates
    $regularRate = $employee->position->rate_per_hour;
    $overtimeRate = $regularRate * 1.25;
    $extra2to4AMRate = ($regularRate * 0.1) + $overtimeRate;

    // Determine current 15-day period if no dates are passed
    $today = Carbon::now();
    $startDate = $startDate ?? ($today->day <= 15 
                                 ? $today->copy()->startOfMonth() 
                                 : $today->copy()->startOfMonth()->addDays(15));
    $endDate = $endDate ?? ($today->day <= 15 
                             ? $startDate->copy()->addDays(14) 
                             : $startDate->copy()->endOfMonth());

    // Attendance data
    $attendances = Attendance::where('employee_id', $employee_id)
        ->whereBetween('check_in_time', [$startDate, $endDate])
        ->whereNotNull('check_in_time')
        ->whereNotNull('check_out_time')
        ->get();

    // Totals
    $totalRegularPay = 0;
    $totalOvertimePay = 0;
    $totalHolidayPay = 0;
    $total2to4AMPay = 0;

    foreach ($attendances as $attendance) {
        $checkIn = Carbon::parse($attendance->check_in_time);
        $checkOut = Carbon::parse($attendance->check_out_time);

        if ($checkOut->lessThan($checkIn)) {
            $checkOut->addDay(); // Handle overnight shifts
        }

        $hoursWorked = $checkIn->diffInHours($checkOut);

        // Check for holiday
        $holiday = Holiday::whereDate('holiday_date', $checkIn->toDateString())->first();
        $isSunday = $checkIn->isSunday();

        // Holiday bonus logic
        $holidayBonus = 0;
        if ($holiday) {
            if ($holiday->type === 'regular') {
                $holidayBonus = $regularRate; // Double pay for regular holiday
            } elseif ($holiday->type === 'special') {
                $holidayBonus = $regularRate * 0.3; // 30% extra for special holidays
            }
        } elseif ($isSunday) {
            $holidayBonus = $regularRate * 0.3; // Sunday bonus
        }

        // Regular hours calculation (max 8 hours)
        $regularHours = min(8, $hoursWorked);
        $totalRegularPay += $regularHours * $regularRate;
        $totalHolidayPay += $regularHours * $holidayBonus;

        // Overtime calculation (beyond 8 hours)
        $overtimeHours = max(0, $hoursWorked - 8);

        // 2-4 AM Pay Logic
        $start2AM = $checkIn->copy()->setTime(2, 0, 0);
        $end4AM = $checkIn->copy()->setTime(4, 0, 0);

        if ($checkIn->hour >= 18 || $checkIn->hour < 4) {
            $start2AM = $checkIn->copy()->addDay()->setTime(2, 0, 0);
            $end4AM = $checkIn->copy()->addDay()->setTime(4, 0, 0);
        }

        // Calculate overlap between 2-4 AM and attendance time
        $overlapHours = 0;
        if ($checkOut->greaterThan($start2AM) && $checkIn->lessThan($end4AM)) {
            $overlapStart = $checkIn->greaterThan($start2AM) ? $checkIn : $start2AM;
            $overlapEnd = $checkOut->lessThan($end4AM) ? $checkOut : $end4AM;

            if ($overlapStart->lessThan($overlapEnd)) {
                $overlapHours = $overlapStart->diffInMinutes($overlapEnd) / 60;
                $total2to4AMPay += $overlapHours * $extra2to4AMRate;
            }
        }

        // Adjust overtime hours if 2-4 AM overlap exists
        if ($overlapHours > 0) {
            $overtimeHours = max(0, $overtimeHours - $overlapHours);
        }

        $totalOvertimePay += $overtimeHours * $overtimeRate;
    }

    // Calculate gross salary
    $grossSalary = $totalRegularPay + $totalOvertimePay + $totalHolidayPay + $total2to4AMPay;

    // Check if deductions have already been applied this month
    $currentMonth = $startDate->format('Y-m');
    $existingPayrollWithDeductions = Payroll::where('employee_id', $employee_id)
        ->where('start_date', 'LIKE', "$currentMonth%")
        ->where('deductions', '>', 0)
        ->exists();

    // Apply deductions only if not already applied
    $deductions = 0;
    if (!$existingPayrollWithDeductions) {
        $deductions = $employee->deduction ? $employee->deduction->amount : 0;
    }

    // Fetch and sum cash advances only for this payroll period
    $cashAdvanceTotal = CashAdvance::where('employee_id', $employee_id)
        ->whereBetween('request_date', [$startDate, $endDate])
        ->where('status', 'approved')
        ->sum('amount');

    // Net salary calculation
    $netSalary = $grossSalary - $deductions - $cashAdvanceTotal;

    $payrollData = [
        'employee_id'   => $employee_id,
        'start_date'    => $startDate->toDateString(),
        'end_date'      => $endDate->toDateString(),
        'regular_pay'   => round($totalRegularPay, 2),
        'overtime_pay'  => round($totalOvertimePay, 2),
        'holiday_pay'   => round($totalHolidayPay, 2),
        'extra_2to4_pay' => round($total2to4AMPay, 2),
        'gross_salary'  => round($grossSalary, 2),
        'cash_advance'  => round($cashAdvanceTotal, 2),
        'deductions'    => round($deductions, 2),
        'net_salary'    => round($netSalary, 2),
    ];

    // Save to database if requested
    if ($saveToDatabase) {
        Payroll::updateOrCreate(
            [
                'employee_id' => $employee_id,
                'start_date'  => $startDate->toDateString(),
                'end_date'    => $endDate->toDateString(),
            ],
            $payrollData
        );
    }

    return $payrollData;
}
}