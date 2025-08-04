<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Normal User',
            'email' => 'user1@bloodbridge.com',
            'password' => Hash::make('password'), // You should change this in production
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Normal User',
            'email' => 'user2@bloodbridge.com',
            'password' => Hash::make('password'), // You should change this in production
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Normal User',
            'email' => 'user3@bloodbridge.com',
            'password' => Hash::make('password'), // You should change this in production
            'email_verified_at' => now(),
        ]);
    }
} 