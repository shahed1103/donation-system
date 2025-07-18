<?php

namespace App\Services;

use App\Models\Association;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Session;
use Exception;
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

}
