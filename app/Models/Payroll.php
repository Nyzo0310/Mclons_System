<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $primaryKey = 'payroll_id';

    protected $fillable = [
        'employee_id',
        'start_date',
        'end_date',
        'regular_pay',
        'overtime_pay',
        'holiday_pay',
        'extra_2to4_pay',
        'gross_salary',
        'cash_advance',
        'deductions',
        'net_salary',
    ];

    /**
     * Relationship with Employee model.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
