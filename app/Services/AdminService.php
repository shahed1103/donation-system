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
use Storage;
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

class AdminService
{

public function totalAssociationDonationsByYear(int $owner_id, int $year): array
{
    $association = Association::where('association_owner_id' , $owner_id ) ->first();
    $startOfYear = Carbon::createFromDate($year, 1, 1)->startOfDay();
    $endOfYear = Carbon::createFromDate($year, 12, 31)->endOfDay();

    $total = DonationAssociationCampaign::whereHas('associationCompaigns', function ($query) use ($associationId) {
            $query->where('association_id', $associationId);
        })
        ->whereBetween('created_at', [$startOfYear, $endOfYear])
        ->sum('amount');

    $message = "Total donations for year {$year} retrieved successfully";

    return [
        'total' => $total,
        'message' => $message
    ];
}


public function getMonthlyDonationsByYear(int $associationId, int $year): array
{
    $donationsByMonth = DonationAssociationCampaign::whereHas('associationCampaign', function ($query) use ($associationId) {
            $query->where('association_id', $associationId);
        })
        ->whereYear('created_at', $year)
        ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
        ->groupBy('month')
        ->orderBy('month')
        ->get();

    $monthlyTotals = [];
    for ($i = 1; $i <= 12; $i++) {
        $monthlyTotals[$i] = 0;
    }

    foreach ($donationsByMonth as $row) {
        $monthlyTotals[(int)$row->month] = (float)$row->total;
    }

    $message = "Monthly donation totals for year {$year} retrieved successfully";

    return [
        'data' => $monthlyTotals,
        'message' => $message
    ];
}



public function getActiveCampaignsCount(int $associationId , $year): array
{
    $startOfYear = Carbon::createFromDate($year, 1, 1)->startOfDay();
    $endOfYear = Carbon::createFromDate($year, 12, 31)->endOfDay();


    $campaignIds = SharedAssociationCampaign::where('association_id', $associationId)
        ->pluck('association_campaign_id');

    $activeCampaignsCount = AssociationCampaign::whereIn('id', $campaignIds)
        ->where('campaign_status_id', 1)
        ->whereBetween('created_at', [$startOfYear, $endOfYear])
        ->count();

    $message = 'Active campaigns count retrieved successfully';

    return [
        'count' => $activeCampaignsCount,
        'message' => $message
    ];
}

public function getCompleteCampaignsCount(int $associationId , $year): array
{

    $startOfYear = Carbon::createFromDate($year, 1, 1)->startOfDay();
    $endOfYear = Carbon::createFromDate($year, 12, 31)->endOfDay();

    $campaignIds = SharedAssociationCampaign::where('association_id', $associationId)
        ->pluck('association_campaign_id');

    $activeCampaignsCount = AssociationCampaign::whereIn('id', $campaignIds)
        ->where('campaign_status_id', 3)
        ->whereBetween('created_at', [$startOfYear, $endOfYear])
        ->count();

    $message = 'Active campaigns count retrieved successfully';

    return [
        'count' => $activeCampaignsCount,
        'message' => $message
    ];
}


public function getDonationCountsByClassByYear(int $associationId , int $year): array
{
    $startOfYear = Carbon::createFromDate($year, 1, 1)->startOfDay();
    $endOfYear = Carbon::createFromDate($year, 12, 31)->endOfDay();


    $healthyCount = DonationAssociationCampaign::where('classification_id', 1)
        -> where('association_id', $associationId)
        ->whereBetween('created_at', [$startOfYear, $endOfYear])
        ->count();

    $EducationalCount = DonationAssociationCampaign::where('classification_id', 2)
        -> where('association_id', $associationId)
        ->whereBetween('created_at', [$startOfYear, $endOfYear])
        ->count();

    $cleanlinessCount = DonationAssociationCampaign::where('classification_id', 3)
        -> where('association_id', $associationId)
        ->whereBetween('created_at', [$startOfYear, $endOfYear])
        ->count();

    $environmentalCount = DonationAssociationCampaign::where('classification_id', 4)
        -> where('association_id', $associationId)
        ->whereBetween('created_at', [$startOfYear, $endOfYear])
        ->count();


    $data = [
        'healthy' => $healthyCount,
        'Educational'     => $EducationalCount,
        'cleanliness'     => $cleanlinessCount,
        'environmental'      => $environmentalCount,
    ];

    $message = "User counts by classification for year {$year} retrieved successfully";

    return [
        'data' => $data,
        'message' => $message
    ];
}


public function AssociationDetails($id): array
      {
         $association = Association::findOrFail($id);
         $campaignIds = SharedAssociationCampaign::where('association_id', $id)
            ->pluck('association_campaign_id');

         $totalCampaigns = $campaignIds->count();
         $totalDonations = DonationAssociationCampaign::whereIn('association_campaign_id', $campaignIds)
            ->sum('amount');

        //  $completedCampaigns = $this->getAssociationCompaingsComplete($id);
        //  $activeCampaigns = $this->getAssociationsCampaignsActive($id);
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
            // 'completed_campaigns' => $completedCampaigns,
            // 'active_campaigns' => $activeCampaigns
            ];
            $message = 'association details are retrived sucessfully';

