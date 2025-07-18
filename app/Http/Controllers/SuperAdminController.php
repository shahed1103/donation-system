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

public function getTotalCampaignsCount(): JsonResponse {
    $data = [];
    try {
        $data = $this->superAdminService->getTotalCampaignsCount();
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function getUserCountsByRole(): JsonResponse {
    $data = [];
    try {
        $data = $this->superAdminService->getUserCountsByRole();
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}
}
