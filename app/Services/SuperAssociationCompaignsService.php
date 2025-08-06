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

class SuperAssociationCompaignsService
{


public function getAssociations(): array
{
        $associations = Association::select('id', 'name')->get()
            ->map(function ($association) {
                return [
                    'id'   => $association->id,
                    'name' => $association->name, ]; });
        return [
            'associations' => $associations,
            'message' => 'done' ];
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
            ]; }
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


public function getCampaignDetails($campaignId): array
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
         $remainingAmount = max($campaign->amount_required - $totalDonations, 0);

         $compaingDet = [];
         $compaingDet[] = [
            'title' => $campaign->title,
            'description' => $campaign->description,
            'amount_required' => $campaign->amount_required,
            'donation_amount' => $totalDonations,
            'remaining_amount' => $remainingAmount,
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
            'associations' => $campaign->associations
               ->unique('id')
               ->values()
               ->map(function ($association) {
                  return [
                        'id' => $association->id,
                        'name' => $association->name,
                  ];
               }),
         ];

         $message = 'association campaign details are retrived sucessfully';

         return ['campaign' => $compaingDet , 'message' => $message];
      }


 public function getAssociationDetails($id): array
      {
         $association = Association::findOrFail($id);

         $campaignIds = SharedAssociationCampaign::where('association_id', $id)
            ->pluck('association_campaign_id');

         $totalCampaigns = $campaignIds->count();

         $totalDonations = DonationAssociationCampaign::whereIn('association_campaign_id', $campaignIds)
            ->sum('amount');

         $completedCampaigns = $this->getAssociationCompaingsComplete($id);
         $closedCampaigns = $this->getAssociationCompaingsClosed($id);
         $activeCampaigns = $this->getAssociationsCampaignsActive($id);
         $association_owner = User::find($association->association_owner_id);
         $associationDet = [];

        $associationDet[] = [
            'association_name' => $association->name,
            'association_description' => $association->description,
            'location' => $association->location,
            'association_owner' => $association_owner->name,
            'date_start_working' => $association -> date_start_working,
            'date_end_working' => $association -> date_end_working,
            'total_donations' => $totalDonations,
            'closed_campaigns' => $closedCampaigns,
            'completed_campaigns' => $completedCampaigns,
            'active_campaigns' => $activeCampaigns
            ];
            $message = 'association details are retrived sucessfully';

         return ['association' => $associationDet , 'message' => $message];
      }

public function addAssociation($request): array{

        $adminRole = Role::query()->firstWhere('name', 'Admin')->id;
        $association_owner = User::create([
         'name' =>$request['owner_name'],
         'email' => $request ['email'],
         'password' => Hash::make($request['password']),
         'role_id' => $adminRole
        ]);

        $association = Association::create([
                'name' =>  $request['name'],
                'description' => $request['description'],
                'location' => $request['location'],
                'date_start_working' =>  $request['date_start_working'],
                'date_end_working' => $request['date_end_working'],
                'compaigns_time' =>  $request['compaigns_time'],
                'association_owner_id' => $association_owner -> id
       ]);

       $association->refresh();
       $association_dett = [
                'name' =>  $request['name'],
                'description' => $request['description'],
                'location' => $request['location'],
                'date_start_working' =>  $request['date_start_working'],
                'date_end_working' => $request['date_end_working'],
                'compaigns_time' =>  $request['compaigns_time'],
                'association_owner' => $association_owner->name,
       ];
        $message = 'Your association created sucessfully';

        return ['association' =>  $association_dett , 'message' => $message];
    }


    }
