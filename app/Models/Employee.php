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
        'photo',
        'schedule_id',
        'statutory_benefits',
    ];

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function schedule()
    {
        return $this->hasMany(Schedule::class, 'employee_id');
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class, 'employee_id');
    }

    public function cashAdvances()
    {
        return $this->hasMany(CashAdvance::class, 'employee_id');
    }

    public function overtimes()
    {
        return $this->hasMany(Overtime::class, 'employee_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
