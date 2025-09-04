<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DonationAssociationCampaign;
use App\Models\User;
use App\Models\AssociationCampaign;
use Illuminate\Support\Facades\DB;

class DonationAssociationCampaignsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        $campaign_id = [2,6,10,13,17,21,24,28,32 , 4,8,15,19,26,30  , 2,6,10,13,17,21,24,28,32 , 4,8,15,19,26,30,

                2,6,10,13,17,21,24,28,32 , 2,6,10,13,17,21,24,28,32 , 4,8,15,19,26,30];

          $amount = [20000,5000,30000,10000,3500,80000,10000,3000,20000 , 5000,573,2000,8000,2000,1900  ,
           10000,10000,40000,15000,1500,10000,15000,7000,40000 , 1000,8827,7000,2000,200,3040,

                15000,2000,10000,20000,5000,5000,10000,6000,30000 ,
                5000,3000,20000,5000,10000,5000,15000,4000,10000 , 4000,10000,4000,500,100,8712];


        for ($i = 0; $i < 54; $i++) {
            DonationAssociationCampaign::create([
                'user_id' => rand(3, 13),
                'association_campaign_id' => $campaign_id[$i],
                'amount' => $amount[$i],
            ]);
        }
    }
}
