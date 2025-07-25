<?php

namespace App\Services;
use App\Models\DonationAssociationCampaign;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use App\Models\TaskVolunteerProfile;
use Illuminate\Support\Facades\DB;
use App\Models\VolunteerProfile;
use App\Models\AvailabilityType;
use App\Models\VolunteerTask;
use App\Models\IndCampaign;
use App\Models\Donation;
use App\Models\User;
use Exception;
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
   //1 - summry about my achievements
   public function mySummryAchievements(): array{
    $userId = Auth::user()->id;
    $total = [];
    $indivdualCampaignsdoniations = Donation::where('user_id' , $userId) 
                                              ->sum('amount');

    $associationCampaignsdoniations = DonationAssociationCampaign::where('user_id' , $userId)
                                                                 ->sum('amount');

    $totalDonations = $indivdualCampaignsdoniations + $associationCampaignsdoniations;

    $volunteerProfileId = VolunteerProfile::where('user_id' , $userId)
                                            ->pluck('id');

    $volunteerTaskId = TaskVolunteerProfile::where('volunteer_profile_id' , $volunteerProfileId)
                                            ->pluck('volunteer_task_id');

    $totalVoluntingHours = VolunteerTask::whereIn('id' , $volunteerTaskId)
                                           ->where('status_id' , 2)
                                           ->sum('hours');

    $indivdualCampaigns = Donation::where('user_id' , $userId)
                                    ->distinct('campaign_id') 
                                    ->count('campaign_id');

    $associationCampaigns = DonationAssociationCampaign::where('user_id' , $userId)
                                                        ->distinct('association_campaign_id')
                                                        ->count('association_campaign_id');

    $VoluntingCampaigns = VolunteerTask::whereIn('id' , $volunteerTaskId)
                                            ->where('status_id' , 2)
                                            ->distinct('association_campaign_id')
                                            ->count('association_campaign_id');

    $totalCampaigns = $indivdualCampaigns + $associationCampaigns + $VoluntingCampaigns;

    $total [] = [
        'total donations' => $totalDonations,
        'total volunting hours' => $totalVoluntingHours,
        'total campaigns' => $totalCampaigns
    ];

    $message = 'my summry achievement are retrived sucessfully';
    return ['my summry achievements' => $total , 'message' => $message];
   }
   
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

    //3 - the campigns that user voluntee in  //my volunting
    public function myVoluntings(): array{
      $userId = Auth::user()->id;
      $volunteerProfileId = VolunteerProfile::where('user_id' , $userId)
                                            ->pluck('id');

      $volunteerTaskId = TaskVolunteerProfile::where('volunteer_profile_id' , $volunteerProfileId)
                                             ->pluck('volunteer_task_id');
      
      $campiagnsVolunting = VolunteerTask::with('associationCampaigns')
                                           ->whereIn('id' , $volunteerTaskId)
                                           ->where('status_id' , 2)
                                           ->get();

        foreach ($campiagnsVolunting as $campiagnVolunting) {

        $createdAt = TaskVolunteerProfile::where('volunteer_profile_id', $volunteerProfileId)
                                            ->where('volunteer_task_id', $campiagnVolunting->id)
                                            ->value('created_at');

            $campiagns_volunting [] = [
                'campiagn name' => $campiagnVolunting->associationCampaigns->title,
                'volunting time' => $createdAt->format('Y-m-d')
            ];
        }

    $message = 'my voluntings are retrived sucessfully';

    return ['my voluntings' => $campiagns_volunting , 'message' => $message];

      }

    //4 - the most campigns that user donate for it 
    public function mostDonationFor(): array{
    $userId = Auth::user()->id;
    $topDonation = [];
    $topDonation[] = Donation::select('campaign_id', DB::raw('SUM(amount) as tamount'))
                                ->where('user_id', $userId)
                                ->groupBy('campaign_id')
                                ->orderByDesc('tamount')
                                ->with('IndCompaigns')
                                ->first();

    $topDonation []  = DonationAssociationCampaign::select('association_campaign_id', DB::raw('SUM(amount) as tamount'))
                                                    ->where('user_id', $userId)
                                                    ->groupBy('association_campaign_id')
                                                    ->orderByDesc('tamount')
                                                    ->with('associationCompaigns')
                                                    ->first();

                    usort($topDonation, function ($a, $b) {
                    return $b['tamount'] <=> $a['tamount'];
                });

    if ($topDonation) {

    if($topDonation[0]->association_campaign_id){
    $firstDonation = DonationAssociationCampaign::where('user_id', $userId)
        ->where('association_campaign_id', $topDonation[0]->association_campaign_id)
        ->orderBy('created_at', 'desc') 
        ->first();
        }
        else{
    $firstDonation = Donation::where('user_id', $userId)
        ->where('campaign_id', $topDonation[0]->campaign_id)
        ->orderBy('created_at', 'desc') 
        ->first();
        }
    }
    $topDonationDet = [];
    $name = $topDonation[0]->associationCompaigns->title ?? $topDonation[0]->IndCompaigns->title;
    $topDonationDet = [
         'campiagn name' => $name,
        'donation time' => $firstDonation->created_at->format('Y-m-d')
    ];

    $message = 'the most campaign I donation for are retrived sucessfully';

    return ['most campaign donation for' => $topDonationDet, 'message' => $message];
    }

    // Create Volunting Profile
    public function createVoluntingProfile($request): array{
        $userId = Auth::user()->id;

        if( VolunteerProfile::where('user_id' , $userId)){
            throw new Exception("You cannot create a volunteer profile because you already have one", 400);
        }
        $voluntingProfile = VolunteerProfile::create([
                'availability_type_id' =>  $request['availability_type_id'],
                'skills' => $request['skills'],
                'availability_hours' => $request['availability_hours'],
                'preferred_tasks' => $request['preferred_tasks'],
                'academic_major' =>  $request['academic_major'],
                'user_id' => $userId,
                'previous_volunteer_work' =>  $request['previous_volunteer_work'],
       ]);

       $availability_type = AvailabilityType::find($voluntingProfile->availability_type_id)->name;

       $voluntingProfile_dett = [
        'availability_type_id' =>  ['id' => $request['availability_type_id'] , 'availability_type' => $availability_type],
        'skills' => $request['skills'],
        'availability_hours' => $request['availability_hours'],
        'preferred_tasks' => $request['preferred_tasks'],
        'academic_major' =>  $request['academic_major'],
        'previous_volunteer_work' =>  $request['previous_volunteer_work'],
       ];

        $message = 'Your volunting profile created sucessfully';

        return ['volunting profile' =>  $voluntingProfile_dett , 'message' => $message];

    }
    }


