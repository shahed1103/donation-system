<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CampaignStatus;

class CampaignStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $status = ['Active' , 'Closed' , 'Complete'];

        for ($i=0; $i < 3 ; $i++) {
            CampaignStatus::query()->create([
           'status_type' => $status[$i] ,
            ]); }
    }
}
