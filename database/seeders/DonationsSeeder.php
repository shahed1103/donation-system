<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Donation;

class DonationsSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 0; $i < 30; $i++) {
            Donation::create([
                'user_id' => rand(1, 5),
                'campaign_id' => rand(1, 11), 
                'amount' => rand(10, 500),
            ]);
        }
    }
}

