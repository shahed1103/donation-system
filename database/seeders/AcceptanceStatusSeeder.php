<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AcceptanceStatus;

class AcceptanceStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                $status = ['Under review' , 'Approved' , 'Rejected'];

        for ($i=0; $i < 3 ; $i++) {
            AcceptanceStatus::query()->create([
           'status_type' => $status[$i] ,
            ]); }
    }


public function getClosedRejectedIndiviCampaigns($id): array
{
    $campaigns = IndCompaign::where('classification_id', $id)
        ->whereHas('campaignStatus', function ($query) {
            $query->where('status_type', 'Closed');
        })
        ->whereHas('acceptanceStatus', function ($query) {
            $query->where('status_type', 'Rejected');
        })
        ->get();

    $campaignAll = [];

    foreach ($campaigns as $campaign) {
        $classification_name = optional($campaign->classification)->classification_name;
        $campaign_status_type = optional($campaign->campaignStatus)->status_type;
        $acceptance_status_type = optional($campaign->acceptanceStatus)->status_type;

        $photo = optional($campaign->photo)->photo;
        $fullPath = url(Storage::url($photo));

        $total = Donation::where('campaign_id', $campaign->id)->sum('amount');

        $campaignAll[] = [
            'id' => $campaign->id,
            'title' => $campaign->title,
            'amount_required' => $campaign->amount_required,
            'donation_amount' => $total,
            'campaign_status' => [
                'id' => $campaign->campaign_status_id,
                'type' => $campaign_status_type,
            ],
            'acceptance_status' => [
                'id' => $campaign->acceptance_status_id,
                'type' => $acceptance_status_type,
            ],
            'photo' => [
                'id' => $campaign->photo_id,
                'url' => $fullPath,
            ],
            'compaigns_time_to_end' => Carbon::now()->diff($campaign->compaigns_end_time)->format('%m Months %d Days %h Hours'),
        ];
    }

    return [
        'campaigns' => $campaignAll,
        'message' => 'Closed and Rejected campaigns retrieved successfully',
    ];
}



}
