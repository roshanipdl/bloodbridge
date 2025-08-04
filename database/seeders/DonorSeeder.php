<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Donor;
use App\Models\User;

class DonorSeeder extends Seeder
{
    public function run()
    {
        // Get the three test users
        $users = User::where('email', 'user1@bloodbridge.com')
            ->orWhere('email', 'user2@bloodbridge.com')
            ->orWhere('email', 'user3@bloodbridge.com')
            ->get();

        // Create test donors for each user
        Donor::create([
            'user_id' => $users[0]->id,
            'name' => 'John Doe',
            'blood_type' => 'O+',
            'contact' => '+1234567890',
            'latitude' => 28.3949 + (rand(-5000, 5000) / 100000),
            'longitude' => 84.1240 + (rand(-5000, 5000) / 100000),
            'is_available' => true,
            'health_status' => 'good'
        ]);

        Donor::create([
            'user_id' => $users[1]->id,
            'name' => 'Jane Smith',
            'blood_type' => 'A-',
            'contact' => '+0987654321',
            'latitude' => 28.3949 + (rand(-5000, 5000) / 100000),
            'longitude' => 84.1240 + (rand(-5000, 5000) / 100000),
            'is_available' => true,
            'health_status' => 'good'
        ]);

        Donor::create([
            'user_id' => $users[2]->id,
            'name' => 'Mike Johnson',
            'blood_type' => 'B+',
            'contact' => '+1122334455',
            'latitude' => 28.3949 + (rand(-5000, 5000) / 100000),
            'longitude' => 84.1240 + (rand(-5000, 5000) / 100000),
            'is_available' => true,
            'health_status' => 'good'
        ]);
    }
}