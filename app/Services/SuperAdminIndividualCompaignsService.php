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
use App\Models\Leader_form;


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
use App\Traits\GetUnderReviewIndiviCampaignDetailsMain;


class SuperAdminIndividualCompaignsService
{
    use GetUnderReviewIndiviCampaignDetailsMain;

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
            'classification_id' => [
                'id' => $campaign->classification_id,
                 'campaign_classification' => optional($campaign->classification)->classification_name,
            ],
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
            'compaigns_time_to_end' => Carbon::now()->diff($campaign->compaigns_end_time)->format('%d'),
        ];
    }

    return [
        'campaign' => $campaignAll,
        'message' => 'All active campaigns retrieved successfully',
    ];
}


public function getCompleteIndiviCompaign(): array
{
    $completeCampaignIds = CampaignStatus::where('status_type', 'Complete')
        ->pluck('id');
    $campaigns = IndCompaign::with(['classification', 'campaignStatus', 'photo'])
        ->whereIn('campaign_status_id', $completeCampaignIds)
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
            'classification_id' => [
                'id' => $campaign->classification_id,
                 'campaign_classification' => optional($campaign->classification)->classification_name,
            ],
            'campaign_status_id' => [
                'id' => $campaign->campaign_status_id,
                'campaign_status_type' => optional($campaign->campaignStatus)->status_type,
            ],
            'photo_id' => [
                'id' => $campaign->photo_id,
                'photo' => $photoUrl,
            ],
            'compaigns_time_to_end' => Carbon::now()->diff($campaign->compaigns_end_time)->format('%d'),
        ];
    }

    return [
        'campaign' => $campaignAll,
        'message' => 'All complete campaigns retrieved successfully',
    ];
}


public function getClosedRejectedIndiviCampaigns(): array
{
    $closedStatusId = CampaignStatus::where('status_type', 'Closed')->pluck('id');
    $rejectedStatusId = AcceptanceStatus::where('status_type', 'Rejected')->pluck('id');

    $campaigns = IndCompaign::with(['classification', 'campaignStatus', 'acceptanceStatus', 'photo'])
        ->whereIn('campaign_status_id', $closedStatusId)
        ->whereIn('acceptance_status_id', $rejectedStatusId)
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
            'classification_id' => [
                'id' => $campaign->classification_id,
                 'campaign_classification' => optional($campaign->classification)->classification_name,
            ],
            'campaign_status_id' => [
                'id' => $campaign->campaign_status_id,
                'campaign_status_type' => optional($campaign->campaignStatus)->status_type,
            ],
            'acceptance_status_id' => [
                'id' => $campaign->acceptance_status_id,
                'acceptance_status_type' => optional($campaign->acceptanceStatus)->status_type,
            ],
            'photo_id' => [
                'id' => $campaign->photo_id,
                'photo' => $photoUrl,
            ],
            'compaigns_time_to_end' => Carbon::now()->diff($campaign->compaigns_end_time)->format('%d'),
        ];
    }

    return [
        'campaign' => $campaignAll,
        'message' => 'All closed and rejected campaigns retrieved successfully',
    ];
}


public function getClosedUnderReviewIndiviCompaign(): array
{
    $closedStatusId = CampaignStatus::where('status_type', 'Closed')->pluck('id');
    $underReviewStatusId = AcceptanceStatus::where('status_type', 'Under review')->pluck('id');

    $campaigns = IndCompaign::with(['classification', 'campaignStatus', 'acceptanceStatus', 'photo'])
        ->whereIn('campaign_status_id', $closedStatusId)
        ->whereIn('acceptance_status_id', $underReviewStatusId)
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
            'classification_id' => [
                'id' => $campaign->classification_id,
                 'campaign_classification' => optional($campaign->classification)->classification_name,
            ],
            'acceptance_status_id' => [
                'id' => $campaign->acceptance_status_id,
                'acceptance_status_type' => optional($campaign->acceptanceStatus)->status_type,
            ],
            'photo_id' => [
                'id' => $campaign->photo_id,
                'photo' => $photoUrl,
            ],
            'compaigns_time_to_end' => Carbon::now()->diff($campaign->compaigns_end_time)->format('%d'),
        ];
    }

    return [
        'campaign' => $campaignAll,
        'message' => 'All closed and under review campaigns retrieved successfully',
    ];
}


