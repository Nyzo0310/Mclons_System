<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // Extends Authenticatable
use Illuminate\Notifications\Notifiable;

class user_table extends Authenticatable
{
    use Notifiable;

    protected $table = 'user_table'; // Ensure this matches your table name

    protected $fillable = [
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}

