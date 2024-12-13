<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Overtime;
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
        // Fetch all employees and select relevant columns, including their cash advances
        $employees = Employee::select('employee_id', 'first_name', 'last_name', 'statutory_benefits')
            ->withSum(['cashAdvances' => function ($query) {
                $query->where('status', 'approved'); // Only sum approved cash advances
            }], 'amount')
            ->get();
        
        // Loop through each employee and calculate payroll
        foreach ($employees as $employee) {
            // Call the calculatePayroll function for each employee and get the payroll object
            $payroll = $this->calculatePayroll($employee->employee_id);
            
            // Attach payroll data to employee
            $employee->payroll = $payroll;
            
            // Attach the total approved cash advance to the employee
            $employee->approved_cash_advance = $employee->cash_advances_sum_amount;
            
            // Optionally, you can also include deduction name or amount if necessary:
            $employee->deduction_name = $payroll->deduction_id ? Deduction::find($payroll->deduction_id)->name : null;
            $employee->total_deductions = $payroll->deduction_id ? Deduction::find($payroll->deduction_id)->amount : 0;
        }
    
        // Pass the employees with payroll data to the view
        return view('payroll', ['employees' => $employees]);
    }
    
    
    

    public function Display7()
    {
        $overtimes = Overtime::all();
        return view('overtime', compact('overtimes'));
    }

    public function Display6()
    {
        return view('holiday');
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
    
        // On-time Percentage (Total On-time / Total Records * 100)
        $totalAttendance = Attendance::count();
        $onTimeAttendance = Attendance::whereRaw('
            (TIME_TO_SEC(check_in_time) <= TIME_TO_SEC("06:10:00") AND HOUR(check_in_time) < 12) OR 
            (TIME_TO_SEC(check_in_time) <= TIME_TO_SEC("18:10:00") AND HOUR(check_in_time) >= 12)
        ')->count();
        $onTimePercentage = $totalAttendance > 0 ? ($onTimeAttendance / $totalAttendance) * 100 : 0;
    
        // On Time Today
        $today = now()->toDateString();
        $onTimeToday = Attendance::whereDate('date', $today)
            ->whereRaw('
                (TIME_TO_SEC(check_in_time) <= TIME_TO_SEC("06:10:00") AND HOUR(check_in_time) < 12) OR 
                (TIME_TO_SEC(check_in_time) <= TIME_TO_SEC("18:10:00") AND HOUR(check_in_time) >= 12)
            ')
            ->count();
    
        // Late Today (including the night time)
        $lateToday = Attendance::whereDate('date', $today)
            ->whereRaw('
                (TIME_TO_SEC(check_in_time) > TIME_TO_SEC("06:10:00") AND HOUR(check_in_time) < 12) OR 
                (TIME_TO_SEC(check_in_time) > TIME_TO_SEC("18:10:00"))
            ')
            ->count();
    
        // Monthly Attendance Counts
        $attendanceCounts = DB::table('attendances')
            ->selectRaw('
                MONTH(date) as month,
                SUM((TIME_TO_SEC(check_in_time) <= TIME_TO_SEC("06:10:00") AND HOUR(check_in_time) < 12) OR 
                    (TIME_TO_SEC(check_in_time) <= TIME_TO_SEC("18:10:00") AND HOUR(check_in_time) >= 12)) as ontime,
                SUM((TIME_TO_SEC(check_in_time) > TIME_TO_SEC("06:10:00") AND HOUR(check_in_time) < 12) OR 
                    (TIME_TO_SEC(check_in_time) > TIME_TO_SEC("18:10:00")) ) as late
            ')
            ->groupByRaw('MONTH(date)')
            ->orderByRaw('MONTH(date)')
            ->get();
    
        // Return the view with the necessary data
        return view('admindash', compact('totalEmployees', 'onTimePercentage', 'onTimeToday', 'lateToday', 'attendanceCounts'));
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
        $employees = Employee::all(); // Fetch all employees
        $positions = Position::all(); // Fetch all positions
        return view('EmployeeList', compact('employees', 'positions'));
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
            'rate_per_hour' => 'required|numeric',
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

    public function Display5()
{
    $schedules = Schedule::all(); // Fetch all schedules from the database
    return view('schedule', compact('schedules')); // Pass schedules to the view
}


    public function AddSched(Request $request)
    {
        $validated = $request->validate([
            'work_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time', // Ensure end time is after start time
        ]);

        Schedule::create($validated);

        return redirect()->route('admin.schedule')->with('success', 'Schedule added successfully!');
    }

    public function deleteSchedule($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('admin.schedule')->with('success', 'Schedule deleted successfully!');
    }

    public function addOvertime(Request $request)
    {
        $validatedData = $request->validate([
            'Overtime_Type' => 'required|string|max:255',
            'Rate_Per_Hour' => 'required|numeric|min:0'
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
            'Rate_Per_Hour' => 'required|numeric|min:0',
        ]);

        $overtime = Overtime::findOrFail($id); // Ensure it exists
        $overtime->update($validatedData); // Update the record

        \Log::info("Overtime with ID {$id} updated successfully.");
        return response()->json(['success' => true, 'message' => 'Overtime updated successfully!']);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        \Log::error("Overtime with ID {$id} not found.");
        return response()->json(['success' => false, 'message' => 'Overtime not found.'], 404);
    } catch (\Exception $e) {
        \Log::error("Error updating overtime with ID {$id}: " . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'An unexpected error occurred.'], 500);
    }
}

public function deleteOvertime($id)
{
    \Log::info("Attempting to delete overtime with ID: {$id}");

    try {
        $overtime = Overtime::findOrFail($id); // Correct primary key
        \Log::info("Found overtime record: " . json_encode($overtime));

        $overtime->delete();
        \Log::info("Overtime with ID {$id} deleted successfully.");

        return response()->json(['success' => true, 'message' => 'Overtime deleted successfully!']);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        \Log::error("Overtime with ID {$id} not found.");
        return response()->json(['success' => false, 'message' => 'Overtime not found.'], 404);
    } catch (\Exception $e) {
        \Log::error("Error deleting overtime with ID {$id}: " . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'An unexpected error occurred.'], 500);
    }
}

    public function add(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'contact_no' => 'required|string|max:20',
            'gender' => 'required|in:Male,Female',
            'position_id' => 'nullable|exists:positions,position_id', // Make position_id optional
            'statutory_benefits' => 'required|string|max:255',
            'photo' => 'nullable|image|max:2048',
        ]);

        $employee = new Employee($validated);

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
            return redirect('login')->with('incorrect_msg', 'Incorrect Credentials. Login Unsuccessful.');
        }
    }

    public function Submit(Request $data)
    {
        $validated = $data->validate([
            'employee_id' => 'required|integer',
            'attendancestatus' => 'required',
            'date' => 'required|date',
            'check_in_time' => 'required',
        ]);
    
        $employeeId = $validated['employee_id'];
        $date = $validated['date'];
        $attendanceStatus = $validated['attendancestatus'];
    
        // Adjusting the time based on the 15-minute rule
        $checkInTime = Carbon::parse($validated['check_in_time']);
        $minute = $checkInTime->minute;
    
        if ($minute >= 45 || $minute <= 15) {
            // Round up to the nearest hour if >= 45 mins, or down if <= 15 mins
            $adjustedTime = $minute >= 45 
                ? $checkInTime->copy()->ceilHour() 
                : $checkInTime->copy()->floorHour();
        } else {
            // Keep the exact time if the minute is between 16 and 44
            $adjustedTime = $checkInTime;
        }
    
        $attendance = Attendance::where('employee_id', $employeeId)
            ->where('date', $date)
            ->first();
    
        if ($attendance) {
            if ($attendanceStatus === 'timeout') {
                $attendance->update([
                    'check_out_time' => now()->toTimeString(),
                ]);
                return back()->with('success', 'Time Out recorded successfully!');
            } else {
                return back()->with('error', 'Time In already exists for this employee on this date!');
            }
        } else {
            if ($attendanceStatus === 'timein') {
                Attendance::create([
                    'employee_id' => $employeeId,
                    'date' => $date,
                    'check_in_time' => $adjustedTime->toTimeString(),
                    'check_out_time' => null,
                ]);
                return back()->with('success', 'Time In recorded successfully!');
            } else {
                return back()->with('error', 'No Time In record found to Time Out!');
            }
        }
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

    
    public function updatePosition(Request $request, $id)
    {
        $validated = $request->validate([
            'position_name' => 'required|string|max:255',
            'rate_per_hour' => 'required|numeric',
        ]);

        $position = Position::where('position_id', $id)->first();
        if (!$position) {
            return response()->json(['success' => false, 'message' => 'Position not found.']);
        }

        $position->update($validated);
        return response()->json(['success' => true, 'message' => 'Position updated successfully.']);
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
        // Retrieve employee and position details
        $employee = Employee::find($employee_id);
        
        // Hourly rates
        $regular_rate = 54.75; // Rate per regular hour
        $overtime_rate = 68.44; // Rate per overtime hour
        
        // Retrieve the attendance for the employee
        $attendances = Attendance::where('employee_id', $employee_id)->get();
        
        $total_regular_hours = 0;
        $total_overtime_hours = 0;
        
        foreach ($attendances as $attendance) {
            // Check if both check-in and check-out times are available
            if ($attendance->check_in_time && $attendance->check_out_time) {
                // Convert check-in and check-out times to Carbon instances for easier calculation
                $check_in_time = Carbon::parse($attendance->check_in_time);
                $check_out_time = Carbon::parse($attendance->check_out_time);
        
                // If check-out is on the next day, adjust the date
                if ($check_out_time->lessThan($check_in_time)) {
                    $check_out_time->addDay();
                }
        
                // Calculate total hours worked for the day
                $hours_worked = $check_in_time->diffInHours($check_out_time);
        
                // Calculate regular and overtime hours
                if ($hours_worked > 8) {
                    $regular_hours = 8; // Regular hours are capped at 8
                    $overtime_hours = $hours_worked - 8; // Extra hours are overtime
                } else {
                    $regular_hours = $hours_worked; // All hours are regular if <= 8
                    $overtime_hours = 0; // No overtime
                }
        
                // Add to totals
                $total_regular_hours += $regular_hours;
                $total_overtime_hours += $overtime_hours;
            }
        }
        
        // Calculate total regular and overtime pay
        $total_regular_pay = $total_regular_hours * $regular_rate;
        $total_overtime_pay = $total_overtime_hours * $overtime_rate;
        
        // Calculate gross salary (before any deductions, just the total pay)
        $gross_salary = $total_regular_pay + $total_overtime_pay;
        $untouchgross = $gross_salary; // This is the untouchable gross salary
        
        // Now calculate the deductions, net salary etc., but not affecting untouchgross yet.
        $cash_advance = CashAdvance::where('employee_id', $employee_id)
                                   ->where('status', 'approved')
                                   ->sum('amount');
        
        // Subtract the approved cash advance from gross salary
        if ($cash_advance > 0) {
            $gross_salary -= $cash_advance;
        }
        
        // Retrieve statutory benefits for the employee
        $statutory_benefits = explode(',', $employee->statutory_benefits);
        
        $deduction_id = null;
        
        // Check for existing payroll
        $current_month = Carbon::now()->format('Y-m');
        $existing_payroll = Payroll::where('employee_id', $employee_id)
                                   ->whereRaw('DATE_FORMAT(created_at, "%Y-%m") = ?', [$current_month])
                                   ->first();
        
        if (!$existing_payroll) {
            foreach ($statutory_benefits as $benefit) {
                $benefit = trim($benefit);
                $deduction = Deduction::where('name', $benefit)->first();
                
                if ($deduction) {
                    $deduction_id = $deduction->deduction_id;
                    break;
                }
            }
        }
        
        // Calculate net salary after deductions
        $total_deductions = $deduction_id ? Deduction::find($deduction_id)->amount : 0;
        $net_salary = $gross_salary - $total_deductions;
        
        // Save payroll to the database
        $payroll = new Payroll();
        $payroll->employee_id = $employee_id;
        $payroll->gross_salary = $untouchgross;  // Save the untouchable gross salary here
        $payroll->deduction_id = $deduction_id;
        $payroll->net_salary = $net_salary;
        $payroll->save();
        
        return $payroll;
    }
}    