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
        $employees = Employee::with(['position', 'deduction'])
            ->get()
            ->map(function ($employee) {
                $payroll = $this->calculatePayroll($employee->employee_id);
                return [
                    'employee_id' => $employee->employee_id,
                    'name' => $employee->first_name . ' ' . $employee->last_name,
                    'position_name' => $employee->position->position_name ?? 'N/A',
                    'regular_pay' => $payroll->regular_pay ?? 0,
                    'overtime_pay' => $payroll->overtime_pay ?? 0,
                    'holiday_pay' => $payroll->holiday_pay ?? 0,
                    'extra_2to4_pay' => $payroll->extra_2to4_pay ?? 0,
                    'gross_salary' => $payroll->gross_salary ?? 0,
                    'deduction_name' => $employee->deduction->name ?? 'None',
                    'deductions' => $employee->deduction->amount ?? 0,
                    'cash_advance' => $payroll->cash_advance ?? 0, // Cash advance included
                    'total_deductions' => ($employee->deduction->amount ?? 0) + ($payroll->cash_advance ?? 0),
                    'net_salary' => $payroll->net_salary ?? 0,
                ];
            });
    
        return view('payroll', ['employees' => $employees]);
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
                'holiday_date' => 'required|date',
            ]);

            Holiday::create([
                'description' => $validated['description'],
                'holiday_date' => $validated['holiday_date'],
            ]);

            return response()->json(['success' => true, 'message' => 'Holiday added successfully!']);
        } catch (\Exception $e) {
            Log::error('Failed to add holiday: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to add holiday.'], 500);
        }
    }



    public function updateHoliday(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'description' => 'required|string|max:255',
                'holiday_date' => 'required|date',
            ]);
    
            $holiday = Holiday::where('holiday_id', $id)->firstOrFail();
            $holiday->update($validated);
    
            return response()->json(['success' => true, 'message' => 'Holiday updated successfully!']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Holiday not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An unexpected error occurred.'], 500);
        }
    }
    public function deleteHoliday($id)
    {
        try {
            $holiday = Holiday::where('holiday_id', $id)->firstOrFail();
            $holiday->delete();
    
            return response()->json(['success' => true, 'message' => 'Holiday deleted successfully!']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Holiday not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An unexpected error occurred.'], 500);
        }
    }
    
    
    public function Display6()
    {
        $holidays = Holiday::all()->map(function ($holiday) {
            return [
                'id' => $holiday->holiday_id, // Use 'holiday_id' here
                'title' => $holiday->description,
                'start' => $holiday->holiday_date
            ];
        });
    
        return view('holiday', compact('holidays'));
    }
    

    public function Display4()
    {
        $position = Position::all();
        return view('position', compact('position'));
    }

    public function Display3()
    {
    
        // Total Employees
        $totalEmployees = Employee::count();
    
        // Today's Date
        $today = now()->toDateString();
    
        // Fetch today's attendance with employees and their schedules
        $attendancesToday = Attendance::with('employee.schedule')
            ->whereDate('check_in_time', $today)
            ->get();
    
        // On-Time and Late Today
        $onTimeToday = $attendancesToday->filter(function ($attendance) {
            $schedule = $attendance->employee->schedule;
            if ($schedule && $attendance->check_in_time) {
                $attendanceTime = Carbon::parse($attendance->check_in_time);
                $scheduleTime = Carbon::parse($schedule->check_in_time);
                $differenceInMinutes = $scheduleTime->diffInMinutes($attendanceTime, false); // Negative if early
    
                // Employee is on time if they check in within 10 minutes after the scheduled time or earlier
                return $differenceInMinutes <= 10;
            }
            return false;
        })->count();
    
        $lateToday = $attendancesToday->filter(function ($attendance) {
            $schedule = $attendance->employee->schedule;
            if ($schedule && $attendance->check_in_time) {
                $attendanceTime = Carbon::parse($attendance->check_in_time);
                $scheduleTime = Carbon::parse($schedule->check_in_time);
                $differenceInMinutes = $scheduleTime->diffInMinutes($attendanceTime, false);
    
                // Employee is late if they check in more than 10 minutes after the scheduled time
                return $differenceInMinutes > 10;
            }
            return false;
        })->count();
    
        // Calculate On-Time Percentage
        $totalAttendanceToday = $onTimeToday + $lateToday;
        $onTimePercentage = $totalAttendanceToday > 0
            ? ($onTimeToday / $totalAttendanceToday) * 100
            : 0;
    
        // Monthly Attendance Report
        $attendanceCountsCurrentMonth = Attendance::with('employee.schedule')
            ->whereMonth('check_in_time', now()->month)
            ->whereNotNull('check_in_time')
            ->get()
            ->groupBy(function ($attendance) {
                return date('j', strtotime($attendance->check_in_time)); // Group by day
            })
            ->map(function ($dayAttendances) {
                $ontime = $dayAttendances->filter(function ($attendance) {
                    $schedule = $attendance->employee->schedule;
                    if ($schedule && $attendance->check_in_time) {
                        $attendanceTime = Carbon::parse($attendance->check_in_time);
                        $scheduleTime = Carbon::parse($schedule->check_in_time);
                        $differenceInMinutes = $scheduleTime->diffInMinutes($attendanceTime, false);
                        return $differenceInMinutes <= 10;
                    }
                    return false;
                })->count();
    
                $late = $dayAttendances->filter(function ($attendance) {
                    $schedule = $attendance->employee->schedule;
                    if ($schedule && $attendance->check_in_time) {
                        $attendanceTime = Carbon::parse($attendance->check_in_time);
                        $scheduleTime = Carbon::parse($schedule->check_in_time);
                        $differenceInMinutes = $scheduleTime->diffInMinutes($attendanceTime, false);
                        return $differenceInMinutes > 10;
                    }
                    return false;
                })->count();
    
                return [
                    'ontime' => $ontime,
                    'late' => $late,
                ];
            })->sortKeys();
    
        // Prepare data for the chart
        $attendanceCountsCurrentMonth = $attendanceCountsCurrentMonth->map(function ($data, $day) {
            return [
                'day' => $day,
                'ontime' => $data['ontime'],
                'late' => $data['late'],
            ];
        })->values();
    
        // Return data to the dashboard view
        return view('admindash', compact(
            'totalEmployees',
            'onTimePercentage',
            'onTimeToday',
            'lateToday',
            'attendanceCountsCurrentMonth'
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
    
    
    

    public function Display1()
    {
        $attendances = Attendance::all();
        return view('attendanceDash', compact('attendances'));
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
        // Validate incoming request
        $validated = $data->validate([
            'employee_id' => 'required|integer',
            'attendancestatus' => 'required|in:timein,timeout',
            'check_in_time' => 'required|date',
        ]);

        $employeeId = $validated['employee_id'];
        $attendanceStatus = $validated['attendancestatus'];

        // Check if employee exists
        $employee = Employee::find($employeeId);
        if (!$employee) {
            return back()->with('error', 'Employee not found!');
        }

        // Adjust check-in time based on the 15-minute rule
        $checkInTime = Carbon::parse($validated['check_in_time']);
        $minute = $checkInTime->minute;

        if ($minute >= 45 || $minute <= 15) {
            // Round to the nearest hour
            $adjustedTime = $minute >= 45
                ? $checkInTime->copy()->ceilHour()
                : $checkInTime->copy()->floorHour();
        } else {
            // Keep the exact time if between 16 and 44 minutes
            $adjustedTime = $checkInTime;
        }

        // Check for a holiday on the current date
        $holiday = Holiday::whereDate('holiday_date', $adjustedTime->toDateString())->first();
        $holidayId = $holiday ? $holiday->holiday_id : null;

        // Check if attendance already exists for the employee on the same day
        $attendance = Attendance::where('employee_id', $employeeId)
            ->whereDate('check_in_time', $adjustedTime->toDateString())
            ->first();

        if ($attendance) {
            // Handle Time Out scenario
            if ($attendanceStatus === 'timeout') {
                $attendance->update([
                    'check_out_time' => now()->toTimeString(),
                ]);
                return back()->with('success', 'Time Out recorded successfully!');
            } else {
                // Time In already exists
                return back()->with('error', 'Time In already exists for this employee on this date!');
            }
        } else {
            // Handle Time In scenario
            if ($attendanceStatus === 'timein') {
                Attendance::create([
                    'employee_id' => $employeeId,
                    'check_in_time' => $adjustedTime,
                    'check_out_time' => null,
                    'holiday_id' => $holidayId, // Store holiday_id if it's a holiday
                ]);
                return back()->with('success', 'Time In recorded successfully!');
            } else {
                return back()->with('error', 'No Time In record found to Time Out!');
            }
        }
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
    

    public function assignPosition(Request $request)
    {
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,employee_id',
            'position' => 'required|exists:positions,position_id',
        ]);

        $employee = Employee::find($validatedData['employee_id']);

        if ($employee->position_id != $validatedData['position']) {
            $employee->position_id = $validatedData['position'];
            $employee->save();

            return redirect()->back()->with('success', 'Position updated successfully!');
        } else {
            return redirect()->back()->with('info', 'No changes were made, as the selected position is the same.');
        }
    }

    public function calculatePayroll($employee_id)
    {
        $employee = Employee::with('position')->find($employee_id);
        if (!$employee || !$employee->position) {
            return null;
        }
    
        // Rate calculation
        $regularRate = $employee->position->rate_per_hour;
        $overtimeRate = $regularRate * 1.25;
        $holidayRate = $regularRate * 0.3; // 30% additional for holidays
        $extra2to4AMRate = $regularRate * 0.1; // 10% additional for 2-4 AM
    
        // Attendance data
        $attendances = Attendance::where('employee_id', $employee_id)->get();
    
        // Totals
        $totalRegularHours = 0;
        $totalOvertimeHours = 0;
        $totalHolidayPay = 0;
        $totalExtra2to4AMPay = 0;
    
        foreach ($attendances as $attendance) {
            if ($attendance->check_in_time && $attendance->check_out_time) {
                $checkIn = Carbon::parse($attendance->check_in_time);
                $checkOut = Carbon::parse($attendance->check_out_time);
    
                if ($checkOut->lessThan($checkIn)) {
                    $checkOut->addDay();
                }
    
                $hoursWorked = $checkIn->diffInHours($checkOut);
    
                // Split regular and overtime hours
                $regularHours = min(8, $hoursWorked);
                $overtimeHours = max(0, $hoursWorked - 8);
    
                // Check for holiday pay
                $holiday = Holiday::whereDate('holiday_date', $checkIn->toDateString())->first();
                if ($holiday) {
                    $totalHolidayPay += $hoursWorked * $holidayRate;
                }
    
                // 2-4 AM Additional Pay
                $start2AM = $checkIn->copy()->setHour(2)->setMinute(0);
                $end4AM = $checkIn->copy()->setHour(4)->setMinute(0);
    
                if ($checkOut->greaterThan($start2AM)) {
                    $extraStart = $checkIn->greaterThan($start2AM) ? $checkIn : $start2AM;
                    $extraEnd = $checkOut->lessThan($end4AM) ? $checkOut : $end4AM;
    
                    $extra2to4AMHours = max(0, $extraStart->diffInHours($extraEnd));
                    $totalExtra2to4AMPay += $extra2to4AMHours * $extra2to4AMRate;
                }
    
                $totalRegularHours += $regularHours;
                $totalOvertimeHours += $overtimeHours;
            }
        }
    
        // Calculate pays
        $totalRegularPay = $totalRegularHours * $regularRate;
        $totalOvertimePay = $totalOvertimeHours * $overtimeRate;
        $grossSalary = $totalRegularPay + $totalOvertimePay + $totalHolidayPay + $totalExtra2to4AMPay;
    
        // Fetch deductions
        $deductions = $employee->deduction ? $employee->deduction->amount : 0;
    
        // Fetch and sum cash advances for this employee
        $cashAdvanceTotal = CashAdvance::where('employee_id', $employee_id)
            ->where('status', 'approved')
            ->sum('amount');
    
        // Net salary calculation
        $netSalary = $grossSalary - $deductions - $cashAdvanceTotal;
    
        // Save payroll
        $payroll = Payroll::updateOrCreate(
            ['employee_id' => $employee_id, 'created_at' => now()],
            [
                'gross_salary' => $grossSalary,
                'net_salary' => $netSalary,
                'deduction_id' => $employee->statutory_benefits,
            ]
        );
    
        // Attach detailed breakdown to the returned object
        $payroll->regular_pay = $totalRegularPay;
        $payroll->overtime_pay = $totalOvertimePay;
        $payroll->holiday_pay = $totalHolidayPay;
        $payroll->extra_2to4_pay = $totalExtra2to4AMPay;
        $payroll->cash_advance = $cashAdvanceTotal; // Include cash advance for the breakdown
    
        return $payroll;
    }
}    