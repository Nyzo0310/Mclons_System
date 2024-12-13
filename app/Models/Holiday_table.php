<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $primaryKey = 'holiday_id';

    protected $fillable = [
        'holiday_date',
        'description',
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'holiday_id');
    }
}
