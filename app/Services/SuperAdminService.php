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

class SuperAdminService
{

    public function countAssociations(): array
    {
        try {
            $count = Association::count();
            return [
                'count' => $count,
                'message' => 'done'
            ];
        } catch (Throwable $th) {
            throw $th;
        }
    }


public function lastNewUsers(): array
{
    try {
        $count = User::where('created_at', '>=', Carbon::now()->subDays(30))->count();

        return [
            'count' => $count,
            'message' => 'done'
        ];
    } catch (Throwable $th) {
        throw $th;
    }
}

public function getUserCountsLastFiveYears(): array
{
    try{
    $currentYear = Carbon::now()->year;
    $startYear = $currentYear - 4;

    $data = [];
    $total = 0;

    for ($year = $startYear; $year <= $currentYear; $year++) {
        $count = User::whereYear('created_at', $year)->count();
        $data[$year] = $count;
        $total += $count;
    }
    $result = [];
    foreach ($data as $year => $count) {
        $percentage = $total > 0 ? round(($count / $total) * 100, 2) : 0;
        $result[] = [
            'year' => $year,
            'count' => $count,
            'percentage' => $percentage
        ];
    }

    return [
        'status' => 0,
        'data' => $result,
        'message' => 'done',
        'errors' => []
    ];
}
    catch (Throwable $th) {
        throw $th;
    }
}

public function getTotalCampaignsCountByYear(int $year): array
{
    try {
        $startOfYear = Carbon::createFromDate($year, 1, 1)->startOfDay();
        $endOfYear = Carbon::createFromDate($year, 12, 31)->endOfDay();

        $individualCount = IndCompaign::whereBetween('created_at', [$startOfYear, $endOfYear])->count();
        $associationCount = AssociationCampaign::whereBetween('created_at', [$startOfYear, $endOfYear])->count();

        $total = $individualCount + $associationCount;

        return [
            'status' => 0,
            'data' => [
                'individual_campaigns' => $individualCount,
                'association_campaigns' => $associationCount,
                'total_campaigns' => $total,
            ],
            'message' => "Campaign counts for year {$year} retrieved successfully",
            'errors' => []
        ];
    } catch (\Throwable $e) {
        return [
            'status' => 1,
            'data' => [],
            'message' => 'Error fetching campaign counts',
            'errors' => [$e->getMessage()]
        ];
    }
}

public function getUserCountsByRoleByYear(int $year): array
{
    try {
        $startOfYear = Carbon\Carbon::createFromDate($year, 1, 1)->startOfDay();
        $endOfYear = Carbon\Carbon::createFromDate($year, 12, 31)->endOfDay();

        $volunteerRoleId = Role::where('name', 'Volunteer')->value('id');
        $donorRoleId = Role::where('name', 'Donor')->value('id');
        $adminRoleId = Role::where('name', 'Admin')->value('id');

        $volunteerCount = User::where('role_id', $volunteerRoleId)
            ->whereBetween('created_at', [$startOfYear, $endOfYear])
            ->count();

        $donorCount = User::where('role_id', $donorRoleId)
            ->whereBetween('created_at', [$startOfYear, $endOfYear])
            ->count();

        $adminCount = User::where('role_id', $adminRoleId)
            ->whereBetween('created_at', [$startOfYear, $endOfYear])
            ->count();

        return [
            'status' => 0,
            'data' => [
                'volunteers' => $volunteerCount,
                'donors' => $donorCount,
                'admins' => $adminCount,
                'total' => $volunteerCount + $donorCount + $adminCount,
            ],
            'message' => "User counts by role for year {$year} retrieved successfully",
            'errors' => []
        ];
    } catch (\Throwable $e) {
        return [
            'status' => 1,
            'data' => [],
            'message' => 'Error fetching user counts by role',
            'errors' => [$e->getMessage()]
        ];
    }
}

public function usersCountByYear(int $year): array
{
    try {
        $startOfYear = Carbon::createFromDate($year, 1, 1)->startOfDay();
        $endOfYear = Carbon::createFromDate($year, 12, 31)->endOfDay();

        $count = User::whereBetween('created_at', [$startOfYear, $endOfYear])->count();

        return [
            'count' => $count,
            'message' => 'done'
        ];
    } catch (Throwable $th) {
        throw $th;
    }
}

public function totalDonationsByYear(int $year): array
{
    try {
        $startOfYear = Carbon::createFromDate($year, 1, 1)->startOfDay();
        $endOfYear = Carbon::createFromDate($year, 12, 31)->endOfDay();

        $total = Donation::whereBetween('created_at', [$startOfYear, $endOfYear])
                         ->sum('amount');

        return [
            'total' => $total,
            'message' => 'done'
        ];
    } catch (Throwable $th) {
        throw $th;
    }
}

public function getCityDonationPercentagesByYear(int $year): array
{
    try {

        $startOfYear = Carbon::createFromDate($year, 1, 1)->startOfDay();
        $endOfYear = Carbon::createFromDate($year, 12, 31)->endOfDay();


        $donorRoleId = Role::where('name', 'Donor')->value('id');

     $donations = Donation::join('users', 'donations.user_id', '=', 'users.id')
    ->join('cities', 'users.city_id', '=', 'cities.id')
    ->where('users.role_id', $donorRoleId)
    ->whereBetween('donations.created_at', [$startOfYear, $endOfYear])
    ->select('cities.name as city_name', DB::raw('SUM(donations.amount) as total'))
    ->groupBy('cities.name')
    ->get();


        $overallTotal = $donations->sum('total');
        $data = $donations->map(function ($item) use ($overallTotal) {
            return [
                'city' => $item->city_name,
                'donation_total' => (float) $item->total,
                'percentage' => $overallTotal > 0 ? round(($item->total / $overallTotal) * 100, 2) : 0.0,
            ];
        });

        return [
            'status' => 0,
            'data' => $data,
            'message' => "City donation percentages for year {$year} retrieved successfully",
            'errors' => []
        ];
    } catch (Throwable $e) {
        return [
            'status' => 1,
            'data' => [],
            'message' => 'Error calculating city donation percentages',
            'errors' => [$e->getMessage()]
        ];
    }
}


public function getMonthlyDonationsByYear(int $year): array
{
    try {
   $donationsByMonth = Donation::whereYear('created_at', $year)
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

        return [
            'status' => 0,
            'data' => $monthlyTotals,
            'message' => "Monthly donation totals for year {$year} retrieved successfully",
            'errors' => []
        ];
    } catch (Throwable $e) {
        return [
            'status' => 1,
            'data' => [],
            'message' => 'Error retrieving monthly donations',
            'errors' => [$e->getMessage()]
        ];
    }
}

////عدد المستفيدين
public function getEndedCampaignsCountByYear(int $year): array
{
    try {
        $startOfYear = Carbon::createFromDate($year, 1, 1)->startOfDay();
        $endOfYear = Carbon::createFromDate($year, 12, 31)->endOfDay();

        $endedIndCampaigns = IndCompaign::whereBetween('compaigns_end_time', [$startOfYear, $endOfYear])
            ->whereDate('compaigns_end_time', '<', Carbon::today())
            ->count();


        $endedAssociationCampaigns = AssociationCampaign::whereBetween('compaigns_end_time', [$startOfYear, $endOfYear])
            ->whereDate('compaigns_end_time', '<', Carbon::today())
            ->count();

        return [
            'status' => 0,
            'data' => [
                'individual_campaigns_ended' => $endedIndCampaigns,
                'association_campaigns_ended' => $endedAssociationCampaigns,
                'total_ended' => $endedIndCampaigns + $endedAssociationCampaigns,
            ],
            'message' => "Ended campaigns in year {$year} retrieved successfully",
            'errors' => []
        ];
    } catch (Throwable $e) {
        return [
            'status' => 1,
            'data' => [],
            'message' => 'Error fetching ended campaigns count',
            'errors' => [$e->getMessage()]
        ];
    }
}


}
