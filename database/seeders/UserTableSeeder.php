<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\user_table;

class UserTableSeeder extends Seeder
{
    public function run()
    {
        // Check if the user already exists, to avoid duplication
        user_table::firstOrCreate(
            ['username' => 'Admin'], // Replace 'defaultuser' with your desired username
            ['password' => Hash::make('123')] // Replace 'defaultpassword' with your desired default password
        );
    }
}
