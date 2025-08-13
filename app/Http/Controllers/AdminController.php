<?php

namespace App\Http\Controllers;

use Storage;
use Illuminate\Http\Request;
use App\Http\Responses\response;
use App\Services\AdminService;
use App\Http\Requests\Donation\DonationForCompaingRequest;
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


public function totalAssociationDonationsByYear($id , $year): JsonResponse {
    $data = [];
    try {
        $data = $this->adminService->totalAssociationDonationsByYear($id ,$year);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}


public function getMonthlyDonationsByYear($id ,$year): JsonResponse {
    $data = [];
    try {
        $data = $this->adminService->getMonthlyDonationsByYear($id ,$year);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}


public function getActiveCampaignsCount($id ,$year): JsonResponse {
    $data = [];
    try {
        $data = $this->adminService->getActiveCampaignsCount($id,$year);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function getCompleteCampaignsCount($id , $year): JsonResponse {
    $data = [];
    try {
        $data = $this->adminService->getCompleteCampaignsCount($id , $year);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function getDonationCountsByClassByYear($id , $year): JsonResponse {
    $data = [];
    try {
        $data = $this->adminService->getDonationCountsByClassByYear($id , $year);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}

public function AssociationDetails($id ): JsonResponse {
    $data = [];
    try {
        $data = $this->adminService->getDonationCountsByClassByYear($id );
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


}
