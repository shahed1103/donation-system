<?php

namespace App\Http\Controllers;

use Storage;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\UserSigninRequest;
use App\Http\Requests\Auth\UserSignupRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\CheckCodeRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Responses\response;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use App\Models\ResetCodePassword;
use App\Models\User;
use App\Mail\SendCodeResetPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;


class AuthController extends Controller
{
    private UserService $userService;

    public function __construct(UserService  $userService){
        $this->userService = $userService;
    }

    public function register(UserSignupRequest $request): JsonResponse {
        $data = [] ;
        try{
            $data = $this->userService->register($request->validated(),$request );
            return Response::Success($data['user'], $data['message']);
        }
        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message;
            return Response::Error($data , $message , $errors);
        }
    }

    public function signin(UserSigninRequest $request): JsonResponse {
        $data = [] ;
       try{
            $data = $this->userService->signin($request);
            return Response::Success($data['user'], $data['message'], $data['code']);
       }
        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message ;
            $code = $th->getCode();
            return Response::Errorx($data , $message , $errors , $code );
        }
    }

    public function logout(): JsonResponse {
        $data = [] ;
        try{
            $data = $this->userService->logout();
            return Response::Success($data['user'], $data['message'], $data['code']);
        }
        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message;
            $code = $th->getCode();
            return Response::Errorx($data , $message , $errors , $code );
        }
    }

    public function userForgotPassword(ForgotPasswordRequest $request): JsonResponse{
        $data = [] ;
        try{
            $data = $this->userService->forgotPassword($request);
            return Response::Success($data['user'], $data['message'], $data['code']);
        }
        catch(Throwable $th){
            $message = $th->getMessage();
            $errors [] = $message;

            return Response::Error($data , $message , $errors);
        }
    }

    public function userCheckCode(CheckCodeRequest $request): JsonResponse {
        $data = [];
        try{
            $data = $this->userService->checkCode($request);
            return Response::Success($data['verifyCode'], $data['message'], $data['code']);
        }

            catch(Throwable $th){
                $message = $th->getMessage();
                $errors [] = $message;

                return Response::Error($data , $message , $errors);
        }
    }

    public function userResetPassword(ResetPasswordRequest $request): JsonResponse {

        $data = [];
       try{
            $data = $this->userService->resetPassword($request);
            return Response::Success($data['role'], $data['message'] , $data['code'] );
       }

            catch(Throwable $th){
                $message = $th->getMessage();
                $errors [] = $message;
                $code = $th->getCode();
                return Response::Errorx($data , $message , $errors , $code);
        }
    }
}
