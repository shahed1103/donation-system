<?php

namespace App\Http\Controllers;

use Storage;
use Illuminate\Http\Request;
use App\Http\Responses\response;
use App\Services\AssociationCompaignsService;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;


class AssociationCompaignsController extends Controller
{
    private AssociationCompaignsService $associationCompaignsService;

    public function __construct(AssociationCompaignsService  $associationCompaignsService){
        $this->associationCompaignsService = $associationCompaignsService;
    }

    // view associations compaings active
    public function viewAssociationsCompaingsActive($id): JsonResponse {
        $data = [] ;
        try{
            $data = $this->associationCompaignsService->viewAssociationsCompaingsActive($id);
           return Response::Success($data['associations Campaigns'], $data['message']);
        }
        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message;
            return Response::Error($data , $message , $errors);
        }
    }

    // view association compaings complete for spicific association
    public function viewAssociationCompaingsComplete($id): JsonResponse {
        $data = [] ;
        try{
            $data = $this->associationCompaignsService->viewAssociationCompaingsComplete($id);
           return Response::Success($data['campaign'], $data['message']);
        }
        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message;
            return Response::Error($data , $message , $errors);
        }
    }

    //Get specific association details
    public function showAssociationDetails($id): JsonResponse {
        $data = [] ;
        try{
            $data = $this->associationCompaignsService->showAssociationDetails($id);
           return Response::Success($data['association'], $data['message']);
        }
        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message;
            return Response::Error($data , $message , $errors);
        }
    }

    // Get association campaign details 
    public function showCampaignDetails($campaignId): JsonResponse {
        $data = [] ;
        try{
            $data = $this->associationCompaignsService->showCampaignDetails($campaignId);
           return Response::Success($data['campaign'], $data['message']);
        }
        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message;
            return Response::Error($data , $message , $errors);
        }
    }

        public function searchCampaigns(Request $request): JsonResponse {
        $data = [] ;
        try{
            $data = $this->associationCompaignsService->searchCampaigns( $request);
           return Response::Success($data['campaign'], $data['message']);
        }
        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message;
            return Response::Error($data , $message , $errors);
        }
    }
}
