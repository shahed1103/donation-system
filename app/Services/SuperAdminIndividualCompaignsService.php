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

class SuperAdminIndividualCompaignsService
{

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
        'message' => 'All complete campaigns retrieved successfully',
    ];
}


public function getClosedRejectedIndiviCompaign(): array
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

