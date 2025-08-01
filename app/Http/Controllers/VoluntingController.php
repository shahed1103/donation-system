<?php

namespace App\Http\Controllers;

use Storage;
use Illuminate\Http\Request;
use App\Http\Responses\response;
use App\Services\VoluntingService;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class VoluntingController extends Controller
{
    private VoluntingService $voluntingService;

    public function __construct(VoluntingService  $voluntingService){
        $this->voluntingService = $voluntingService;
    }

    public function getAllVoluntingCampigns() : JsonResponse{
  
        $data = [];
        try{
           $data = $this->voluntingService->getAllVoluntingCampigns();
           return Response::Success($data['Volunting campaigns'], $data['message']);
        }

        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message;
            return Response::Error($data , $message , $errors);
        }
    }

     public function getVoluntingCampigndetails($campaignId)  : JsonResponse{
  
        $data = [];
        try{
           $data = $this->voluntingService->getVoluntingCampigndetails($campaignId);
           return Response::Success($data['Volunting campaign'], $data['message']);
        }

        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message;
            return Response::Error($data , $message , $errors);
        }
    }

   //get task details
   public function getTaskDetails($taskID) : JsonResponse{
        $data = [];
        try{
           $data = $this->voluntingService->getTaskDetails($taskID);
           return Response::Success($data['task'], $data['message']);
        }

        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message;
            return Response::Error($data , $message , $errors);
        }
   }

   //volunting request
   public function voluntingRequest($taskId) : JsonResponse{
        $data = [];
        try{
           $data = $this->voluntingService->voluntingRequest($taskId);
           return Response::Success($data['volunting request'], $data['message']);
        }

        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message;
            return Response::Error($data , $message , $errors);
        }
   }
}