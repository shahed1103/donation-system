<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SuperAdminService;
use Storage;
use App\Http\Responses\response;
use App\Services\IndividualCompaignsService;
use App\Http\Requests\IndividualCompaings\CreateIndividualCompaingsRequest;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;


class SuperAdminController extends Controller
{

protected AssociationService $associationService;

public function __construct(SuperAdminService $superAdminService)
{
    $this->superAdminService = $superAdminService;
}


public function countAssociations(): JsonResponse {
    $data = [];
    try {
        $data = $this->superAdminService->countAssociations();
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function lastNewUsers(): JsonResponse {
    $data = [];
    try {
        $data = $this->superAdminService->lastNewUsers();
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function getUserCountsLastFiveYears(): JsonResponse {
    $data = [];
    try {
        $data = $this->superAdminService->getUserCountsLastFiveYears();
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function getTotalCampaignsCountByYear($year): JsonResponse {
    $data = [];
    try {
        $data = $this->superAdminService->getTotalCampaignsCount($year);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function getUserCountsByRoleByYear($year): JsonResponse {
    $data = [];
    try {
        $data = $this->superAdminService->getUserCountsByRoleByYear($year);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}


public function usersCountByYear($year): JsonResponse {
    $data = [];
    try {
        $data = $this->superAdminService->usersCountByYear($year);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function totalDonationsByYear($year): JsonResponse {
    $data = [];
    try {
        $data = $this->superAdminService->totalDonationsByYear($year);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function getCityDonationPercentagesByYear($year): JsonResponse {
    $data = [];
    try {
        $data = $this->superAdminService->getCityDonationPercentagesByYear($year);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function getMonthlyDonationsByYear($year): JsonResponse {
    $data = [];
    try {
        $data = $this->superAdminService->getMonthlyDonationsByYear($year);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function getEndedCampaignsCountByYear($year): JsonResponse {
    $data = [];
    try {
        $data = $this->superAdminService->getEndedCampaignsCountByYear($year);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function getDonorsAndVolunteers(): JsonResponse {
    $data = [];
    try {
        $data = $this->superAdminService->getDonorsAndVolunteers();
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function getTeamLeaders(): JsonResponse {
    $data = [];
    try {
        $data = $this->superAdminService->getTeamLeaders();
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function createLeader(Request $request): JsonResponse {
    $data = [];
    try {
        $data = $this->superAdminService->createLeader($request);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function deleteLeader($id): JsonResponse {
    $data = [];
    try {
        $data = $this->superAdminService->deleteLeader($id);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}


}
