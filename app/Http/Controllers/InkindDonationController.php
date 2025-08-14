<?php

namespace App\Http\Controllers;

use Storage;
use Illuminate\Http\Request;
use App\Http\Responses\response;
use App\Services\InkindDonationService;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use App\Http\Requests\InkindDonation\AddInkindDonationRequest;
use App\Http\Requests\InkindDonation\ReserveInkindDonationAmountRequest;
use Illuminate\Support\Facades\Auth;
use Throwable;


use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;


class InkindDonationController extends Controller
{
    private InkindDonationService $inkindDonationService;

    public function __construct(InkindDonationService  $inkindDonationService){
        $this->inkindDonationService = $inkindDonationService;
    }

    //show all in-kind donations
    public function showAllInkindDonations(): JsonResponse {
        $data = [] ;
        try{
            $data = $this->inkindDonationService->showAllInkindDonations();
           return Response::Success($data['Inkind Donations All'], $data['message']);
        }
        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message;
            return Response::Error($data , $message , $errors);
        }
    }

    //show details for in-kind donation
    public function showInkindDonationDetails($id): JsonResponse {
        $data = [] ;
        try{
            $data = $this->inkindDonationService->showInkindDonationDetails($id);
           return Response::Success($data['Inkind Donation Details'], $data['message']);
        }
        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message;
            return Response::Error($data , $message , $errors);
        }
    }

    //show details for in-kind donation
    public function searchForNearestInkindDonation($location): JsonResponse {
        $data = [] ;
        try{
            $data = $this->inkindDonationService->searchForNearestInkindDonation($location);
           return Response::Success($data['Inkind Donations All'], $data['message']);
        }
        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message;
            return Response::Error($data , $message , $errors);
        }
    }

    //add in-kind donation
    public function addInkindDonation(AddInkindDonationRequest $request): JsonResponse {
        $data = [] ;
        try{
            $data = $this->inkindDonationService->addInkindDonation($request);
           return Response::Success($data['Inkind Donation'], $data['message']);
        }
        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message;
            return Response::Error($data , $message , $errors);
        }
    }

    //reserve in-kind donation
    public function reserveInkindDonation(ReserveInkindDonationAmountRequest $request , $id):JsonResponse {
        $data = [] ;
        try{
            $data = $this->inkindDonationService->reserveInkindDonation($request , $id);
           return Response::Success($data['Inkind Donation'], $data['message']);
        }
        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message;
            return Response::Error($data , $message , $errors);
        }
    }

    //1 view all centers
    public function getCenter():JsonResponse {
        $data = [] ;
        try{
            $data = $this->inkindDonationService->getCenter();
           return Response::Success($data['center'], $data['message']);
        }
        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message;
            return Response::Error($data , $message , $errors);
        }
    }

    //2 view all in-kind donations types
    public function getInkindDonationTypes():JsonResponse {
        $data = [] ;
        try{
            $data = $this->inkindDonationService->getInkindDonationTypes();
           return Response::Success($data['donation_type'], $data['message']);
        }
        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message;
            return Response::Error($data , $message , $errors);
        }
    }

    //3 view all in-kind donation statues of object
    public function getStatusOfDonation():JsonResponse {
        $data = [] ;
        try{
            $data = $this->inkindDonationService->getStatusOfDonation();
           return Response::Success($data['status_Of_donation'], $data['message']);
        }
        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message;
            return Response::Error($data , $message , $errors);
        }
    }
}