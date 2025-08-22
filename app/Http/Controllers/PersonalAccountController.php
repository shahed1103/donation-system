<?php

namespace App\Http\Controllers;

use Storage;
use Illuminate\Http\Request;
use App\Http\Responses\response;
use App\Services\PersonalAccountService;
use Illuminate\Http\JsonResponse;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Http\Requests\VoluntingProfile\CreateVoluntingProfileRequest;
use App\Http\Requests\VoluntingProfile\UpdateVoluntingProfileRequest;
use App\Http\Requests\Auth\UpdatePersonalProfileRequest;
use App\Http\Requests\Wallet\WalletRequest;



class PersonalAccountController extends Controller
{
    private PersonalAccountService $personalAccountService;

    public function __construct(PersonalAccountService  $personalAccountService){
        $this->personalAccountService = $personalAccountService;
    }

   //get mini information
    public function miniIfo(): JsonResponse {
        $data = [] ;
        try{
            $data = $this->personalAccountService->miniIfo();
           return Response::Success($data['personal inforamation'], $data['message']);
        }
        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message;
            return Response::Error($data , $message , $errors);
        }
    }

        //my achievements
        //1 - summry about my achievements
        public function mySummryAchievements(): JsonResponse{
        $data =[];
        try{
           $data = $this->personalAccountService->mySummryAchievements();
           return Response::Success($data['my summry achievements'] , $data['message']);
        }

        catch(Throwable $th){
           $message = $th->getMessage();
           $errors [] = $message;
           return Response::Error($data , $message , $errors);
        }
    }

    //2 - the campigns that user donate for it //my donation
    public function mydonations(): JsonResponse{
        $data =[];
        try{
           $data = $this->personalAccountService->mydonations();
           return Response::Success($data['my donations'] , $data['message']);
        }

        catch(Throwable $th){
           $message = $th->getMessage();
           $errors [] = $message;
           return Response::Error($data , $message , $errors);
        }
    }

    //3 - the campigns that user voluntee in  //my volunting
    public function myVoluntings(): JsonResponse{
        $data =[];
        try{
           $data = $this->personalAccountService->myVoluntings();
           return Response::Success($data['my voluntings'] , $data['message']);
        }

        catch(Throwable $th){
           $message = $th->getMessage();
           $errors [] = $message;
           return Response::Error($data , $message , $errors);
        }
    }

    //4 - the most campigns that user donate for it 
    public function mostDonationFor(): JsonResponse{
        $data =[];
        try{
           $data = $this->personalAccountService->mostDonationFor();
           return Response::Success($data['most campaign donation for'] , $data['message']);
        }

        catch(Throwable $th){
           $message = $th->getMessage();
           $errors [] = $message;
           return Response::Error($data , $message , $errors);
        }
    }

    // Create Volunting Profile
    public function createVoluntingProfile(CreateVoluntingProfileRequest $request): JsonResponse{
        $data =[];
        try{
           $data = $this->personalAccountService->createVoluntingProfile($request);
           return Response::Success($data['volunting profile'] , $data['message']);
        }

        catch(Throwable $th){
           $message = $th->getMessage();
           $errors [] = $message;
            $code = $th->getCode();
            return Response::ErrorX($data , $message , $errors , $code );
        }
    }    

    // Update Volunting Profile
    public function updateVoluntingProfile(UpdateVoluntingProfileRequest $request): JsonResponse{
        $data =[];
        try{
           $data = $this->personalAccountService->updateVoluntingProfile($request);
           return Response::Success($data['volunting profile'] , $data['message']);
        }

        catch(Throwable $th){
           $message = $th->getMessage();
           $errors [] = $message;
           return Response::Error($data , $message , $errors );
        }
    } 
    
    // show Volunting Profile
    public function showVoluntingProfile(): JsonResponse{
        $data =[];
        try{
           $data = $this->personalAccountService->showVoluntingProfile();
           return Response::Success($data['volunting profile'] , $data['message']);
        }

        catch(Throwable $th){
           $message = $th->getMessage();
           $errors [] = $message;
           return Response::Error($data , $message , $errors );
        }
    } 

    // show Volunting Profile details
    public function showVoluntingProfileDetails(): JsonResponse{
        $data =[];
        try{
           $data = $this->personalAccountService->showVoluntingProfileDetails();
           return Response::Success($data['volunting profile'] , $data['message']);
        }

        catch(Throwable $th){
           $message = $th->getMessage();
           $errors [] = $message;
           return Response::Error($data , $message , $errors );
        }
    } 

    //show my personal profile information
    public function showAllInfo(): JsonResponse{
        $data =[];
        try{
           $data = $this->personalAccountService->showAllInfo();
           return Response::Success($data['personal profile'] , $data['message']);
        }

        catch(Throwable $th){
           $message = $th->getMessage();
           $errors [] = $message;
           return Response::Error($data , $message , $errors );
        }
     }

    //edit my personal profile information
    public function editPersonalInfo(UpdatePersonalProfileRequest $request): JsonResponse{
        $data =[];
        try{
           $data = $this->personalAccountService->editPersonalInfo($request);
           return Response::Success($data['personal profile'] , $data['message']);
        }

        catch(Throwable $th){
           $message = $th->getMessage();
           $errors [] = $message;
           return Response::Error($data , $message , $errors );
        }
     }

    //create wallet for user
    public function createWallet(WalletRequest $request): JsonResponse {
        $data =[];
        try{
           $data = $this->personalAccountService->createWallet($request);
           return Response::Success($data['wallet'] , $data['message']);
        }

        catch(Throwable $th){
           $message = $th->getMessage();
           $errors [] = $message;
            $code = $th->getCode();
            return Response::ErrorX($data , $message , $errors , $code );
        }
     }

     //show wallet
     public function showWallet(): JsonResponse {
        $data =[];
        try{
           $data = $this->personalAccountService->showWallet();
           return Response::Success($data['wallet'] , $data['message']);
        }

        catch(Throwable $th){
           $message = $th->getMessage();
           $errors [] = $message;
            $code = $th->getCode();
            return Response::ErrorX($data , $message , $errors , $code );        }
     }
   }