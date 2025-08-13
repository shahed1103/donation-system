<?php

namespace App\Http\Controllers;

use Storage;
use Illuminate\Http\Request;
use App\Http\Responses\response;
use App\Services\InkindDonationService;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Throwable;


use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;


class InkindDonationController extends Controller
{
    private InkindDonationService $inkindDonationService;

    public function __construct(InkindDonationService  $inkindDonationService){
        $this->inkindDonationService = $inkindDonationService;
    }

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
}