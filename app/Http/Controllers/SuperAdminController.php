<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AssociationService;


class SuperAdminController extends Controller
{

protected AssociationService $associationService;

public function __construct(AssociationService $associationService)
{
    $this->associationService = $associationService;
}


public function countAssociations(): JsonResponse {
    $data = [];
    try {
        $data = $this->associationService->countAssociations();
        return Response::Success($data, $data['message']);
    } catch (Throwable $th) {
        $message = $th->getMessage();
        $errors[] = $message;
        return Response::Error($data, $message, $errors);
    }
}


}
