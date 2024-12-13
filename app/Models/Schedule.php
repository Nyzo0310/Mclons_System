<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    // Explicitly set the table name
    protected $table = 'schedule';

    protected $primaryKey = 'schedule_id';

    protected $fillable = [
        'work_date',
        'start_time',
        'end_time',
    ];

    public $timestamps = true; // Ensure created_at and updated_at are managed

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
