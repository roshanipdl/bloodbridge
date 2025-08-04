<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recipient;
use App\Models\User;

class RecipientSeeder extends Seeder
{
    public function run()
    {
        // Get the three test users
        $users = User::where('email', 'user1@bloodbridge.com')
            ->orWhere('email', 'user2@bloodbridge.com')
            ->orWhere('email', 'user3@bloodbridge.com')
            ->get();

        // Create test recipients for each user
        Recipient::create([
            'user_id' => $users[0]->id,
            'name' => 'Patient One',
            'blood_group' => 'O-',
            'contact' => '+1234567890',
        ]);

        Recipient::create([
            'user_id' => $users[1]->id,
            'name' => 'Patient Two',
            'blood_group' => 'AB+',
            'contact' => '+0987654321',
        ]);

        Recipient::create([
            'user_id' => $users[2]->id,
            'name' => 'Patient Three',
            'blood_group' => 'A+',
            'contact' => '+1122334455',
        ]);
    }
} 