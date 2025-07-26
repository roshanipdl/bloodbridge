<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Donor;

class DonorSeeder extends Seeder
{
    public function run()
    {
        Donor::factory()->count(10)->create();
    }
} 