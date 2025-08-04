<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BloodRequest;
use App\Models\User;
use App\Models\Recipient;

class BloodRequestSeeder extends Seeder
{
    public function run()
    {
        // Get the three test users
        $users = User::where('email', 'user1@bloodbridge.com')
            ->orWhere('email', 'user2@bloodbridge.com')
            ->orWhere('email', 'user3@bloodbridge.com')
            ->get();

        // Create test blood requests for each user
        $recipient1 = Recipient::find(1);
        $recipient2 = Recipient::find(2);
        $recipient3 = Recipient::find(3);

        BloodRequest::create([
            'created_by' => $users[0]->id,
            'recipient_id' => 1,
            'recipient_name' => $recipient1->name,
            'status' => 'pending',
            'urgency_level' => 'normal',
            'hospital_name' => 'City Hospital',
            'units_required' => 2,
            'required_by_date' => now()->addDays(3),
            'notes' => 'Urgent need for O- blood type',
            'latitude' => 28.3949 + (rand(-5000, 5000) / 100000),
            'longitude' => 84.1240 + (rand(-5000, 5000) / 100000)
        ]);

        BloodRequest::create([
            'created_by' => $users[1]->id,
            'recipient_id' => 2,
            'recipient_name' => $recipient2->name,
            'status' => 'pending',
            'urgency_level' => 'urgent',
            'hospital_name' => 'Central Hospital',
            'units_required' => 3,
            'required_by_date' => now()->addDays(1),
            'notes' => 'Critical condition, needs immediate attention',
            'latitude' => 28.3949 + (rand(-5000, 5000) / 100000),
            'longitude' => 84.1240 + (rand(-5000, 5000) / 100000)
        ]);

        BloodRequest::create([
            'created_by' => $users[2]->id,
            'recipient_id' => 3,
            'recipient_name' => $recipient3->name,
            'status' => 'pending',
            'urgency_level' => 'critical',
            'hospital_name' => 'Emergency Hospital',
            'units_required' => 4,
            'required_by_date' => now()->addHours(24),
            'notes' => 'Emergency surgery, multiple units required',
            'latitude' => 28.3949 + (rand(-5000, 5000) / 100000),
            'longitude' => 84.1240 + (rand(-5000, 5000) / 100000)
        ]);

        // Create some additional test cases
        BloodRequest::create([
            'created_by' => $users[0]->id,
            'recipient_id' => 1,
            'recipient_name' => $recipient1->name,
            'status' => 'pending',
            'urgency_level' => 'normal',
            'hospital_name' => 'City Hospital',
            'units_required' => 1,
            'required_by_date' => now()->addDays(5),
            'notes' => 'Routine transfusion',
            'latitude' => 28.3949 + (rand(-5000, 5000) / 100000),
            'longitude' => 84.1240 + (rand(-5000, 5000) / 100000)
        ]);

        BloodRequest::create([
            'created_by' => $users[1]->id,
            'recipient_id' => 2,
            'recipient_name' => $recipient2->name,
            'status' => 'pending',
            'urgency_level' => 'urgent',
            'hospital_name' => 'Central Hospital',
            'units_required' => 2,
            'required_by_date' => now()->addDays(2),
            'notes' => 'Emergency surgery',
            'latitude' => 28.3949 + (rand(-5000, 5000) / 100000),
            'longitude' => 84.1240 + (rand(-5000, 5000) / 100000)
        ]);

        BloodRequest::create([
            'created_by' => $users[2]->id,
            'recipient_id' => 3,
            'recipient_name' => $recipient3->name,
            'status' => 'pending',
            'urgency_level' => 'critical',
            'hospital_name' => 'Emergency Hospital',
            'units_required' => 3,
            'required_by_date' => now()->addHours(12),
            'notes' => 'Severe trauma patient',
            'latitude' => 28.3949 + (rand(-5000, 5000) / 100000),
            'longitude' => 84.1240 + (rand(-5000, 5000) / 100000)
        ]);
    }
}