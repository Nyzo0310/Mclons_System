<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $primaryKey = 'attendance_id';

    protected $fillable = [
        'employee_id',
        'schedule_id',
        'check_in_time',
        'check_out_time',
        'holiday_id',
        'overtime_id',
    ];

    // Relationship with Employee
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    // Relationship with Schedule
    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id', 'schedule_id');
    }

    // Relationship with Holiday
    public function holiday()
    {
        return $this->belongsTo(Holiday::class, 'holiday_id', 'holiday_id');
    }

    // Relationship with Overtime
    public function overtime()
    {
        return $this->belongsTo(Overtime::class, 'overtime_id', 'overtime_id');
    }
}
