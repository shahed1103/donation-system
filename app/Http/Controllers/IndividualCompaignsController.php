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
    //view all comp end
}
