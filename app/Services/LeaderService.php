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
use App\Models\Leader_form;
use App\Models\IndCompaign;
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
            'is_need_real' => $request['is_need_real'],
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
            'is_need_real' => $leaderForm->is_need_real,
            'has_other_support' => $leaderForm->has_other_support,
            'marks_from_5' => $leaderForm->marks_from_5,
            'notes' => $leaderForm->notes,
            'recommendation' => $leaderForm->recommendation,
        ];

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
            'compaigns_time_to_end' => Carbon::now()->diff($campaign->compaigns_end_time)->format('%m Months %d Days %h Hours'),
        ];
    }

    return [
        'campaign' => $campaignAll,
        'message' => 'All closed and under review campaigns retrieved successfully',
    ];
}




}
