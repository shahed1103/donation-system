<?php

namespace App\Services;
use App\Models\DonationAssociationCampaign;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use App\Models\Donation;
use App\Models\IndCampaign;

use App\Models\User;
use Storage;

class PersonalAccountService
{
   //get mini information
   public function miniIfo(): array{
     
    $userID = Auth::user()->id;
    $personalInfo = User::find($userID);

         $sourcePath = 'uploads/seeder_photos/defualtProfilePhoto.png';
         $targetPath = 'uploads/det/defualtProfilePhoto.png';

    Storage::disk('public')->put($targetPath, File::get($sourcePath));

    $photo = $personalInfo->photo 
             ? url(Storage::url($personalInfo->photo)) 
             : url(Storage::url($targetPath)) ;
    $requiredInfo = [];
    $requiredInfo = [
    'user name' => $personalInfo->name,
    'photo' => $photo,
    'points' =>  $personalInfo->points,
    ];

    $message = 'personal required information are retrived sucessfully';

    return ['personal inforamation' => $requiredInfo , 'message' => $message];
   }


   //my achievements

   //2 - the campigns that user donate for it //my donation
   public function mydonations(): array{
    $userId = Auth::user()->id;
    $indivdualCampaignsdoniations = Donation::with('IndCompaigns')
                                    ->where('user_id' , $userId)
                                    ->get();

    $associationCampaignsdoniations = DonationAssociationCampaign::with('associationCompaigns')
                                     ->where('user_id' , $userId)
                                     ->get();

    $campaigns = [];
    foreach ($indivdualCampaignsdoniations as $indivdualCampaignsdoniation) {
        $campaigns [] = [
         'campiagn name' => $indivdualCampaignsdoniation->IndCompaigns->title,
         'donation time' => $indivdualCampaignsdoniation->created_at->format('Y-m-d')
        ];
    } 

    foreach ($associationCampaignsdoniations as $associationCampaignsdoniation) {
        $campaigns [] = [
         'campiagn name' => $associationCampaignsdoniation->associationCompaigns->title,
         'donation time' => $associationCampaignsdoniation->created_at->format('Y-m-d')
        ];
    }                                    

    $message = 'my donation are retrived sucessfully';

    return ['my donations' => $campaigns , 'message' => $message];
   }

}
