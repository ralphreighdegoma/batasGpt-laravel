<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::create([
            'name' => 'Admin User', // Replace with the admin name
            'email' => 'admin@gmail.com', // Replace with admin email
            'password' => Hash::make('password'), // Replace with a secure password
        ]);
      
    }
}
