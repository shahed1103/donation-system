<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Storage;
use App\Http\Responses\response;
use App\Services\IndividualCompaignsService;
use App\Services\LeaderService;
use App\Http\Requests\Leader\LeaderFormRequest;

use Illuminate\Http\JsonResponse;
use App\Models\Leader_form;
use Illuminate\Support\Facades\Auth;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;


class LeaderController extends Controller
{
protected LeaderService $leaderService;

public function __construct(LeaderService $leaderService)
{
    $this->leaderService = $leaderService;
}

public function addLeaderForm(LeaderFormRequest $request, $id): JsonResponse {
    $data = [];
    try {
        $data = $this->leaderService->addLeaderForm($request->validated() ,$id);
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}




public function UnderReviewIndiviCompaign(): JsonResponse {
    $data = [];
    try {
        $data = $this->leaderService->UnderReviewIndiviCompaign();
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}


}
