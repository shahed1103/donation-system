<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Association;
use App\Models\AssociationCampaign;

class SharedAssociationCampaignsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $associationIds = [1,1,1,1, 1,1,1,1 , 1,1,1,
                            2,2,2,2, 3,3,3,3 , 4,4,4,
                             5,5,5,5,  6,6,6,6 , 7,7,7];

        $campaignIds = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,
                        20,21,22,23,24,25,26,27,28,29,30,31,32,33];

        if (empty($associationIds) || empty($campaignIds)) {
            return;
        }

        for ($i = 0; $i < 33; $i++) {
            DB::table('shared_association_campaigns')->insert([

                'association_id' => $associationIds[array_rand($associationIds)],
                'association_campaign_id' => $campaignIds[array_rand($campaignIds)],
            ]);
        }
    }
}
