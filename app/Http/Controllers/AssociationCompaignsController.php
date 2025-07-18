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

    // view association compaings closed for spicific association
    public function viewAssociationCompaingsClosed($id): JsonResponse {
        $data = [] ;
        try{
            $data = $this->associationCompaignsService->viewAssociationCompaingsClosed($id);
           return Response::Success($data['campaign'], $data['message']);
        }
        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message;
            return Response::Error($data , $message , $errors);
        }
    }

}
