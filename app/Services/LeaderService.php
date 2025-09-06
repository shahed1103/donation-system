<?php

namespace App\Services;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Session;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\Association;
use App\Models\User;
use App\Models\AcceptanceStatus;

use App\Models\CampaignStatus;
use App\Http\Controllers\FcmController;
use App\Models\Donation;
use App\Models\Leader_form;
use App\Models\IndCompaign;
use App\Models\AssociationCampaign;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use App\Http\Responses\Response;
use App\Models\InkindDonation;
use App\Models\InkindDonationReservation;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Throwable;
use Storage;

class LeaderService
{

     public function addLeaderForm(array $request , $id): array
    {
        $leaderForm = Leader_form::create([
            'campaign_id' => $id,
            'visit_date' => $request['visit_date'],
            'leader_name' => $request['leader_name'],
            'location_type' => $request['location_type'],
            'description' => $request['description'],
            'number_of_beneficiaries' => $request['number_of_beneficiaries'],
            'beneficiary_type' => $request['beneficiary_type'],
            'need_type' => $request['need_type'],
            'has_other_support' => $request['has_other_support'],
            'marks_from_5' => $request['marks_from_5'],
            'notes' => $request['notes'] ?? null,
            'recommendation' => $request['recommendation'],
        ]);

        $leaderForm->refresh();

        $details = [
            'campaign' => optional($leaderForm->campaign)->title,
            'visit_date' => $leaderForm->visit_date,
            'leader_name' => $leaderForm->leader_name,
            'location_type' => $leaderForm->location_type,
            'description' => $leaderForm->description,
            'number_of_beneficiaries' => $leaderForm->number_of_beneficiaries,
            'beneficiary_type' => $leaderForm->beneficiary_type,
            'need_type' => $leaderForm->need_type,
            'has_other_support' => $leaderForm->has_other_support,
            'marks_from_5' => $leaderForm->marks_from_5,
            'notes' => $leaderForm->notes,
            'recommendation' => $leaderForm->recommendation,
        ];

        $superAdmin = User::where('role_id', 1)->first(); 

        if ($superAdmin && $superAdmin->fcm_token) {
            $fcmController = new FcmController();
            $fakeRequest = new Request([
                'user_id' => $superAdmin->id,
                'title' => 'الفورم الخاص بالكشف عن الحملة الفردية',
                // 'body' => "تم انشاء فورم الكشف يمكنك مراجعته: " . json_encode($details),
            ]);
            $fcmController->sendFcmNotification($fakeRequest);
        }

        $message = 'Leader form has been created successfully.';

        return [
            'leader_form' => $details,
            'message' => $message,
        ];
    }


public function UnderReviewIndiviCompaign(): array
{
    $closedStatusId = CampaignStatus::where('status_type', 'Closed')->pluck('id');
    $underReviewStatusId = AcceptanceStatus::where('status_type', 'Under review')->pluck('id');

    $campaigns = IndCompaign::with(['classification', 'campaignStatus', 'acceptanceStatus', 'photo'])
        ->whereIn('campaign_status_id', $closedStatusId)
        ->whereIn('acceptance_status_id', $underReviewStatusId)
        ->get();

    $campaignAll = [];

    foreach ($campaigns as $campaign) {
        if(!Leader_form::where('campaign_id' , $campaign->id)->exists()){
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
}

    return [
        'campaign' => $campaignAll,
        'message' => 'All closed and under review campaigns retrieved successfully',
    ];
}


     ///////in-kind management 
     //1 recive in-kind donation
     public function reciveInkindDonation(): array{
      $inkinds = InkindDonation::where('inkindDonation_acceptence_id' , 1)->get();

      $inkindDonation = [];
      foreach ($inkinds as $inkind) {

        $photos = [];
            foreach ($inkind->inkindDonationPhotos as $inkindDonationPhoto) {
                    $photos[] = [
                     'id' => $inkindDonationPhoto->id ,
                     'photo' => url(Storage::url($inkindDonationPhoto->photo)), 
                  ];
            }

        $inkindDonation [] = [
            'id' => $inkind->id,
            'name_of_donation' => $inkind->name_of_donation,
            'description'      => $inkind->description,
            'center_id'      => ['id' => $inkind->center_id , 'center_name' => $inkind->center->center_name],
            'location'      => $inkind->center->location,
            'status_of_donation_id'      => ['id' => $inkind->status_of_donation_id , 'status' => $inkind->statusOfDonation->status],
            'donation_type_id'      => ['id' => $inkind->donation_type_id , 'donation-type' => $inkind->donationType->donation_Type],
            'amount'      => $inkind->amount,
            'photo'        =>  $photos,
            'created_at'      => $inkind->created_at->format('Y-m-d'),

        ] ;
      }

      $message = 'all recived In-kind Donations are retrived succesfully';
      return ['in-kind donations' => $inkindDonation , 'message' => $message];
     }

     //2 accept in-kind donation
     public function updateInkindDonationAcceptence($inkindId): array{
        $inkinds = InkindDonation::find($inkindId);

        $inkinds->update([
            'inkindDonation_acceptence_id' => 2
        ]);

       $inkinds->refresh();
       $message = 'recived In-kind Donation accepted succesfully';
       return ['in-kind donations' => $inkinds , 'message' => $message];
     }

     //3 request to have in-kind donation
     public function requestToHaveInkindDonation(): array{
      $inkinds = InkindDonationReservation::where('status_id' , 1)->get();

      $inkindDonation = [];
      foreach ($inkinds as $inkind) {

        $inkindDonation [] = [
            'id' => $inkind->id,
            'user_name' => $inkind->user->name,
            'name_of_donation' => $inkind->donation->name_of_donation,
            'phone' => $inkind->user->phone,
            'amount'      => $inkind->amount,
            'created_at'      => $inkind->created_at->format('Y-m-d'),

        ] ;
      }

      $message = 'all request to have In-kind Donations are retrived succesfully';
      return ['in-kind donations' => $inkindDonation , 'message' => $message];
     }

     //4 update request to have in-kind donation
     public function updateRequestToHaveInkindDonation($reserveID): array{
        $inkinds = InkindDonationReservation::find($reserveID);

        $inkinds->update([
            'status_id' => 2
        ]);

       $inkinds->refresh();
       $message = 'request to have In-kind Donation delivered succesfully';
       return ['in-kind donations' => $inkinds , 'message' => $message];
     }

}
