<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Storage;
use App\Http\Responses\response;
use App\Services\SuperAssociationCompaignsService;
use App\Http\Requests\Association\AddAssociationRequest;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;


class SuperAdminAssociationCompaignsController extends Controller
{

protected SuperAssociationCompaignsService $superAssociationCompaignsService;

public function __construct(SuperAssociationCompaignsService $superAssociationCompaignsService)
{
    $this->superAssociationCompaignsService = $superAssociationCompaignsService;
}


public function getAssociations(): JsonResponse {
    $data = [];
    try {
        $data = $this->superAssociationCompaignsService->getAssociations();
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
        $data = $this->superAssociationCompaignsService->getAssociationsCampaignsActive($association_id);
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
        $data = $this->superAssociationCompaignsService->getAssociationCompaingsComplete($association_id);
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
        $data = $this->superAssociationCompaignsService->getAssociationCompaingsClosed($association_id);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function getCampaignDetails($Campaign_id): JsonResponse {
    $data = [];
    try {
        $data = $this->superAssociationCompaignsService->getCampaignDetails($Campaign_id);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}


public function getAssociationDetails($Campaign_id): JsonResponse {
    $data = [];
    try {
        $data = $this->superAssociationCompaignsService->getAssociationDetails($Campaign_id);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function addAssociation(AddAssociationRequest $request): JsonResponse {
    $data = [];
    try {
        $data = $this->superAssociationCompaignsService->addAssociation($request);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}


}
