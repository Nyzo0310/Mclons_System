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
        'date',
        'check_in_time',
        'check_out_time',
        'holiday_id',
        'overtime_id',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function holiday()
    {
        return $this->belongsTo(Holiday::class, 'holiday_id');
    }

    public function overtime()
    {
        return $this->belongsTo(Overtime::class, 'overtime_id');
    }
}
