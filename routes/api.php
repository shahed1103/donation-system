<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AssociationCompaignsController;
use App\Http\Controllers\IndividualCompaignsController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\SuperAdminCompaignsController;


use App\Http\Controllers\PersonalAccountController;
// use App\Http\Requests\Auth\UserSignupRequest;
// use App\Http\Requests\Auth\UserSigninRequest;
use App\Http\Controllers\MobileHomeController;

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

Route::get('getAvailabilityType' , 'getAvailabilityType')
->name('all.getAvailabilityType');

Route::get('getCities' , 'getCities')
->name('all.getCities');

Route::middleware('auth:sanctum')->post('createIndiviCompa', [IndividualCompaignsController::class, 'createIndiviCompa'])->name('user.createIndiviCompa');
Route::middleware('auth:sanctum')->get('viewMyIndiviCompa', [IndividualCompaignsController::class, 'viewMyIndiviCompa'])->name('user.viewMyIndiviCompa');
Route::get('viewIndiviCompa/{id}' , 'viewIndiviCompa')
    ->name('user.viewIndiviCompa');

    //individual campaign id
Route::get('showIndiviCampaignDetails/{campaignId}' , 'showIndiviCampaignDetails')
    ->name('user.showIndiviCampaignDetails');

});

Route::controller(AssociationCompaignsController::class)->group(function(){

    //classification id
Route::get('viewAssociationsCompaingsActive/{id}' , 'viewAssociationsCompaingsActive')
    ->name('user.viewAssociationsCompaingsActive');

    //association id
Route::get('viewAssociationCompaingsComplete/{id}' , 'viewAssociationCompaingsComplete')
    ->name('user.viewAssociationCompaingsComplete');

    //association id
Route::get('showAssociationDetails/{id}' , 'showAssociationDetails')
    ->name('user.showAssociationDetails');

    //association campaign id
Route::get('showCampaignDetails/{campaignId}' , 'showCampaignDetails')
    ->name('user.showCampaignDetails');
});

Route::controller(MobileHomeController::class)->group(function(){
    Route::post('searchCampaigns' , 'searchCampaigns')
        ->name('user.searchCampaigns');

    Route::get('emergencyCompaings' , 'emergencyCompaings')
        ->name('user.emergencyCompaings');
});

Route::controller(PersonalAccountController::class)->group(function(){
    Route::middleware('auth:sanctum')->get('miniIfo', [PersonalAccountController::class, 'miniIfo'])->name('user.miniIfo');
    Route::middleware('auth:sanctum')->get('mydonations', [PersonalAccountController::class, 'mydonations'])->name('user.mydonations');
    Route::middleware('auth:sanctum')->get('myVoluntings', [PersonalAccountController::class, 'myVoluntings'])->name('user.myVoluntings');
    Route::middleware('auth:sanctum')->get('mostDonationFor', [PersonalAccountController::class, 'mostDonationFor'])->name('user.mostDonationFor');
    Route::middleware('auth:sanctum')->get('mySummryAchievements', [PersonalAccountController::class, 'mySummryAchievements'])->name('user.mySummryAchievements');
    Route::middleware('auth:sanctum')->post('createVoluntingProfile', [PersonalAccountController::class, 'createVoluntingProfile'])->name('user.createVoluntingProfile');
    Route::middleware('auth:sanctum')->post('updateVoluntingProfile', [PersonalAccountController::class, 'updateVoluntingProfile'])->name('user.updateVoluntingProfile');
    Route::middleware('auth:sanctum')->get('showVoluntingProfile', [PersonalAccountController::class, 'showVoluntingProfile'])->name('user.showVoluntingProfile');
    Route::middleware('auth:sanctum')->get('showVoluntingProfileDetails', [PersonalAccountController::class, 'showVoluntingProfileDetails'])->name('user.showVoluntingProfileDetails');

    Route::middleware('auth:sanctum')->post('updateVoluntingProfile', [PersonalAccountController::class, 'updateVoluntingProfile'])->name('user.updateVoluntingProfile');

    Route::middleware('auth:sanctum')->post('updateVoluntingProfile', [PersonalAccountController::class, 'updateVoluntingProfile'])->name('user.updateVoluntingProfile');
    Route::middleware('auth:sanctum')->get('showVoluntingProfile', [PersonalAccountController::class, 'showVoluntingProfile'])->name('user.showVoluntingProfile');
    Route::middleware('auth:sanctum')->get('showVoluntingProfileDetails', [PersonalAccountController::class, 'showVoluntingProfileDetails'])->name('user.showVoluntingProfileDetails');
});




