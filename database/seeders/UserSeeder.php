<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        $admin = User::create([
            'name' => 'Normal User',
            'email' => 'user@bloodbridge.com',
            'password' => Hash::make('password'), // You should change this in production
            'email_verified_at' => now(),
        ]);

        User::factory()->count(10)->create();
    }
} 