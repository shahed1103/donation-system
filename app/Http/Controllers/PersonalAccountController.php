<?php

namespace App\Http\Controllers;

use Storage;
use Illuminate\Http\Request;
use App\Http\Responses\response;
use App\Services\PersonalAccountService;
use Illuminate\Http\JsonResponse;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;


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

}
