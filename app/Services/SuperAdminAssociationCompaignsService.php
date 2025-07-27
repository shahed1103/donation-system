<?php
namespace App\Services;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Session;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\Association;
use App\Models\User;
use App\Models\Donation;
use App\Models\IndCompaign;

use App\Models\Classification;
use App\Models\AcceptanceStatus;
use App\Models\CampaignStatus;
use App\Models\IndCompaigns_photo;

use App\Models\SharedAssociationCampaign;
use App\Models\DonationAssociationCampaign;

use App\Models\AssociationCampaign;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use App\Http\Responses\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Throwable;
use Storage;

class SuperAdminCompaignsService
{

    ///////////////association
public function getAssociations(): array
{
    try {
        $associations = Association::select('id', 'name')->get()
            ->map(function ($association) {
                return [
                    'id'   => $association->id,
                    'name' => $association->name,
                ];
            });

        return [
            'associations' => $associations,
            'message' => 'done'
        ];
    } catch (Throwable $th) {
        throw $th;
    }
}

public function getAssociationsCampaignsActive($association_id): array
{

         $campaignIds = SharedAssociationCampaign::where('association_id', $association_id)
            ->pluck('association_campaign_id');

         $campaigns = AssociationCampaign::with(['classification', 'campaignStatus'])
            ->whereIn('id', $campaignIds)
            ->where('campaign_status_id', 1)
            ->get();

         $compaingAll = [];

         foreach ($campaigns as $campaign) {
            $totalDonations = DonationAssociationCampaign::where('association_campaign_id', $campaign->id)
                  ->sum('amount');

            $compaingAll[] = [
                  'id' =>  $campaign->association_id,
                  'title' => $campaign->title,
                  'photo' => url(Storage::url($campaign->photo)),
                  'amount_required' => $campaign->amount_required,
                  'donation_amount' => $totalDonations,
                  'campaign_status_id' => [
                     'id' => $campaign->campaign_status_id,
                     'campaign_status_type' => $campaign->campaignStatus->status_type
                  ],
                  // 'compaigns_time_to_end' => Carbon::now()->diff($campaign->compaigns_end_time)->format('%m Months %d Days %h Hours'),
            ];
         }
            $message = 'Your campaign retrived sucessfully';
         return ['campaign' => $compaingAll, 'message' => $message];
}

public function getAssociationCompaingsComplete($association_id): array
      {
         $campaignIds = SharedAssociationCampaign::where('association_id', $association_id)
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
                  'id' =>  $campaign->association_id,
                  'title' => $campaign->title,
                  'photo' => url(Storage::url($campaign->photo)),
                  'amount_required' => $campaign->amount_required,
                  'donation_amount' => $totalDonations,
                  'campaign_status_id' => [
                     'id' => $campaign->campaign_status_id,
                     'campaign_status_type' => $campaign->campaignStatus->status_type
                  ],
                  // 'compaigns_time_to_end' => Carbon::now()->diff($campaign->compaigns_end_time)->format('%m Months %d Days %h Hours'),
            ];
         }
            $message = 'Your campaign retrived sucessfully';
         return ['campaign' => $compaingAll, 'message' => $message];
      }


public function getAssociationCompaingsClosed($association_id): array
      {
         $campaignIds = SharedAssociationCampaign::where('association_id', $association_id)
            ->pluck('association_campaign_id');

         $campaigns = AssociationCampaign::with(['classification', 'campaignStatus'])
            ->whereIn('id', $campaignIds)
            ->where('campaign_status_id', 2)
            ->get();

         $compaingAll = [];

         foreach ($campaigns as $campaign) {
            $totalDonations = DonationAssociationCampaign::where('association_campaign_id', $campaign->id)
                  ->sum('amount');

            $compaingAll[] = [
                  'id' =>  $campaign->association_id,
                  'title' => $campaign->title,
                  'photo' => url(Storage::url($campaign->photo)),
                  'amount_required' => $campaign->amount_required,
                  'donation_amount' => $totalDonations,
                  'campaign_status_id' => [
                     'id' => $campaign->campaign_status_id,
                     'campaign_status_type' => $campaign->campaignStatus->status_type
                  ],
                  // 'compaigns_time_to_end' => Carbon::now()->diff($campaign->compaigns_end_time)->format('%m Months %d Days %h Hours'),
            ];
         }
            $message = 'Your campaign retrived sucessfully';
         return ['campaign' => $compaingAll, 'message' => $message];
      }


public function showCampaignDetails($campaignId): array
      {
         $campaign = AssociationCampaign::with(['associations', 'campaignStatus', 'classification', 'donationAssociationCampaigns'])
                              ->findOrFail($campaignId);

         $totalDonations = $campaign->donationAssociationCampaigns->sum('amount');

         $lastDonation = $campaign->donationAssociationCampaigns->sortByDesc('created_at')->first();

         $totalDonors = $campaign->donationAssociationCampaigns()
                                 ->distinct('user_id')
                                 ->count('user_id');
         $totalDonors = $campaign->donationAssociationCampaigns()
                        ->distinct('user_id')
                        ->count('user_id');

         $compaingDet = [];
         $compaingDet[] = [
            'title' => $campaign->title,
            'description' => $campaign->description,
            'amount_required' => $campaign->amount_required,
            'donation_amount' => $totalDonations,
            'location' => $campaign->location ,
            'photo' => url(Storage::url($campaign->photo)),
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
            'last_donation_time' => $lastDonation ? $lastDonation->created_at->diffForHumans() : 'no Donations yet',
            'totalDonors' => $totalDonors,
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
