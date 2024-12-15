<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $primaryKey = 'employee_id';
    protected $table = 'employees';

    protected $fillable = [
        'first_name',
        'last_name',
        'address',
        'birthdate',
        'contact_no',
        'gender',
        'position_id',
        'Schedule',
        'photo',
        'schedule_id',
        'statutory_benefits',
    ];

    // Relationship with Deduction
    public function deduction()
    {
        return $this->belongsTo(Deduction::class, 'statutory_benefits', 'deduction_id');
    }

    // Relationship with Position
    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function schedule()
{
    return $this->belongsTo(Schedule::class, 'schedule_id', 'schedule_id');
}


    // Relationship with Payroll
    public function payrolls()
    {
        return $this->hasMany(Payroll::class, 'employee_id');
    }

    // Relationship with Cash Advance
    public function cashAdvances()
    {
        return $this->hasMany(CashAdvance::class, 'employee_id');
    }

    // Relationship with Overtime
    public function overtimes()
    {
        return $this->hasMany(Overtime::class, 'employee_id');
    }

    // Relationship with Attendance
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'employee_id');
    }
}
