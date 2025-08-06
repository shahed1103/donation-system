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
    $count = Association::count();

    return [
        'count' => $count,
        'message' => 'Association count retrieved successfully'
    ];
}

public function lastNewUsers(): array
{
    $count = User::where('created_at', '>=', Carbon::now()->subDays(30))->count();

    return [
        'count' => $count,
        'message' => 'New users in the last 30 days retrieved successfully'
    ];
}


public function getUserCountsLastFiveYears(): array
{
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
        'data' => $result,
        'message' => 'User counts for the last 5 years retrieved successfully'
    ];
}

public function getTotalCampaignsCountByYear(int $year): array
{
    $startOfYear = Carbon::createFromDate($year, 1, 1)->startOfDay();
    $endOfYear = Carbon::createFromDate($year, 12, 31)->endOfDay();

    $individualCount = IndCompaign::whereBetween('created_at', [$startOfYear, $endOfYear])->count();
    $associationCount = AssociationCampaign::whereBetween('created_at', [$startOfYear, $endOfYear])->count();

    $total = $individualCount + $associationCount;

    return [
        'data' => [
            'individual_campaigns' => $individualCount,
            'association_campaigns' => $associationCount,
            'total_campaigns' => $total,
        ],
        'message' => "Campaign counts for year {$year} retrieved successfully"
    ];
}


public function getUserCountsByRoleByYear(int $year): array
{
    $startOfYear = Carbon::createFromDate($year, 1, 1)->startOfDay();
    $endOfYear = Carbon::createFromDate($year, 12, 31)->endOfDay();

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

    $data = [
        'volunteers' => $volunteerCount,
        'donors'     => $donorCount,
        'admins'     => $adminCount,
        'total'      => $volunteerCount + $donorCount + $adminCount,
    ];

    $message = "User counts by role for year {$year} retrieved successfully";

    return [
        'data' => $data,
        'message' => $message
    ];
}


public function usersCountByYear(int $year): array
{
    $startOfYear = Carbon::createFromDate($year, 1, 1)->startOfDay();
    $endOfYear = Carbon::createFromDate($year, 12, 31)->endOfDay();

    $count = User::whereBetween('created_at', [$startOfYear, $endOfYear])->count();

    return [
        'count' => $count,
        'message' => "User count for year {$year} retrieved successfully"
    ];
}


public function totalDonationsByYear(int $year): array
{
    $startOfYear = Carbon::createFromDate($year, 1, 1)->startOfDay();
    $endOfYear = Carbon::createFromDate($year, 12, 31)->endOfDay();

    $total = Donation::whereBetween('created_at', [$startOfYear, $endOfYear])
                     ->sum('amount');

    return [
        'total' => $total,
        'message' => "Total donations for year {$year} retrieved successfully"
    ];
}





public function getCityDonationPercentagesByYear(int $year): array
{
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

    $message = "City donation percentages for year {$year} retrieved successfully";

    return [
        'data' => $data,
        'message' => $message
    ];
}



public function getMonthlyDonationsByYear(int $year): array
{
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

    $message = "Monthly donation totals for year {$year} retrieved successfully";

    return [
        'data' => $monthlyTotals,
        'message' => $message
    ];
}









////عدد المستفيدين
public function getEndedCampaignsCountByYear(int $year): array
{
    $startOfYear = Carbon::createFromDate($year, 1, 1)->startOfDay();
    $endOfYear = Carbon::createFromDate($year, 12, 31)->endOfDay();

    $endedIndCampaigns = IndCompaign::whereBetween('compaigns_end_time', [$startOfYear, $endOfYear])
        ->whereDate('compaigns_end_time', '<', Carbon::today())
        ->count();

    $endedAssociationCampaigns = AssociationCampaign::whereBetween('compaigns_end_time', [$startOfYear, $endOfYear])
        ->whereDate('compaigns_end_time', '<', Carbon::today())
        ->count();

    $data = [
        'individual_campaigns_ended'   => $endedIndCampaigns,
        'association_campaigns_ended' => $endedAssociationCampaigns,
        'total_ended'                 => $endedIndCampaigns + $endedAssociationCampaigns,
    ];

    $message = "Ended campaigns in year {$year} retrieved successfully";

    return [
        'data' => $data,
        'message' => $message
    ];
}


public function getDonorsAndVolunteers(): array
{
    $volunteerRoleId = Role::where('name', 'Volunteer')->value('id');
    $donorRoleId = Role::where('name', 'Donor')->value('id');

    $users = User::whereIn('role_id', [$volunteerRoleId, $donorRoleId])
        ->select('name', 'email', 'phone')
        ->get();

    $message = 'Donors and volunteers retrieved successfully';

    return [
        'users' => $users,
        'message' => $message
    ];
}


public function getTeamLeaders(): array
{
    $leaderRoleId = Role::where('name', 'Leader')->value('id');

    $users = User::with('city:id,name')
        ->where('role_id', $leaderRoleId)
        ->select('id', 'name', 'email', 'phone', 'city_id', 'password')
        ->get()
        ->map(function ($user) {
            return [
                'name'     => $user->name,
                'email'    => $user->email,
                'phone'    => $user->phone,
                'city'     => optional($user->city)->name,
                'password' => '[PASSWORD IS HASHED]',
            ];
        });

    $message = 'Team leaders retrieved successfully';

    return [
        'users' => $users,
        'message' => $message
    ];
}


public function createLeader(Request $request): array
{
    $role = Role::where('name', 'leader')->first();

    $validated = $request->validate([
        'name'      => 'required|string',
        'email'     => 'required|email|unique:users,email',
        'phone'     => 'nullable|string',
        'password'  => 'required|string|min:6',
        'age'       => 'nullable|string',
        'city_id'   => 'nullable|integer',
        'gender_id' => 'nullable|integer',
    ]);

    $user = User::create([
        'name'      => $validated['name'],
        'email'     => $validated['email'],
        'phone'     => $validated['phone'] ?? null,
        'password'  => Hash::make($validated['password']),
        'age'       => $validated['age'] ?? null,
        'city_id'   => $validated['city_id'] ?? null,
        'gender_id' => $validated['gender_id'] ?? null,
        'role_id'   => $role->id,
    ]);

    $user->assignRole('leader');

    return [
        'user' => $user,
        'message' => 'Leader created successfully'
    ];
}



public function deleteLeader($id): array
{
    $user = User::findOrFail($id);
    $user->delete();

    return [
        'message' => 'Leader deleted successfully'
    ];
}


}
