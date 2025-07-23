<?php


namespace App\Services;

use App\Models\User;
use App\Models\IndCompaign;
use App\Models\Classification;
use App\Models\Association;
use App\Models\CampaignStatus;
use App\Models\DonationAssociationCampaign;
use App\Models\Donation;
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
                  // 'compaigns_time_to_end' => Carbon::now()->diff($campaign->compaigns_end_time)->format('%m Months %d Days %h Hours'),
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


      //search campigns
public function searchCampaigns(Request $request)
{
    $campaigns = [];

    if($request->has('association_name')){
    $associations = Association::where('name', 'like', '%' . $request['association_name'] . '%')->get();

    foreach ($associations as $association) {
        $activeCampaigns = $association->associationCampaigns()
                                       ->where('campaign_status_id', 1)
                                       ->get();
        $campaigns = $activeCampaigns;
    }

         $message = 'this association campaigns are retrived sucessfully';

         return ['campaign' => $campaigns , 'message' => $message];
   }

   if($request->has('classification_name')){
    $classification = Classification::where('classification_name', 'like', '%' . $request['classification_name'] . '%')->first();

    if ($classification) {
        $assocCampaignsByClass = AssociationCampaign::where('classification_id', $classification->id)
                                                      ->where('campaign_status_id', 1)
                                                      ->get();



        foreach ($assocCampaignsByClass as $assocCampaignsByClas) {
        $totalDonations = DonationAssociationCampaign::where('association_campaign_id', $assocCampaignsByClas->id)
                                                      ->sum('amount');
        $campaigns [] = [
                  'id' =>  $assocCampaignsByClas->id,
                  'title' => $assocCampaignsByClas->title,
                  'amount_required' => $assocCampaignsByClas->amount_required,
                  'donation_amount' => $totalDonations,
                  'campaign_status_id' => [
                     'id' => $assocCampaignsByClas->campaign_status_id,
                     'campaign_status_type' => $assocCampaignsByClas->campaignStatus->status_type
                  ],
                  'compaigns_time_to_end' => Carbon::now()->diff($assocCampaignsByClas->compaigns_end_time)->format('%m Months %d Days %h Hours'),
               ];
            }
      //   $assocCampaignsByClass;

        $individualCampaigns = IndCompaign::where('classification_id', $classification->id)
                                                   ->where('campaign_status_id', 1)
                                                   ->get();

        foreach ($individualCampaigns as $individualCampaign) {
         $totalDonations = Donation::where('campaign_id', $individualCampaign->id)
                                                      ->sum('amount');
        $campaigns [] = [
                  'id' =>  $individualCampaign->id,
                  'title' => $individualCampaign->title,
                  'amount_required' => $individualCampaign->amount_required,
                  'donation_amount' => $totalDonations,
                  'campaign_status_id' => [
                     'id' => $individualCampaign->campaign_status_id,
                     'campaign_status_type' => $individualCampaign->campaignStatus->status_type
                  ],
                  'compaigns_time_to_end' => Carbon::now()->diff($individualCampaign->compaigns_end_time)->format('%m Months %d Days %h Hours'),
               ];
            }
    }
             $message = 'campaigns with this classification are retrived sucessfully';

         return ['campaign' => $campaigns , 'message' => $message];
   }

         $message = 'there is no campigns' ;

         return ['campaign' => $campaigns , 'message' => $message];

}


}
