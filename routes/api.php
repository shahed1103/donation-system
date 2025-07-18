<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IndividualCompaignsController;
use App\Http\Controllers\SuperAdminController;

use App\Http\Requests\Auth\UserSignupRequest;
use App\Http\Requests\Auth\UserSigninRequest;





/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(function(){
    Route::post('register' , 'register')
          ->name('user.register');

    Route::post('signin' , 'signin')
    ->name('user.signin');

    Route::post('userForgotPassword' , 'userForgotPassword')
    ->name('user.password.email');

Route::post('userCheckCode' , 'userCheckCode')
->name('user.password.code.check');

Route::post('userResetPassword' , 'userResetPassword')
->name('user.password.reset');

Route::middleware('auth:sanctum')->get('logout', [AuthController::class, 'logout'])->name('user.logout');
});



Route::controller(IndividualCompaignsController::class)->group(function(){

Route::get('getClassification' , 'getClassification')
->name('all.getClassification');

Route::middleware('auth:sanctum')->post('createIndiviCompa', [IndividualCompaignsController::class, 'createIndiviCompa'])->name('user.createIndiviCompa');
Route::middleware('auth:sanctum')->get('viewMyIndiviCompa', [IndividualCompaignsController::class, 'viewMyIndiviCompa'])->name('user.viewMyIndiviCompa');
Route::get('viewIndiviCompa/{id}' , 'viewIndiviCompa')
    ->name('user.viewIndiviCompa');

});

Route::controller(AssociationCompaignsController::class)->group(function(){

Route::get('viewAssociationsCompaingsActive/{id}' , 'viewAssociationsCompaingsActive')
    ->name('user.viewAssociationsCompaingsActive');
});

Route::controller(SuperAdminController::class)->group(function(){
Route::middleware('auth:sanctum')->get('countAssociations', [SuperAdminController::class, 'countAssociations'])->name('super_admin.countAssociations');

});

// Route::controller(SuperAdminController::class)->group(function(){
//     Route::get('countAssociations' , 'countAssociations')
//           ->name('super_admin.countAssociations');
// });
