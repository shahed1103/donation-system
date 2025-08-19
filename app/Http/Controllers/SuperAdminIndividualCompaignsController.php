<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Storage;
use App\Http\Responses\response;
use App\Services\IndividualCompaignsService;
use App\Services\SuperAdminIndividualCompaignsService;
use App\Http\Requests\IndividualCompaings\CreateIndividualCompaingsRequest;
use App\Http\Requests\IndividualCompaings\UpdateCampaignAcceptanceStatusRequest;
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

public function getClosedRejectedIndiviCampaigns(): JsonResponse {
    $data = [];
    try {
        $data = $this->superAdminCompaignsService->getClosedRejectedIndiviCampaigns();
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

public function getActiveCompleteIndiviCampaignDetails($id): JsonResponse {
    $data = [];
    try {
        $data = $this->superAdminCompaignsService->getActiveCompleteIndiviCampaignDetails($id);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function getUnderReviewIndiviCampaignDetails($id): JsonResponse {
    $data = [];
    try {
        $data = $this->superAdminCompaignsService->getUnderReviewIndiviCampaignDetails($id);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function updateAcceptanceStatus(UpdateCampaignAcceptanceStatusRequest $request, $id): JsonResponse {
    $data = [];
    try {
        $data = $this->superAdminCompaignsService->updateAcceptanceStatus($request->validated() ,$id);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function getClosedIndiviCampaignDetails( $id): JsonResponse {
    $data = [];
    try {
        $data = $this->superAdminCompaignsService->getClosedIndiviCampaignDetails($id);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function getLeaderForm( $id): JsonResponse {
    $data = [];
    try {
        $data = $this->superAdminCompaignsService->getLeaderForm($id);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}



}
