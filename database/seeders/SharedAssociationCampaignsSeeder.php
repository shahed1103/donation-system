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
        $associationIds = Association::pluck('id')->toArray();
        $campaignIds = AssociationCampaign::pluck('id')->toArray();

        if (empty($associationIds) || empty($campaignIds)) {
            return;
        }

        for ($i = 0; $i < 10; $i++) {
            DB::table('shared_association_campaigns')->insert([
                'association_id' => $associationIds[array_rand($associationIds)],
                'association_campaign_id' => $campaignIds[array_rand($campaignIds)],
            ]);
        }
    }
}
