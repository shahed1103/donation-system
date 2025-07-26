<?php

namespace App\Http\Controllers;

use Storage;
use Illuminate\Http\Request;
use App\Http\Responses\response;
use App\Services\IndividualCompaignsService;
use App\Http\Requests\IndividualCompaings\CreateIndividualCompaingsRequest;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;


class IndividualCompaignsController extends Controller
{
    private IndividualCompaignsService $individualCompaignsService;

    public function __construct(IndividualCompaignsService  $individualCompaignsService){
        $this->individualCompaignsService = $individualCompaignsService;
    }

    public function createIndiviCompa(CreateIndividualCompaingsRequest $request): JsonResponse {
        $data = [] ;
        try{
            $data = $this->individualCompaignsService->createIndiviCompa($request->validated(),$request );
           return Response::Success($data['campaign'], $data['message']);
        }
        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message;
            return Response::Error($data , $message , $errors);
        }
    }

    // view my individual compaings active + closed
    public function viewMyIndiviCompa(): JsonResponse {
        $data = [] ;
        try{
            $data = $this->individualCompaignsService->viewMyIndiviCompa();
           return Response::Success($data['campaign'], $data['message']);
        }
        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message;
            return Response::Error($data , $message , $errors);
        }
    }

        // view individual compaings active
    public function viewIndiviCompa($id): JsonResponse {
        $data = [] ;
        try{
            $data = $this->individualCompaignsService->viewIndiviCompa($id);
           return Response::Success($data['campaign'], $data['message']);
        }
        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message;
            return Response::Error($data , $message , $errors);
        }
    }

    // Get individual campaign details 
    public function showIndiviCampaignDetails($campaignId){
                $data = [] ;
        try{
            $data = $this->individualCompaignsService->showIndiviCampaignDetails($campaignId);
           return Response::Success($data['campaign'], $data['message']);
        }
        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message;
            return Response::Error($data , $message , $errors);
        }
    }

     // view all
    //1 view all classifications
    public function getClassification(): JsonResponse {
        $data = [] ;
        try{
            $data = $this->individualCompaignsService->getClassification();
           return Response::Success($data['classifications'], $data['message']);
        }
        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message;
            return Response::Error($data , $message , $errors);
        }
    }

    //2 view all Availability Type
    public function getAvailabilityType(): JsonResponse {
        $data = [] ;
        try{
            $data = $this->individualCompaignsService->getAvailabilityType();
           return Response::Success($data['availabilityTypes'], $data['message']);
        }
        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message;
            return Response::Error($data , $message , $errors);
        }
    }

    //3 view all cities
    public function getCities(): JsonResponse {
        $data = [] ;
        try{
            $data = $this->individualCompaignsService->getCities();
           return Response::Success($data['cities'], $data['message']);
        }
        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message;
            return Response::Error($data , $message , $errors);
        }
    }

    //4 view all genders
    public function getGender(): JsonResponse {
        $data = [] ;
        try{
            $data = $this->individualCompaignsService->getGender();
           return Response::Success($data['gender'], $data['message']);
        }
        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message;
            return Response::Error($data , $message , $errors);
        }
    }

}
