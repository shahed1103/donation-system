<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Storage;
use App\Http\Responses\response;
use App\Services\IndividualCompaignsService;
use App\Services\SuperAdminCompaignsService;
use App\Http\Requests\IndividualCompaings\CreateIndividualCompaingsRequest;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;


class SuperAdminCompaignsController extends Controller
{

protected SuperAdminCompaignsService $superAdminCompaignsService;

public function __construct(SuperAdminCompaignsService $superAdminCompaignsService)
{
    $this->superAdminCompaignsService = $superAdminCompaignsService;
}


public function getAssociations(): JsonResponse {
    $data = [];
    try {
        $data = $this->superAdminCompaignsService->getAssociations();
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function getAssociationsCampaignsActive($association_id): JsonResponse {
    $data = [];
    try {
        $data = $this->superAdminCompaignsService->getAssociationsCampaignsActive($association_id);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function getAssociationCompaingsComplete($association_id): JsonResponse {
    $data = [];
    try {
        $data = $this->superAdminCompaignsService->getAssociationCompaingsComplete($association_id);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function getAssociationCompaingsClosed($association_id): JsonResponse {
    $data = [];
    try {
        $data = $this->superAdminCompaignsService->getAssociationCompaingsClosed($association_id);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

////////////indivi

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

public function getCompleteIndiviCompaign($id): JsonResponse {
    $data = [];
    try {
        $data = $this->superAdminCompaignsService->getCompleteIndiviCompaign($id);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function getClosedPendingIndiviCampaigns($id): JsonResponse {
    $data = [];
    try {
        $data = $this->superAdminCompaignsService->getClosedPendingIndiviCampaigns($id);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function getClosedRejectedIndiviCampaigns($id): JsonResponse {
    $data = [];
    try {
        $data = $this->superAdminCompaignsService->getClosedRejectedIndiviCampaigns($id);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

}