         return ['association' => $associationDet , 'message' => $message];
      }



public function getCampaignsStatus(): array
{
        $status = CampaignStatus::select('id', 'name')->get()
            ->map(function ($status) {
                return [
                    'id'   => $status->id,
                    'name' => $status->name, ]; });
        return [
            'status' => $status,
            'message' => 'done' ];
}


public function HealthyAssociationsCampaigns($association_id , $campaignStatus): array
{
         $campaignIds = SharedAssociationCampaign::where('association_id', $association_id)
            ->pluck('association_campaign_id');

         $campaigns = AssociationCampaign::with(['classification', 'campaignStatus'])
            ->whereIn('id', $campaignIds)
            ->where('campaign_status_id', $campaignStatus)
            ->where('classification_id', 1)
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

public function EducationalAssociationsCampaigns($association_id , $campaignStatus): array
{
         $campaignIds = SharedAssociationCampaign::where('association_id', $association_id)
            ->pluck('association_campaign_id');

         $campaigns = AssociationCampaign::with(['classification', 'campaignStatus'])
            ->whereIn('id', $campaignIds)
            ->where('campaign_status_id', $campaignStatus)
            ->where('classification_id', 2)
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

public function CleanlinessAssociationsCampaigns($association_id , $campaignStatus): array
{
         $campaignIds = SharedAssociationCampaign::where('association_id', $association_id)
            ->pluck('association_campaign_id');

         $campaigns = AssociationCampaign::with(['classification', 'campaignStatus'])
            ->whereIn('id', $campaignIds)
            ->where('campaign_status_id', $campaignStatus)
            ->where('classification_id', 3)
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

public function EnvironmentalAssociationsCampaigns($association_id , $campaignStatus): array
{
         $campaignIds = SharedAssociationCampaign::where('association_id', $association_id)
            ->pluck('association_campaign_id');

         $campaigns = AssociationCampaign::with(['classification', 'campaignStatus'])
            ->whereIn('id', $campaignIds)
            ->where('campaign_status_id', $campaignStatus)
            ->where('classification_id', 4)
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

public function AssociationAdmin ($association_id) {
    $association = Association:: firstWhere('id', $association_id);
    $admin_id = $association -> association_owner_id;
    $admin = User:: firstWhere('id', $admin_id );

 $adminDet = [];
 $adminDet[] = [
                  'id' =>  $admin->id,
                  'name' => $admin->name,
                  'email' => $admin->email,
                  'phone' => $admin->phone,
 ];

        $message = 'Your admin retrived sucessfully';
        return ['admin' => $adminDet, 'message' => $message];
}

}
