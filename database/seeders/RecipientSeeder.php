<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recipient;

class RecipientSeeder extends Seeder
{
    public function run()
    {
        Recipient::factory()->count(10)->create();
    }
} 