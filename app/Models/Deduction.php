<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deduction extends Model
{
    use HasFactory;

    protected $primaryKey = 'deduction_id';

    protected $fillable = [
        'name',
        'amount',
    ];

    public function payrolls()
    {
        return $this->hasMany(Payroll::class, 'deduction_id');
    }
}

