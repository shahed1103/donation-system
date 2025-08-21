<?php

namespace App\Http\Controllers;

use Storage;
use Illuminate\Http\Request;
use App\Http\Responses\response;
use App\Services\AdminService;
use App\Http\Requests\Donation\DonationForCompaingRequest;
use App\Http\Requests\Association\StoreCampaignRequest;
use App\Http\Requests\Donation\WalletDonationForCompaingRequest;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class AdminController extends Controller
{
    private AdminService $adminService;

    public function __construct(AdminService  $adminService){
        $this->adminService = $adminService;
    }


public function totalAssociationDonationsByYear($owner_id , $year): JsonResponse {
    $data = [];
    try {
        $data = $this->adminService->totalAssociationDonationsByYear($owner_id ,$year);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}


public function getMonthlyDonationsByYear($owner_id ,$year): JsonResponse {
    $data = [];
    try {
        $data = $this->adminService->getMonthlyDonationsByYear($owner_id ,$year);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}


public function getActiveCampaignsCount($owner_id ,$year): JsonResponse {
    $data = [];
    try {
        $data = $this->adminService->getActiveCampaignsCount($owner_id,$year);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function getCompleteCampaignsCount($owner_id , $year): JsonResponse {
    $data = [];
    try {
        $data = $this->adminService->getCompleteCampaignsCount($owner_id , $year);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function getDonationCountsByClassByYear($owner_id , $year): JsonResponse {
    $data = [];
    try {
        $data = $this->adminService->getDonationCountsByClassByYear($owner_id , $year);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function AssociationDetails($owner_id ): JsonResponse {
    $data = [];
    try {
        $data = $this->adminService->AssociationDetails($owner_id );
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function getCampaignsStatus(): JsonResponse {
    $data = [];
    try {
        $data = $this->adminService->getCampaignsStatus();
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}


public function HealthyAssociationsCampaigns($association_id , $campaignStatus): JsonResponse {
    $data = [];
    try {
        $data = $this->adminService->HealthyAssociationsCampaigns($association_id , $campaignStatus);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function EducationalAssociationsCampaigns($association_id , $campaignStatus): JsonResponse {
    $data = [];
    try {
        $data = $this->adminService->EducationalAssociationsCampaigns($association_id , $campaignStatus);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function CleanlinessAssociationsCampaigns($association_id , $campaignStatus): JsonResponse {
    $data = [];
    try {
        $data = $this->adminService->CleanlinessAssociationsCampaigns($association_id , $campaignStatus);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function EnvironmentalAssociationsCampaigns($association_id , $campaignStatus): JsonResponse {
    $data = [];
    try {
        $data = $this->adminService->EnvironmentalAssociationsCampaigns($association_id , $campaignStatus);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function AssociationAdmin($association_id): JsonResponse {
    $data = [];
    try {
        $data = $this->adminService->AssociationAdmin($association_id);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function getVoluntingCampigns($status_id): JsonResponse {
    $data = [];
    try {
        $data = $this->adminService->getVoluntingCampigns($status_id);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function getVoluntingCompDetails($compaign_id): JsonResponse {
    $data = [];
    try {
        $data = $this->adminService->getVoluntingCompDetails($compaign_id);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}


public function createAssociationCampaign(StoreCampaignRequest $request): JsonResponse {
    $data = [];
    try {
        $data = $this->adminService->createAssociationCampaign($request);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}




}
