<?php

namespace App\Services;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Storage;
use Illuminate\Support\Facades\File;

class PersonalAccountService
{
   //get mini information that in front of personal account
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
}