Route::controller(SuperAdminController::class)->group(function(){
Route::middleware('auth:sanctum')->get('countAssociations', [SuperAdminController::class, 'countAssociations'])->name('super_admin.countAssociations');
Route::middleware('auth:sanctum')->get('lastNewUsers', [SuperAdminController::class, 'lastNewUsers'])->name('super_admin.lastNewUsers');
Route::middleware('auth:sanctum')->get('getUserCountsLastFiveYears', [SuperAdminController::class, 'getUserCountsLastFiveYears'])->name('super_admin.getUserCountsLastFiveYears');
Route::middleware('auth:sanctum')->get('getTotalCampaignsCountByYear/{year}', [SuperAdminController::class, 'getTotalCampaignsCount'])->name('super_admin.getTotalCampaignsCount');
Route::middleware('auth:sanctum')->get('getUserCountsByRoleByYear/{year}', [SuperAdminController::class, 'getUserCountsByRoleByYear'])->name('super_admin.getUserCountsByRoleByYear');
Route::middleware('auth:sanctum')->get('usersCountByYear/{year}', [SuperAdminController::class, 'usersCountByYear'])->name('super_admin.usersCountByYear');
Route::middleware('auth:sanctum')->get('totalDonationsByYear/{year}', [SuperAdminController::class, 'totalDonationsByYear'])->name('super_admin.totalDonationsByYear');
Route::middleware('auth:sanctum')->get('getCityDonationPercentagesByYear/{year}', [SuperAdminController::class, 'getCityDonationPercentagesByYear'])->name('super_admin.getCityDonationPercentagesByYear');
Route::middleware('auth:sanctum')->get('getMonthlyDonationsByYear/{year}', [SuperAdminController::class, 'getMonthlyDonationsByYear'])->name('super_admin.getMonthlyDonationsByYear');
Route::middleware('auth:sanctum')->get('getEndedCampaignsCountByYear/{year}', [SuperAdminController::class, 'getEndedCampaignsCountByYear'])->name('super_admin.getEndedCampaignsCountByYear');
Route::middleware('auth:sanctum')->get('getDonorsAndVolunteers', [SuperAdminController::class, 'getDonorsAndVolunteers'])->name('super_admin.getDonorsAndVolunteers');
Route::middleware('auth:sanctum')->get('getTeamLeaders', [SuperAdminController::class, 'getTeamLeaders'])->name('super_admin.getTeamLeaders');
Route::middleware('auth:sanctum')->get('createLeader', [SuperAdminController::class, 'createLeader'])->name('super_admin.createLeader');
Route::middleware('auth:sanctum')->get('deleteLeader/{id}', [SuperAdminController::class, 'deleteLeader'])->name('super_admin.deleteLeader');


});



Route::controller(SuperAdminCompaignsController::class)->group(function(){
Route::middleware('auth:sanctum')->get('getAssociations', [SuperAdminCompaignsController::class, 'getAssociations'])->name('super_admin.getAssociations');
Route::middleware('auth:sanctum')->get('getAssociationsCampaignsActive/{id}', [SuperAdminCompaignsController::class, 'getAssociationsCampaignsActive'])->name('super_admin.getAssociationsCampaignsActive');
Route::middleware('auth:sanctum')->get('getAssociationCompaingsComplete/{id}', [SuperAdminCompaignsController::class, 'getAssociationCompaingsComplete'])->name('super_admin.getAssociationCompaingsComplete');
Route::middleware('auth:sanctum')->get('getAssociationCompaingsClosed/{id}', [SuperAdminCompaignsController::class, 'getAssociationCompaingsClosed'])->name('super_admin.getAssociationCompaingsClosed');

Route::middleware('auth:sanctum')->get('getClosedRejectedIndiviCampaigns/{id}', [SuperAdminCompaignsController::class, 'getClosedRejectedIndiviCampaigns'])->name('super_admin.getClosedRejectedIndiviCampaigns');
Route::middleware('auth:sanctum')->get('getClosedPendingIndiviCampaigns/{id}', [SuperAdminCompaignsController::class, 'getClosedPendingIndiviCampaigns'])->name('super_admin.getClosedPendingIndiviCampaigns');
Route::middleware('auth:sanctum')->get('getCompleteIndiviCompaign/{id}', [SuperAdminCompaignsController::class, 'getCompleteIndiviCompaign'])->name('super_admin.getCompleteIndiviCompaign');
Route::middleware('auth:sanctum')->get('getActiveIndiviCompaign/{id}', [SuperAdminCompaignsController::class, 'getActiveIndiviCompaign'])->name('super_admin.getActiveIndiviCompaign');


});



Route::controller(SuperAdminCompaignsController::class)->group(function(){
    Route::get('getActiveIndiviCompaign' , 'getActiveIndiviCompaign')
          ->name('user.getActiveIndiviCompaign');
});


// Route::controller(SuperAdminController::class)->group(function(){
//     Route::post('createLeader' , 'createLeader')
//           ->name('super_admin.createLeader');

// });
