<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Donation;

class DonationsSeeder extends Seeder
{
    public function run(): void
    {//8 50000
        $campaign_id = [1,2,2,4,1,6,4,8,9,10,9];
        $amount = [1000 , 1000 , 1100 , 10000 , 1000 , 2000 , 10000 , 50000 , 2000 , 1000 , 3000];
        for ($i = 0; $i < 11; $i++) {
            Donation::create([
                'user_id' => rand(1, 5),
                'campaign_id' =>  $campaign_id[$i], 
                'amount' => $amount[$i],
            ]);
        }
    }
}

