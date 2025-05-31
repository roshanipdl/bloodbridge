<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BloodRequest;

class BloodRequestSeeder extends Seeder
{
    public function run()
    {
        // Create 20 random blood requests
        BloodRequest::factory()->count(20)->create();

        // Create some specific test cases
        BloodRequest::factory()->critical()->bloodGroup('O-')->create(); // Critical O- request
        BloodRequest::factory()->urgent()->bloodGroup('AB+')->create();  // Urgent AB+ request
        BloodRequest::factory()->bloodGroup('A+')->create();             // Regular A+ request
    }
} 