<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IndCompaign;
use Carbon\Carbon;

class IndividualCompaignsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $titles = [
            'Support for Cancer Treatment',
            'Books for Orphanage',
            'Neighborhood Cleanup',
            'Tree Planting Initiative',
            'Medical Aid for Refugees',
            'Educational Kits for Rural Kids',
            'Waste Collection Campaign',
            'Air Quality Monitoring',
            'Emergency Health Support',
            'Scholarships for Students',
            'Beach Cleaning Drive'
        ];

        $descriptions = [
            'Raising funds to help cancer patients afford chemotherapy.',
            'Providing educational books to an orphanage.',
            'Organizing a cleanup in our local neighborhood.',
            'Aiming to plant 500 trees in urban areas.',
            'Supplying basic medical needs to displaced families.',
            'Distributing school kits to children in remote villages.',
            'Improving sanitation through organized waste collection.',
            'Installing sensors to track pollution in the city.',
            'Immediate health support for critical cases.',
            'Helping students from poor families get access to higher education.',
            'Cleaning plastic waste and garbage from the beach.'
        ];

        $locations = [
            'Amman, Jordan',
            'Cairo, Egypt',
            'Riyadh, Saudi Arabia',
            'Beirut, Lebanon',
            'Irbid, Jordan',
            'Tunis, Tunisia',
            'Khartoum, Sudan',
            'Muscat, Oman',
            'Ramallah, Palestine',
            'Algiers, Algeria',
            'Jeddah, Saudi Arabia'
        ];

        $photoRanges = [
                1 => [1, 4],
                2 => [5, 8],
                3 => [9, 12],
                4 => [13, 16],
        ];

        $classification_ids = [1, 2, 3, 4 , 1, 2, 3, 4 , 1, 2, 3]; // healthy, Educational, cleanliness, environmental
        $acceptance_status_ids = [1, 2, 3 , 2 , 1, 2, 3 , 2 , 1, 2, 3]; // Under review, Approved, Rejected
        $campaign_status_ids = [2, 1, 2 , 3 , 2, 1, 2 , 3 , 2, 1, 2 ]; // Active, Closed, Complete
        $emergency_level = [1,2,2,5,3,2,3,5,4,1,4];
        $amount_required = [70000,50000,100000,20000,30000,20000,30000,50000,40000,100000,40000];


        for ($i = 0; $i < 11; $i++) {
        $startTime = null;
        $endTime = null;

        if ($acceptance_status_ids[$i] == 2) {
            $startTime = Carbon::now();
            $endTime = (clone $startTime)->addDays(rand(7, 30));
        }

            IndCompaign::query()->create([
                'title' => $titles[$i],
                'description' => $descriptions[$i],
                'classification_id' => $classification_ids[$i],
                'location' => $locations[$i],
                'amount_required' => $amount_required[$i],
                'user_id' => rand(1, 5),
                'photo_id' => rand(...$photoRanges[$classification_ids[$i]]),
                'acceptance_status_id' => $acceptance_status_ids[$i],
                'campaign_status_id' => $campaign_status_ids[$i],
                'compaigns_start_time' => $startTime,
                'compaigns_end_time' => $endTime,
                'compaigns_time' => rand(7, 30),
                'emergency_level' => $emergency_level[$i],

            ]);
        }
    }
}
