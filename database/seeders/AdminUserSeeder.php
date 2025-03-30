<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@bloodbridge.com',
            'password' => Hash::make('password'), // You should change this in production
            'email_verified_at' => now(),
        ]);
        
        $admin->assignRole('Administrator');
    }
}
