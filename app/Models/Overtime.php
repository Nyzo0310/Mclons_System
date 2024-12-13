<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
    use HasFactory;

    protected $table = 'overtimes'; // Correct table name
    protected $primaryKey = 'overtime_id'; // Matches your table's primary key
    public $timestamps = true; // Ensures `created_at` and `updated_at` are used

    protected $fillable = [
        'Overtime_Type',
        'Rate_Per_Hour',
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'overtime_id');
    }
}
