<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $table = 'holidays'; // Explicit table name
    protected $primaryKey = 'holiday_id'; // Use 'holiday_id' as the primary key
    public $incrementing = true; // Ensure it's auto-incrementing
    protected $fillable = ['description', 'holiday_date'];
}