public function getActiveCompleteIndiviCampaignDetails($campaignId):array{
    $compaign = IndCompaign::with(['user', 'classification' , 'campaignStatus' , 'donations'])->findOrFail($campaignId);
    $totalDonations = $compaign->donations->sum('amount');

    $photo = IndCompaigns_photo::find($compaign->photo_id)->photo;
    $fullPath = url(Storage::url($photo));
    $donorCounts = Donation:: where ('campaign_id' ,$campaignId)->count();

    $lastDonation = $compaign->donations->sortByDesc('created_at')->first();

    $targetPath = 'uploads/det/defualtProfilePhoto.png';
    $userPhoto = $compaign->user->photo
             ? url(Storage::url($compaign->user->photo))
             : url(Storage::url($targetPath)) ;
    $remainingAmount = max($compaign->amount_required - $totalDonations, 0);

         $compaingDet = [];
         $compaingDet[] = [
            'title' => $compaign->title,
            'amount_required' => $compaign->amount_required,
            'donation_amount' => $totalDonations,
            'remaining_amount' => $remainingAmount,

            'donorCounts' => $donorCounts,
            'campaign_status' => [
                  'id' => $compaign->campaign_status_id,
                  'type' => $compaign->campaignStatus->status_type
            ],
            'photo_id' => [
                  'id' =>$compaign->photo_id ,
                  'photo' => $fullPath
            ],
            'compaigns_time_to_end' => Carbon::now()->diff($compaign->compaigns_end_time)->format('%d'),
            'description' => $compaign->description,
            'campaign_start_time' => $compaign->compaigns_start_time,
            'campaign_end_time' => $compaign->compaigns_end_time,
            'last_donation_time' => $lastDonation ? $lastDonation->created_at->diffForHumans() : 'no Donations yet',
            'location' => $compaign->location,
            'classification' => [
                  'id' => $compaign->classification_id,
                  'type' => $compaign->classification->classification_name
            ],
            'user' => [
                'name' => $compaign->user->name,
                'photo' => $userPhoto,
            ]
    ];

        $message = 'individual campaign details are retrived sucessfully';
         return ['campaign' => $compaingDet , 'message' => $message];
}


public function getUnderReviewIndiviCampaignDetails($campaignId):array{
        return $this->getUnderReviewIndiviCampaignDetailsMain($campaignId);
}

public function updateAcceptanceStatus(array $request, int $campaignId): array
{
    $campaign = IndCompaign::findOrFail($campaignId);
    $status = AcceptanceStatus::where('status_type', $request['status'])->first();

    if (!$status) {
        throw new InvalidArgumentException('Invalid acceptance status type.');
    }

    $campaign->acceptance_status_id = $status->id;
    if ($request['status'] === 'Rejected') {
        $campaign->rejection_reason = $request['rejection_reason'];
    } else {
        $campaign->rejection_reason = null;
    }

    $campaign->save();
    $campaign->refresh();
    $campaignDetails = [
        'id' => $campaign->id,
        'title' => $campaign->title,
        'status' => $status->status_type,
        'rejection_reason' => $campaign->rejection_reason,
    ];
    $message = 'done';
    return [
        'campaign' => $campaignDetails,
        'message' => $message,
    ];
}


public function getClosedIndiviCampaignDetails($campaignId):array{
    $compaign = IndCompaign::with(['user', 'classification' , 'campaignStatus' , 'donations'])->findOrFail($campaignId);
    $photo = IndCompaigns_photo::find($compaign->photo_id)->photo;
    $fullPath = url(Storage::url($photo));
    $targetPath = 'uploads/det/defualtProfilePhoto.png';
    $userPhoto = $compaign->user->photo
             ? url(Storage::url($compaign->user->photo))
             : url(Storage::url($targetPath)) ;

         $compaingDet = [];
         $compaingDet[] = [
            'title' => $compaign->title,
            'amount_required' => $compaign->amount_required,
            'compaigns_time' => $compaign->compaigns_time,
            'rejection_reason' => $compaign -> rejection_reason,
            'campaign_status' => [
                  'id' => $compaign->campaign_status_id,
                  'type' => $compaign->campaignStatus->status_type
            ],
            'photo_id' => [
                  'id' =>$compaign->photo_id ,
                  'photo' => $fullPath
            ],
            'description' => $compaign->description,
            'location' => $compaign->location,
            'classification' => [
                  'id' => $compaign->classification_id,
                  'type' => $compaign->classification->classification_name
            ],
            'user' => [
                'name' => $compaign->user->name,
                'photo' => $userPhoto,
            ]
    ];

        $message = 'individual campaign details are retrived sucessfully';
         return ['campaign' => $compaingDet , 'message' => $message];
}


public function getLeaderForm($campaignId):array{
    $form = Leader_form::where('campaign_id', $campaignId)->first();
    if (!$form) {
    return ['form' => [], 'message' => 'No leader form found for this campaign yet'];
}

    // $photo = IndCompaigns_photo::find($compaign->photo_id)->photo;
    // $fullPath = url(Storage::url($photo));
    // $targetPath = 'uploads/det/defualtProfilePhoto.png';
    // $userPhoto = $compaign->user->photo
    //          ? url(Storage::url($compaign->user->photo))
    //          : url(Storage::url($targetPath)) ;

         $formDet = [];
         $formDet[] = [
                'visit_date' => $form->visit_date,
                'leader_name' => $form->leader_name,
                'location_type' => $form->location_type,
                'description' => $form -> description,
                'number_of_beneficiaries' =>  $form->number_of_beneficiaries,

                'beneficiary_type' =>  $form->beneficiary_type,
                'need_type' =>  $form->need_type,
                'has_other_support' =>  $form->has_other_support,
                'marks_from_5' =>  $form->marks_from_5,

                'notes' => $form->notes ?? '',
                'recommendation' =>  $form->recommendation,

            // 'photo_id' => [
            //       'id' =>$compaign->photo_id ,
            //       'photo' => $fullPath
            // ],
    ];

        $message = 'Leader form are retrived sucessfully';
         return ['form' => $formDet , 'message' => $message];
}
}
