<?php

namespace App\Http\Controllers;

use Storage;
use Illuminate\Http\Request;
use App\Http\Responses\response;
use App\Services\AdminService;
use App\Http\Requests\Donation\DonationForCompaingRequest;
use App\Http\Requests\Donation\WalletDonationForCompaingRequest;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class AdminController extends Controller
{
    private AdminService $adminService;

    public function __construct(AdminService  $adminService){
        $this->adminService = $adminService;
    }





}
