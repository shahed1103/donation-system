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


      /////////////individual
public function getActiveIndiviCompaign(): array
{
    $activeCampaignIds = CampaignStatus::where('status_type', 'Active')
        ->pluck('id');

    $campaigns = IndCompaign::with(['classification', 'campaignStatus', 'photo'])
        ->whereIn('campaign_status_id', $activeCampaignIds)
        ->get();

    $campaignAll = [];

    foreach ($campaigns as $campaign) {
        $totalDonations = Donation::where('campaign_id', $campaign->id)->sum('amount');

        $photoUrl = $campaign->photo ? url(Storage::url($campaign->photo->photo)) : null;

        $campaignAll[] = [
            'id' => $campaign->id,
            'title' => $campaign->title,
            'amount_required' => $campaign->amount_required,
            'donation_amount' => $totalDonations,
            'campaign_status_id' => [
                'id' => $campaign->campaign_status_id,
                'campaign_status_type' => optional($campaign->campaignStatus)->status_type,
            ],
            'photo_id' => [
                'id' => $campaign->photo_id,
                'photo' => $photoUrl,
            ],
            'compaigns_time_to_end' => Carbon::now()->diff($campaign->compaigns_end_time)->format('%m Months %d Days %h Hours'),
        ];
    }

    return [
        'campaign' => $campaignAll,
        'message' => 'All active campaigns retrieved successfully',
    ];
}





public function getCompleteIndiviCompaign($id): array{
        $campaigns = IndCompaign::where('classification_id' , $id)->get();
        $compaingAll = [];
        foreach ($campaigns as $compaign) {
                $classification_name = Classification::find($compaign->classification_id)->classification_name;
                $campaign_status_type = CampaignStatus::find($compaign->campaign_status_id)->status_type;
                $photo = IndCompaigns_photo::find($compaign->photo_id)->photo;

                $fullPath = url(Storage::url($photo));

        if($campaign_status_type === "Complete"){
             $campaign_ids = Donation::where('campaign_id' , $compaign->id)->get();
             $total  = 0;
             foreach ($campaign_ids as $campaign_id) {
                $total += $campaign_id->amount;
             }

        $compaingAll [] =
        [
        'id' =>  $compaign->id,
        'title' =>  $compaign->title,
        'amount_required' =>  $compaign->amount_required,
        'donation_amount' => $total,
        'campaign_status_id'=>  ['id' => $compaign->campaign_status_id, 'campaign_status_type' => $campaign_status_type],
        'photo_id' => ['id' =>$compaign->photo_id , 'photo' =>$fullPath],
        'compaigns_time_to_end' => Carbon::now()->diff($compaign->compaigns_end_time)->format('%m Months %d Days %h Hours'),
        ];
        }}

        $message = 'Your campaign retrived sucessfully';

        return ['campaign' =>   $compaingAll , 'message' => $message];
     }


public function getClosedPendingIndiviCampaigns($id): array
{
    $campaigns = IndCompaign::where('classification_id', $id)
        ->whereHas('campaignStatus', function ($query) {
            $query->where('status_type', 'Closed');
        })
        ->whereHas('acceptanceStatus', function ($query) {
            $query->where('status_type', 'Under review');
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
        'message' => 'Closed and pending (under review) campaigns retrieved successfully',
    ];
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

