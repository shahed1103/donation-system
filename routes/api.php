<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AssociationCompaignsController;
use App\Http\Controllers\VoluntingController;
use App\Http\Controllers\IndividualCompaignsController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\LeaderController;
use App\Http\Controllers\SuperAdminIndividualCompaignsController;
use App\Http\Controllers\SuperAdminAssociationCompaignsController;
use App\Http\Controllers\PersonalAccountController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\AdminController;

use App\Http\Controllers\InkindDonationController;

use App\Http\Controllers\FcmController;


// use App\Http\Requests\Auth\UserSignupRequest;
// use App\Http\Requests\Auth\UserSigninRequest;
use App\Http\Controllers\MobileHomeController;

/*
|--------------------------------------------------------------------------
| API Routes
|-------------------------------------------------------- ------------------
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

Route::post('userResetPassword/{code}' , 'userResetPassword')
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

Route::get('getGender' , 'getGender')
->name('all.getGender');

Route::middleware('auth:sanctum')->group(function () {

Route::post('createIndiviCompa' , 'createIndiviCompa')
     ->name('user.createIndiviCompa')->middleware('can:createIndiviCompa');

Route::get('viewMyIndiviCompa', 'viewMyIndiviCompa')
     ->name('user.viewMyIndiviCompa')->middleware('can:viewMyIndiviCompa');

Route::get('viewIndiviCompa/{id}' , 'viewIndiviCompa')
    ->name('user.viewIndiviCompa')->middleware('can:viewIndiviCompa');

    //individual campaign id
Route::get('showIndiviCampaignDetails/{campaignId}' , 'showIndiviCampaignDetails')
    ->name('user.showIndiviCampaignDetails')->middleware('can:showIndiviCampaignDetails');

    //individual campaign id
Route::get('getUnderReviewIndiviCampaignDetailsMob/{campaignId}' , 'getUnderReviewIndiviCampaignDetailsMob')
    ->name('user.getUnderReviewIndiviCampaignDetailsMob')->middleware('can:getUnderReviewIndiviCampaignDetailsMob');

        //individual campaign id
Route::get('showRejectedIndiviCampaignDetails/{campaignId}' , 'showRejectedIndiviCampaignDetails')
    ->name('user.showRejectedIndiviCampaignDetails')->middleware('can:showRejectedIndiviCampaignDetails');
   });

});

Route::middleware('auth:sanctum')->controller(AssociationCompaignsController::class)->group(function () {

    // classification id
    Route::get('viewAssociationsCompaingsActive/{id}', 'viewAssociationsCompaingsActive')
        ->name('user.viewAssociationsCompaingsActive')
        ->middleware('can:viewAssociationsCompaingsActive');

    // association id
    Route::get('viewAssociationCompaingsComplete/{id}', 'viewAssociationCompaingsComplete')
        ->name('user.viewAssociationCompaingsComplete')
        ->middleware('can:viewAssociationCompaingsComplete');

    // association id
    Route::get('showAssociationDetails/{id}', 'showAssociationDetails')
        ->name('user.showAssociationDetails')
        ->middleware('can:showAssociationDetails');

    // association campaign id
    Route::get('showCampaignDetails/{campaignId}', 'showCampaignDetails')
        ->name('user.showCampaignDetails')
        ->middleware('can:showCampaignDetails');
});

Route::middleware('auth:sanctum')->controller(MobileHomeController::class)->group(function () {
    Route::post('searchCampaigns', 'searchCampaigns')
        ->name('user.searchCampaigns')
        ->middleware('can:searchCampaigns');

    Route::get('emergencyCompaings', 'emergencyCompaings')
        ->name('user.emergencyCompaings')
        ->middleware('can:emergencyCompaings');

    Route::get('countAssociationsMob', 'countAssociationsMob')
        ->name('user.countAssociationsMob')
        ->middleware('can:countAssociationsMob');

    Route::get('getEndedCampaignsCountByYearMob', 'getEndedCampaignsCountByYearMob')
        ->name('user.getEndedCampaignsCountByYearMob')
        ->middleware('can:getEndedCampaignsCountByYearMob');

    Route::get('totalDonationsByYearMob', 'totalDonationsByYearMob')
        ->name('user.totalDonationsByYearMob')
        ->middleware('can:totalDonationsByYearMob');
});


Route::controller(PersonalAccountController::class)->group(function(){
    Route::middleware('auth:sanctum')->get('miniIfo', [PersonalAccountController::class, 'miniIfo'])->name('user.miniIfo')->middleware('can:miniIfo');
    Route::middleware('auth:sanctum')->get('mydonations', [PersonalAccountController::class, 'mydonations'])->name('user.mydonations')->middleware('can:mydonations');
    Route::middleware('auth:sanctum')->get('myVoluntings', [PersonalAccountController::class, 'myVoluntings'])->name('user.myVoluntings')->middleware('can:myVoluntings');
    Route::middleware('auth:sanctum')->get('mostDonationFor', [PersonalAccountController::class, 'mostDonationFor'])->name('user.mostDonationFor')->middleware('can:mostDonationFor');
    Route::middleware('auth:sanctum')->get('mySummryAchievements', [PersonalAccountController::class, 'mySummryAchievements'])->name('user.mySummryAchievements')->middleware('can:mySummryAchievements');
    Route::middleware('auth:sanctum')->post('createVoluntingProfile', [PersonalAccountController::class, 'createVoluntingProfile'])->name('user.createVoluntingProfile')->middleware('can:createVoluntingProfile');
    Route::middleware('auth:sanctum')->post('updateVoluntingProfile', [PersonalAccountController::class, 'updateVoluntingProfile'])->name('user.updateVoluntingProfile')->middleware('can:updateVoluntingProfile');
    Route::middleware('auth:sanctum')->get('showVoluntingProfile', [PersonalAccountController::class, 'showVoluntingProfile'])->name('user.showVoluntingProfile')->middleware('can:showVoluntingProfile');
    Route::middleware('auth:sanctum')->get('showVoluntingProfileDetails', [PersonalAccountController::class, 'showVoluntingProfileDetails'])->name('user.showVoluntingProfileDetails')->middleware('can:showVoluntingProfileDetails');
    Route::middleware('auth:sanctum')->get('showAllInfo', [PersonalAccountController::class, 'showAllInfo'])->name('user.showAllInfo')->middleware('can:showAllInfo');
    Route::middleware('auth:sanctum')->post('editPersonalInfo', [PersonalAccountController::class, 'editPersonalInfo'])->name('user.editPersonalInfo')->middleware('can:editPersonalInfo');
    Route::middleware('auth:sanctum')->post('createWallet', [PersonalAccountController::class, 'createWallet'])->name('user.createWallet')->middleware('can:createWallet');
    Route::middleware('auth:sanctum')->get('showWallet', [PersonalAccountController::class, 'showWallet'])->name('user.showWallet')->middleware('can:showWallet');
});

Route::middleware('auth:sanctum')->controller(VoluntingController::class)->group(function () {

    Route::get('getAllVoluntingCampigns', 'getAllVoluntingCampigns')
        ->name('user.getAllVoluntingCampigns')
        ->middleware('can:getAllVoluntingCampigns');

    // association campaign id
    Route::get('getVoluntingCampigndetails/{id}', 'getVoluntingCampigndetails')
        ->name('user.getVoluntingCampigndetails')
        ->middleware('can:getVoluntingCampigndetails');

    // task id
    Route::get('getTaskDetails/{id}', 'getTaskDetails')
        ->name('user.getTaskDetails')
        ->middleware('can:getTaskDetails');

    // task id
    Route::get('voluntingRequest/{id}', 'voluntingRequest')
        ->name('user.voluntingRequest')
        ->middleware('can:voluntingRequest');

    Route::get('upComingTasks', 'upComingTasks')
        ->name('user.upComingTasks')
        ->middleware('can:upComingTasks');

    // task id
    Route::post('editTaskStatus/{id}', 'editTaskStatus')
        ->name('user.editTaskStatus')
        ->middleware('can:editTaskStatus');
});


Route::controller(DonationController::class)->group(function(){

    //campaignType  ,  campaign id
Route::middleware('auth:sanctum')->post('donateWithPoints/{campaignType}/{campaignId}', [DonationController::class, 'donateWithPoints'])->name('user.donateWithPoints')->middleware('can:donateWithPoints');

    //campaignType  ,  campaign id
Route::middleware('auth:sanctum')->post('donateWithWallet/{campaignType}/{campaignId}', [DonationController::class, 'donateWithWallet'])->name('user.donateWithWallet')->middleware('can:donateWithWallet');

Route::middleware('auth:sanctum')->post('quickDonateWithWallet', [DonationController::class, 'quickDonateWithWallet'])->name('user.quickDonateWithWallet')->middleware('can:quickDonateWithWallet');

Route::middleware('auth:sanctum')->post('giftAdonation', [DonationController::class, 'giftAdonation'])->name('user.giftAdonation')->middleware('can:giftAdonation');
});


Route::controller(InkindDonationController::class)->group(function(){

Route::middleware('auth:sanctum')->get('showAllInkindDonations', [InkindDonationController::class, 'showAllInkindDonations'])->name('user.showAllInkindDonations')->middleware('can:showAllInkindDonations');

//in-kind donation id
Route::middleware('auth:sanctum')->get('showInkindDonationDetails/{id}', [InkindDonationController::class, 'showInkindDonationDetails'])->name('user.showInkindDonationDetails')->middleware('can:showInkindDonationDetails');

//in-kind donation id
Route::middleware('auth:sanctum')->post('reserveInkindDonation/{id}', [InkindDonationController::class, 'reserveInkindDonation'])->name('user.reserveInkindDonation')->middleware('can:reserveInkindDonation');

//location name
Route::middleware('auth:sanctum')->get('searchForNearestInkindDonation/{location}', [InkindDonationController::class, 'searchForNearestInkindDonation'])->name('user.searchForNearestInkindDonation')->middleware('can:searchForNearestInkindDonation');

Route::middleware('auth:sanctum')->post('addInkindDonation', [InkindDonationController::class, 'addInkindDonation'])->name('user.addInkindDonation')->middleware('can:addInkindDonation');

Route::get('getCenter' , 'getCenter')
->name('all.getCenter');

Route::get('getInkindDonationTypes' , 'getInkindDonationTypes')
->name('all.getInkindDonationTypes');

Route::get('getStatusOfDonation' , 'getStatusOfDonation')
->name('all.getStatusOfDonation');

});


Route::controller(SuperAdminController::class)->group(function(){
Route::get('countAssociations' , 'countAssociations')
    ->name('super_admin.countAssociations');

Route::get('lastNewUsers' , 'lastNewUsers')
->name('super_admin.lastNewUsers');

Route::get('getUserCountsLastFiveYears' , 'getUserCountsLastFiveYears')
->name('super_admin.getUserCountsLastFiveYears');

Route::get('getTotalCampaignsCountByYear/{year}' , 'getTotalCampaignsCountByYear')
->name('super_admin.getTotalCampaignsCountByYear');

Route::get('getUserCountsByRoleByYear/{year}' , 'getUserCountsByRoleByYear')
->name('super_admin.getUserCountsByRoleByYear');

Route::get('usersCountByYear/{year}' , 'usersCountByYear')
->name('super_admin.usersCountByYear');

Route::get('totalDonationsByYear/{year}' , 'totalDonationsByYear')
->name('super_admin.totalDonationsByYear');

Route::get('getCityDonationPercentagesByYear/{year}' , 'getCityDonationPercentagesByYear')
->name('super_admin.getCityDonationPercentagesByYear');

Route::get('getMonthlyDonationsByYear/{year}' , 'getMonthlyDonationsByYear')
->name('super_admin.getMonthlyDonationsByYear');

Route::get('getEndedCampaignsCountByYear/{year}' , 'getEndedCampaignsCountByYear')
->name('super_admin.getEndedCampaignsCountByYear');

Route::get('getDonorsAndVolunteers/{year}' , 'getDonorsAndVolunteers')
->name('super_admin.getDonorsAndVolunteers');

Route::get('getTeamLeaders' , 'getTeamLeaders')
->name('super_admin.getTeamLeaders');

Route::post('createLeader' , 'createLeader')
->name('super_admin.createLeader');

Route::get('deleteLeader/{id}' , 'deleteLeader')
->name('super_admin.deleteLeader');

});


Route::controller(SuperAdminAssociationCompaignsController::class)->group(function(){
    Route::get('getAssociations' , 'getAssociations')
->name('super_admin.getAssociations');

    Route::get('getAssociationsCampaignsActive/{id}' , 'getAssociationsCampaignsActive')
->name('super_admin.getAssociationsCampaignsActive');

    Route::get('getAssociationCompaingsComplete/{id}' , 'getAssociationCompaingsComplete')
->name('super_admin.getAssociationCompaingsComplete');

    Route::get('getAssociationCompaingsClosed/{id}' , 'getAssociationCompaingsClosed')
->name('super_admin.getAssociationCompaingsClosed');

    Route::get('getCampaignDetails/{id}' , 'getCampaignDetails')
->name('super_admin.getCampaignDetails');

    Route::get('getDetails/{id}' , 'getDetails')
->name('super_admin.getDetails');

    Route::post('addAssociation' , 'addAssociation')
->name('super_admin.addAssociation');


});



Route::controller(SuperAdminIndividualCompaignsController::class)->group(function(){

    Route::get('getClosedRejectedIndiviCampaigns' , 'getClosedRejectedIndiviCampaigns')
->name('super_admin.getClosedRejectedIndiviCampaigns');

    Route::get('getClosedPendingIndiviCampaigns' , 'getClosedPendingIndiviCampaigns')
->name('super_admin.getClosedPendingIndiviCampaigns');

    Route::get('getCompleteIndiviCompaign' , 'getCompleteIndiviCompaign')
->name('super_admin.getCompleteIndiviCompaign');

    Route::get('getActiveIndiviCompaign' , 'getActiveIndiviCompaign')
->name('super_admin.getActiveIndiviCompaign');

    Route::get('getActiveCompleteIndiviCampaignDetails/{id}' , 'getActiveCompleteIndiviCampaignDetails')
->name('super_admin.getActiveCompleteIndiviCampaignDetails');

    Route::post('updateAcceptanceStatus/{id}' , 'updateAcceptanceStatus')
->name('super_admin.updateAcceptanceStatus');

    Route::get('getClosedIndiviCampaignDetails/{id}' , 'getClosedIndiviCampaignDetails')
->name('super_admin.getClosedIndiviCampaignDetails');

    Route::get('getLeaderForm/{id}' , 'getLeaderForm')
->name('super_admin.getLeaderForm');

    Route::get('getUnderReviewIndiviCampaignDetails/{campaignId}' , 'getUnderReviewIndiviCampaignDetails')
->name('super_admin.getUnderReviewIndiviCampaignDetails');



});


Route::controller(LeaderController::class)->group(function(){
Route::middleware('auth:sanctum')->get('UnderReviewIndiviCompaign', [LeaderController::class, 'UnderReviewIndiviCompaign'])->name('super_admin.UnderReviewIndiviCompaign');
Route::middleware('auth:sanctum')->post('addLeaderForm/{id}', [LeaderController::class, 'addLeaderForm'])->name('super_admin.addLeaderForm');

});




Route::controller(AdminController::class)->group(function(){
Route::middleware('auth:sanctum')->get('totalAssociationDonationsByYear/{id}/{year}', [AdminController::class, 'totalAssociationDonationsByYear'])->name('admin.totalAssociationDonationsByYear');
Route::middleware('auth:sanctum')->get('getMonthlyDonationsByYear/{id}/{year}', [AdminController::class, 'getMonthlyDonationsByYear'])->name('admin.getMonthlyDonationsByYear');
Route::middleware('auth:sanctum')->get('getActiveCampaignsCount/{id}/{year}', [AdminController::class, 'getActiveCampaignsCount'])->name('admin.getActiveCampaignsCount');
Route::middleware('auth:sanctum')->get('getCompleteCampaignsCount/{id}/{year}', [AdminController::class, 'getCompleteCampaignsCount'])->name('admin.getCompleteCampaignsCount');
Route::middleware('auth:sanctum')->get('AssociationDetails/{id}', [AdminController::class, 'AssociationDetails'])->name('admin.AssociationDetails');
Route::middleware('auth:sanctum')->get('HealthyAssociationsCampaigns/{association_id}/{campaignStatus}', [AdminController::class, 'HealthyAssociationsCampaigns'])->name('admin.HealthyAssociationsCampaigns');
Route::middleware('auth:sanctum')->get('EducationalAssociationsCampaigns/{association_id}/{campaignStatus}', [AdminController::class, 'EducationalAssociationsCampaigns'])->name('admin.EducationalAssociationsCampaigns');

Route::middleware('auth:sanctum')->get('CleanlinessAssociationsCampaigns/{association_id}/{campaignStatus}', [AdminController::class, 'CleanlinessAssociationsCampaigns'])->name('admin.CleanlinessAssociationsCampaigns');
Route::middleware('auth:sanctum')->get('EnvironmentalAssociationsCampaigns/{association_id}/{campaignStatus}', [AdminController::class, 'EnvironmentalAssociationsCampaigns'])->name('admin.EnvironmentalAssociationsCampaigns');
Route::middleware('auth:sanctum')->get('AssociationAdmin/{association_id}', [AdminController::class, 'AssociationAdmin'])->name('admin.AssociationAdmin');


});






Route::put('/update-device-token', [FcmController::class, 'updateDeviceToken']);
Route::post('/send-notification', [FcmController::class, 'sendFcmNotification']);



Route::controller(LeaderController::class)->group(function(){
    Route::post('addLeaderForm/{id}' , 'addLeaderForm')
          ->name('user.addLeaderForm');
});
