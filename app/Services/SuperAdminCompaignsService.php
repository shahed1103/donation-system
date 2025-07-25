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

class SuperAdminCompaignsService
{

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
    $campaigns = AssociationCampaign::with(['campaignStatus'])
        ->where('campaign_status_id', 1)
        ->whereHas('sharedAssociations', function ($query) use ($association_id) {
            $query->where('association_id', $association_id);
        })
        ->get();
    $campaignsAll = [];

    foreach ($campaigns as $campaign) {
        $totalDonations = DonationAssociationCampaign::where('association_campaign_id', $campaign->id)
            ->sum('amount');

        $campaignsAll[] = [
            'id' => $campaign->id,
            'title' => $campaign->title,
            'photo' => url(Storage::url($campaign->photo)),
            'amount_required' => $campaign->amount_required,
            'donation_amount' => $totalDonations,
            'campaign_status' => [
                'id' => $campaign->campaign_status_id,
                'type' => optional($campaign->campaignStatus)->status_type,
            ],
            'time_to_end' => Carbon::now()->diff($campaign->compaigns_end_time)->format('%m Months %d Days %h Hours'),
        ];
    }

    return [
        'association_campaigns' => $campaignsAll,
        'message' => 'Campaigns retrieved successfully',
    ];
}
}
