<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Storage;
use App\Http\Responses\response;
use App\Services\IndividualCompaignsService;
use App\Services\SuperAdminIndividualCompaignsService;
use App\Http\Requests\IndividualCompaings\CreateIndividualCompaingsRequest;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;


class SuperAdminIndividualCompaignsController extends Controller
{
protected SuperAdminIndividualCompaignsService $superAdminCompaignsService;

public function __construct(SuperAdminIndividualCompaignsService $superAdminCompaignsService)
{
    $this->superAdminCompaignsService = $superAdminCompaignsService;
}



public function getActiveIndiviCompaign(): JsonResponse {
    $data = [];
    try {
        $data = $this->superAdminCompaignsService->getActiveIndiviCompaign();
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function getCompleteIndiviCompaign(): JsonResponse {
    $data = [];
    try {
        $data = $this->superAdminCompaignsService->getCompleteIndiviCompaign();
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function getClosedRejectedIndiviCompaign(): JsonResponse {
    $data = [];
    try {
        $data = $this->superAdminCompaignsService->getClosedRejectedIndiviCompaign();
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function getClosedUnderReviewIndiviCompaign(): JsonResponse {
    $data = [];
    try {
        $data = $this->superAdminCompaignsService->getClosedUnderReviewIndiviCompaign();
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}



}
