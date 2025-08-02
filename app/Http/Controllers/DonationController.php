<?php

namespace App\Http\Controllers;

use Storage;
use Illuminate\Http\Request;
use App\Http\Responses\response;
use App\Services\DonationService;
use App\Http\Requests\Donation\DonationForCompaingRequest;
use App\Http\Requests\Donation\WalletDonationForCompaingRequest;

use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class  DonationController extends Controller
{
    private DonationService $donationService;

    public function __construct(DonationService  $donationService){
        $this->donationService = $donationService;
    }

    // donation with points for association campaign
    public function donateWithPoints(DonationForCompaingRequest $request , $campaignType , $campaignId): JsonResponse {
        $data = [] ;
        try{
            $data = $this->donationService->donateWithPoints($request , $campaignType , $campaignId);
           return Response::Success($data['donation'], $data['message']);
        }
        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message;
            return Response::Error($data , $message , $errors);
        }
    }
    
    // donation with wallet money for campaign
    public function donateWithWallet(WalletDonationForCompaingRequest $request , $campaignType , $campaignId): JsonResponse {
        $data = [] ;
        try{
            $data = $this->donationService->donateWithWallet($request , $campaignType , $campaignId);
           return Response::Success($data['donation'], $data['message']);
        }
        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message;
            return Response::Error($data , $message , $errors);
        }
    }  }
