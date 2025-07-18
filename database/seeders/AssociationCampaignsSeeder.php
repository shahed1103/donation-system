<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AssociationCampaign;
use Carbon\Carbon;

class AssociationCampaignsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $titles = [
            'Health Support Initiative',
            'Educational Supplies Drive',
            'Neighborhood Clean-up',
            'Tree Planting Campaign',
        ];

        $descriptions = [
            'Providing medical aid to underserved communities.',
            'Supplying books and materials to low-income students.',
            'Organizing a community-wide cleaning event.',
            'Planting trees to improve air quality and environment.',
        ];

        $locations = [
            'Riyadh', 'Jeddah', 'Dammam', 'Mecca',
        ];

        $amounts = [5000, 3000, 2000, 4000];

        $classificationIds = [1, 1, 3, 4]; 
        $statusIds = [1, 2, 3 , 1]; 

        for ($i = 0; $i < 4; $i++) {
            AssociationCampaign::create([
                'classification_id' => $classificationIds[$i],
                'title' => $titles[$i],
                'description' => $descriptions[$i],
                'location' => $locations[$i],
                'amount_required' => $amounts[$i],
                'campaign_status_id' => $statusIds[$i],
                'compaigns_start_time' => Carbon::now()->subDays(rand(1, 10)),
                'compaigns_end_time' => Carbon::now()->addDays(rand(10, 30)),
            ]);
        }
    }
}
