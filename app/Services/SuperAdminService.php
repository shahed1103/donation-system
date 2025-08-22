<?php

namespace App\Services;


use Illuminate\Support\Carbon;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Session;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\Association;
use App\Models\User;
use App\Models\Center;
use App\Models\Donation;
use App\Models\DonationType;
use App\Models\StatusOfDonation;
use App\Models\InkindDonationAcceptence;
use App\Models\InkindDonation;
use Storage;
use App\Models\IndCompaign;
use App\Models\AssociationCampaign;
use App\Models\DonationAssociationCampaign;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use App\Http\Responses\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Throwable;
use App\Traits\CountAssociationsMain;

class SuperAdminService
{
    use CountAssociationsMain;

public function countAssociations(): array
{
   return $this->countAssociationsMain();
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

    $clientRole = Role::where('name', 'Client')->value('id');
    $leaderRole = Role::where('name', 'Leader')->value('id');
    $adminRoleId = Role::where('name', 'Admin')->value('id');

    $clientCount = User::where('role_id', $clientRole)
        ->whereBetween('created_at', [$startOfYear, $endOfYear])
        ->count();

    $leaderCount = User::where('role_id', $leaderRole)
        ->whereBetween('created_at', [$startOfYear, $endOfYear])
        ->count();

    $adminCount = User::where('role_id', $adminRoleId)
        ->whereBetween('created_at', [$startOfYear, $endOfYear])
        ->count();

    $data = [
        'client' => $clientCount,
        'leader'     => $leaderCount,
        'admins'     => $adminCount,
        'total'      => $clientCount + $leaderCount + $adminCount,
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

        $totalIndivi = Donation::whereBetween('created_at', [$startOfYear, $endOfYear])
                        ->sum('amount');

        $totalassoci = DonationAssociationCampaign::whereBetween('created_at', [$startOfYear, $endOfYear])
                        ->sum('amount');
        $total = $totalIndivi + $totalassoci;

    return [
        'total' => $total,
        'message' => "Total donations for year {$year} retrieved successfully"
    ];
}





public function getCityDonationPercentagesByYear(int $year): array
{
    $startOfYear = Carbon::createFromDate($year, 1, 1)->startOfDay();
    $endOfYear = Carbon::createFromDate($year, 12, 31)->endOfDay();

    $clientRoleId = Role::where('name', 'Client')->value('id');

    $donations = Donation::join('users', 'donations.user_id', '=', 'users.id')
        ->join('cities', 'users.city_id', '=', 'cities.id')
        ->where('users.role_id', $clientRoleId)
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








public function getClients(): array
{
    $clientRoleId = Role::where('name', 'Client')->value('id');

    $users = User::whereIn('role_id', [$clientRoleId])
        ->select('name', 'email', 'phone')
        ->get();

    $message = 'clients retrieved successfully';

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







////////////////////////////////////


public function getCenters(): array
{
    $centers = Center::All()->map(function ($center) {
            return [
                'center_name'     => $center->center_name,
                'location'    => $center->location,
                'space'    => $center->space,
                'have_frez'     => $center->have_frez,

            ];
        });

    $message = 'centers retrieved successfully';

    return [
        'centers' => $centers,
        'message' => $message
    ];
}


public function createCenter(Request $request): array
{

    $validated = $request->validate([
        'center_name'      => 'required|string',
        'location'     => 'required|string',
        'space'     => 'nullable|integer',
        'have_frez'  => 'required|boolean',

    ]);

    $center = Center::create([
        'center_name'      => $validated['center_name'],
        'location'     => $validated['location'],
        'space'     => $validated['space'] ?? null,
        'have_frez'  => $validated['have_frez'],

    ]);

    return [
        'center' => $center,
        'message' => 'Center created successfully'
    ];
}

public function deleteCenter($id): array
{
    $center = Center::findOrFail($id);
    $center->delete();

    return [
        'message' => 'Center deleted successfully'
    ];
}



public function getInkindDonation(): array
{
    $inkindDonations = InkindDonation::All()->map(function ($inkindDonation) {
            return [
                'donation_type'     => DonationType::where('id', $inkindDonation->donation_type_id)
                ->pluck('donation_Type'),
                'name_of_donation'     => $inkindDonation->center_name,
                'amount'    => $inkindDonation->amount,
                'photo' => url(Storage::url($inkindDonation->photo)),
                'description'    => $inkindDonation->description,
                'status_of_donation'     => StatusOfDonation::where('id', $inkindDonation->status_of_donation_id)
                ->pluck('status'),
                'center'     => Center::where('id', $inkindDonation->center_id)
                ->pluck('center_name'),
                'owner'     => User::where('id', $inkindDonation->owner_id)
                ->pluck('name'),
            ];
        });

    $message = 'inkindDonations retrieved successfully';

    return [
        'inkindDonations' => $inkindDonations,
        'message' => $message
    ];
}



}
