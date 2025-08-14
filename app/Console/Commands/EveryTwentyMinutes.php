<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\IndCompaign;
use App\Models\InkindDonation;
use App\Models\AssociationCampaign;
use App\Models\InkindDonationReservation;

class EveryTwentyMinutes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom:every-twenty';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update every twentyMinutes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
    IndCompaign::all()->each(function ($campaign) {
        $campaign->updateStatus('individual');
    });

    AssociationCampaign::all()->each(function ($campaign) {
        $campaign->updateStatus('association');
    });

    InkindDonation::all()->each(function ($inkindDonation) {
        $inkindDonation->updateinkindDonations();
    });

    InkindDonationReservation::all()->each(function ($inkindDonationReservation) {
        $inkindDonationReservation->updateinkindDonationsReservation();
    });
    
    }
}
