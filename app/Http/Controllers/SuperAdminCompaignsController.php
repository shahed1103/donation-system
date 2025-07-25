<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SuperAdminCompaignsService;
use Storage;
use App\Http\Responses\response;
use App\Services\IndividualCompaignsService;
use App\Http\Requests\IndividualCompaings\CreateIndividualCompaingsRequest;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;


class SuperAdminCompaignsController extends Controller
{

protected SuperAdminCompaignsService $superAdminCompaignsService;

public function __construct(SuperAdminCompaignsService $superAdminCompaignsService)
{
    $this->superAdminCompaignsService = $superAdminCompaignsService;
}


public function getAssociations(): JsonResponse {
    $data = [];
    try {
        $data = $this->superAdminCompaignsService->getAssociations();
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}
}
