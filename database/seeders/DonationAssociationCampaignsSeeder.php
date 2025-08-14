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
        $userIds = User::pluck('id')->toArray();
        $campaignIds = [1,2,3,4];

        if (empty($userIds) || empty($campaignIds)) {
            return;
        }

        for ($i = 0; $i < 4; $i++) {
            DonationAssociationCampaign::create([
                'user_id' => $userIds[array_rand($userIds)],
                'association_campaign_id' => $campaignIds[$i],
                'amount' => rand(500, 1000),
            ]);
        }
    }
}
