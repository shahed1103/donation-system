<?php

namespace App\Http\Controllers;

use Storage;
use Illuminate\Http\Request;
use App\Http\Responses\response;
use App\Services\MobileHomeService;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;


class MobileHomeController extends Controller
{
    private MobileHomeService $mobileHomeService;

    public function __construct(MobileHomeService  $mobileHomeService){
        $this->mobileHomeService = $mobileHomeService;
    }

    //search campigns
    public function searchCampaigns(Request $request): JsonResponse {
        $data = [] ;
        try{
            $data = $this->mobileHomeService->searchCampaigns($request);
           return Response::Success($data['campaign'], $data['message']);
        }
        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message;
            return Response::Error($data , $message , $errors);
        }
    }

    // view emergency compaings active
    public function  emergencyCompaings(): JsonResponse {
        $data = [] ;
        try{
            $data = $this->mobileHomeService->emergencyCompaings();
           return Response::Success($data['emergency compaings'], $data['message']);
        }
        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message;
            return Response::Error($data , $message , $errors);
        }
    }

    // count associations 
    public function  countAssociationsMob(): JsonResponse {
        $data = [] ;
        try{
            $data = $this->mobileHomeService->countAssociationsMob();
           return Response::Success($data['count'], $data['message']);
        }
        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message;
            return Response::Error($data , $message , $errors);
        }
    }

    // count of total campaigns
    public function getEndedCampaignsCountByYearMob(): JsonResponse{
        $data = [] ;
        try{
            $data = $this->mobileHomeService->getEndedCampaignsCountByYearMob();
           return Response::Success($data['count'], $data['message']);
        }
        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message;
            return Response::Error($data , $message , $errors);
        }
    }

    public function totalDonationsByYearMob():  JsonResponse{
        $data = [] ;
        try{
            $data = $this->mobileHomeService->totalDonationsByYearMob();
           return Response::Success($data['total'], $data['message']);
        }
        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message;
            return Response::Error($data , $message , $errors);
        }
    }

        public function totalInkindDonationsByYearMob():  JsonResponse{
        $data = [] ;
        try{
            $data = $this->mobileHomeService->totalInkindDonationsByYearMob();
           return Response::Success($data['total'], $data['message']);
        }
        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message;
            return Response::Error($data , $message , $errors);
        }
    }
    
}

