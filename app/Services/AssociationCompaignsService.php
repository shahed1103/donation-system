<?php


namespace App\Services;

use App\Models\User;
use App\Models\IndCompaign;
use App\Models\Classification;
use App\Models\Association;
use App\Models\CampaignStatus;
use App\Models\DonationAssociationCampaign;
use App\Models\SharedAssociationCampaign;
use App\Models\AssociationCampaign;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Session;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use App\Http\Responses\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Throwable;
use Carbon\Carbon;

class AssociationCompaignsService
{

       // Get all active campaigns for a specific classification

      public function viewAssociationsCompaingsActive($id): array
      {
         $campaigns = AssociationCampaign::with(['classification', 'campaignStatus'])
            ->where('campaign_status_id', 1)
            ->where('classification_id', $id)
            ->get();

         $compaingAll = [];

         foreach ($campaigns as $campaign) {
            $totalDonations = DonationAssociationCampaign::where('association_campaign_id', $campaign->id)
                  ->sum('amount');

            $compaingAll[] = [
                  'id' =>  $campaign->id,
                  'title' => $campaign->title,
                  'amount_required' => $campaign->amount_required,
                  'donation_amount' => $totalDonations,
                  'campaign_status_id' => [
                     'id' => $campaign->campaign_status_id,
                     'campaign_status_type' => $campaign->campaignStatus->status_type
                  ],
                  'compaigns_time_to_end' => Carbon::now()->diff($campaign->compaigns_end_time)->format('%m Months %d Days %h Hours'),
            ];
         }
         $message = 'Your campaigns retrieved successfully';
         return ['associations Campaigns' => $compaingAll, 'message' => $message,
         ];
      }

      // Get all complete campaigns for a specific association
      public function viewAssociationCompaingsComplete($id): array
      {
         $campaignIds = SharedAssociationCampaign::where('association_id', $id)
            ->pluck('association_campaign_id');

         $campaigns = AssociationCampaign::with(['classification', 'campaignStatus'])
            ->whereIn('id', $campaignIds)
            ->where('campaign_status_id', 3)
            ->get();

         $compaingAll = [];

         foreach ($campaigns as $campaign) {
            $totalDonations = DonationAssociationCampaign::where('association_campaign_id', $campaign->id)
                  ->sum('amount');

            $compaingAll[] = [
                  'id' =>  $campaign->id,
                  'title' => $campaign->title,
                  'amount_required' => $campaign->amount_required,
                  'donation_amount' => $totalDonations,
                  'campaign_status_id' => [
                     'id' => $campaign->campaign_status_id,
                     'campaign_status_type' => $campaign->campaignStatus->status_type
                  ],
                  'compaigns_time_to_end' => Carbon::now()->diff($campaign->compaigns_end_time)->format('%m Months %d Days %h Hours'),
            ];
         }
            $message = 'Your campaign retrived sucessfully';
         return ['campaign' => $compaingAll, 'message' => $message];
      }

      //Get specific association details
      public function showAssociationDetails($id): array
      {
         $association = Association::findOrFail($id);

         $campaignIds = SharedAssociationCampaign::where('association_id', $id)
            ->pluck('association_campaign_id');

         $totalCampaigns = $campaignIds->count();

         $totalDonations = DonationAssociationCampaign::whereIn('association_campaign_id', $campaignIds)
            ->sum('amount');

         $completedCampaigns = $this->viewAssociationCompaingsComplete($id);

         $associationDet = [];

          $associationDet[] = [
            'association_name' => $association->name,
            'association_description' => $association->description,
            'total_donations' => $totalDonations,
            'total_campaigns' => $totalCampaigns,
            'completed_campaigns' => $completedCampaigns
            ];
            $message = 'association details are retrived sucessfully';

         return ['association' => $associationDet , 'message' => $message];
      }



      // Get association campaign details
      public function showCampaignDetails($campaignId)
      {
         $campaign = AssociationCampaign::with(['associations', 'campaignStatus', 'classification', 'donationAssociationCampaigns'])
                              ->findOrFail($campaignId);

         $totalDonations = $campaign->donationAssociationCampaigns->sum('amount');

         $lastDonation = $campaign->donationAssociationCampaigns->sortByDesc('created_at')->first();

         $compaingDet = [];
         $compaingDet[] = [
            'title' => $campaign->title,
            'description' => $campaign->description,
            'amount_required' => $campaign->amount_required,
            'donation_amount' => $totalDonations,
            'location' => $campaign->location ,
            'campaign_status' => [
                  'id' => $campaign->campaign_status_id,
                  'type' => $campaign->campaignStatus->status_type
            ],
            'classification' => [
                  'id' => $campaign->classification_id,
                  'type' => $campaign->classification->classification_name
            ],
            'campaign_time_to_end' => Carbon::now()->diff($campaign->compaigns_end_time)->format('%m Months %d Days %h Hours'),
            'campaign_start_time' => $campaign->compaigns_start_time,
            'campaign_end_time' => $campaign->compaigns_end_time,
            'last_donation_time' => $lastDonation ? $lastDonation->created_at->format('Y-m-d') : 'no Donations yet',
            //////////////////
            'associations' => $campaign->associations
               ->unique('id')
               ->values()
               ->map(function ($association) {
                  return [
                        'id' => $association->id,
                        'name' => $association->name,
                  ];
               }),
            //////////////////
         ];

         $message = 'association campaign details are retrived sucessfully';

         return ['campaign' => $compaingDet , 'message' => $message];
      }
}
