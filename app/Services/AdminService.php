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

public function totalAssociationDonationsByYear(int $associationId, int $year): array
{
    $startOfYear = Carbon::createFromDate($year, 1, 1)->startOfDay();
    $endOfYear = Carbon::createFromDate($year, 12, 31)->endOfDay();

    $total = DonationAssociationCampaign::whereHas('associationCampaign', function ($query) use ($associationId) {
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
}
