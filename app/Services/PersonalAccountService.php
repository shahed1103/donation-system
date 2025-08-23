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
use App\Models\Wallet;
use App\Models\User;
use Exception;
use Storage;


class PersonalAccountService
{
   //get mini information
   public function miniIfo(): array{
     
    $userID = Auth::user()->id;
    $personalInfo = User::find($userID);
    $photo = $personalInfo->photo ;

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
                                            ->value('id');

    $volunteerTaskId = TaskVolunteerProfile::where('volunteer_profile_id' , $volunteerProfileId)
                                            ->where('status_id', 2)
                                            ->pluck('volunteer_task_id');

    $totalVoluntingHours = VolunteerTask::whereIn('id' , $volunteerTaskId)
                                           ->sum('hours');

    $indivdualCampaigns = Donation::where('user_id' , $userId)
                                    ->distinct('campaign_id') 
                                    ->count('campaign_id');

    $associationCampaigns = DonationAssociationCampaign::where('user_id' , $userId)
                                                        ->distinct('association_campaign_id')
                                                        ->count('association_campaign_id');

    $VoluntingCampaigns = VolunteerTask::whereIn('id' , $volunteerTaskId)
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
                                            ->value('id');

      $taskProfiles  = TaskVolunteerProfile::where('volunteer_profile_id' , $volunteerProfileId)
                                              ->where('status_id', 2)
                                              ->get();
                                              
      $volunteerTaskIds = $taskProfiles->pluck('volunteer_task_id');

      $tasks = VolunteerTask::with('associationCampaigns')
                          ->whereIn('id', $volunteerTaskIds)
                          ->get();

     $campiagns_volunting = [];

        foreach ($tasks as $task) {

        $createdAt = $taskProfiles->firstWhere('volunteer_task_id', $task->id)?->created_at;


            $campiagns_volunting [] = [
            'campiagn name' => $task->associationCampaigns->title ,
            'volunting time' => $createdAt ? $createdAt->format('Y-m-d') : 'N/A'
            ];
        }

     $message = 'my voluntings are retrived sucessfully';

     return ['my voluntings' => $campiagns_volunting , 'message' => $message];

    }

    //4 - the most campigns that user donate for it 
    public function mostDonationFor(): array{
      $userId = Auth::user()->id;
        $topDonation = [];
        $topDonationDet = [];

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

        $topDonation = array_filter($topDonation);

                    usort($topDonation , function ($a, $b) {
                    return $b['tamount'] <=> $a['tamount'];
                });

        if (!empty($topDonation)) {

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
        
        $name = $topDonation[0]->associationCompaigns->title ?? $topDonation[0]->IndCompaigns->title;
        $topDonationDet = [
            'campiagn name' => $name,
            'donation time' => $firstDonation->created_at->format('Y-m-d')
            ];
        }
        $message = 'the most campaign I donation for are retrived sucessfully';

        return ['most campaign donation for' => $topDonationDet, 'message' => $message];
    }

    // Create Volunting Profile
    public function createVoluntingProfile($request): array{
        $userId = Auth::user()->id;

        if(VolunteerProfile::where('user_id' , $userId)->exists()){
            throw new Exception("You cannot create a volunteer profile because you already have one", 400);
        }
        $voluntingProfile = VolunteerProfile::with('availabilityType')->create([
                'availability_type_id' =>  $request['availability_type_id'],
                'skills' => $request['skills'],
                'availability_hours' => $request['availability_hours'],
                'preferred_tasks' => $request['preferred_tasks'],
                'academic_major' =>  $request['academic_major'],
                'user_id' => $userId,
                'previous_volunteer_work' =>  $request['previous_volunteer_work'],
       ]);

       $voluntingProfile_dett = [
        'availability_type_id' =>  ['id' => $request['availability_type_id'] , 'availability_type' => $voluntingProfile->availabilityType->name],
        'skills' => $request['skills'],
        'availability_hours' => $request['availability_hours'],
        'preferred_tasks' => $request['preferred_tasks'],
        'academic_major' =>  $request['academic_major'],
        'previous_volunteer_work' =>  $request['previous_volunteer_work'],
       ];

        $message = 'Your volunting profile created sucessfully';

        return ['volunting profile' =>  $voluntingProfile_dett , 'message' => $message];
    }

    // update Volunting Profile
    public function updateVoluntingProfile($request): array{
       $userId = Auth::user()->id;
       $voluntingProfile_dett = [];
      $voluntingProfile = VolunteerProfile::with('availabilityType')->where('user_id' , $userId)->first();

      $voluntingProfile->update([
                'availability_type_id' =>  $request['availability_type_id'] ?? $voluntingProfile->availability_type_id,
                'skills' => $request['skills'] ?? $voluntingProfile->skills,
                'availability_hours' => $request['availability_hours'] ?? $voluntingProfile->availability_hours,
                'preferred_tasks' => $request['preferred_tasks'] ?? $voluntingProfile->preferred_tasks,
                'academic_major' =>  $request['academic_major'] ?? $voluntingProfile->academic_major,
                'previous_volunteer_work' =>  $request['previous_volunteer_work'] ?? $voluntingProfile->previous_volunteer_work,
       ]);

       $voluntingProfile->refresh()->load('availabilityType');
       $voluntingProfile_dett = [
        'availability_type_id' =>  ['id' => $request['availability_type_id'] ?? $voluntingProfile->availability_type_id , 'availability_type' => $voluntingProfile->availabilityType->name],
        'skills' => $request['skills'] ?? $voluntingProfile->skills,
        'availability_hours' => $request['availability_hours'] ?? $voluntingProfile->availability_hours,
        'preferred_tasks' => $request['preferred_tasks'] ?? $voluntingProfile->preferred_tasks,
        'academic_major' =>  $request['academic_major'] ?? $voluntingProfile->academic_major,
        'previous_volunteer_work' =>  $request['previous_volunteer_work'] ?? $voluntingProfile->previous_volunteer_work,
       ];

        $message = 'Your volunting profile updated sucessfully';

        return ['volunting profile' =>  $voluntingProfile_dett , 'message' => $message];
    }

    // show Volunting Profile
    public function showVoluntingProfile(): array{
           $user = Auth::user();

       $voluntingProfileCreated = VolunteerProfile::where('user_id' , $user->id)->value('created_at');

       $voluntingProfile_dett = [];
       $voluntingProfile_dett = [
        'volunteer_name' => $user->name,
        'time_becoming_volunteer_member' => $voluntingProfileCreated->format('Y-m-d')
       ];

        $message = 'volunting profile';

        return ['volunting profile' =>  $voluntingProfile_dett , 'message' => $message];
    }

    // show Volunting Profile details
    public function showVoluntingProfileDetails(): array{
       $userId = Auth::user()->id;
       $voluntingProfile_dett = [];

      $voluntingProfile = VolunteerProfile::with('availabilityType')->where('user_id' , $userId)->first();

      $voluntingProfile_dett = [
        'availability_type_id' =>  ['id' => $voluntingProfile->availability_type_id , 'availability_type' => $voluntingProfile->availabilityType->name],
        'skills' => $voluntingProfile->skills,
        'availability_hours' => $voluntingProfile->availability_hours,
        'preferred_tasks' => $voluntingProfile->preferred_tasks,
        'academic_major' =>  $voluntingProfile->academic_major,
        'previous_volunteer_work' =>  $voluntingProfile->previous_volunteer_work,
       ];

        $message = 'Your volunting profile details retrived sucessfully';

        return ['volunting profile' =>  $voluntingProfile_dett , 'message' => $message];
    }

    //show my personal profile information
    public function showAllInfo(): array{
        $userIfon = Auth::user()->load(['city' , 'gender']);

        $userDett = [
        'user_name' => $userIfon->name,
        'city_id' => ['id' => $userIfon->city_id , 'city_name' => $userIfon->city->name  ?? null],
        'phone' => $userIfon ->phone,
        'age' => $userIfon ->age,
        'gender_id' => ['id' => $userIfon->gender_id , 'gender_type' => $userIfon->gender->type ?? null ],
        'email' => $userIfon->email,
        // 'wallet_value'=> $userIfon->wallet->wallet_value ?? 0
        ];

        $message = 'Your personal profile details retrived sucessfully';

        return ['personal profile' =>  $userDett , 'message' => $message];
    }

    //edit my personal profile information
    public function editPersonalInfo($request): array{
        $userIfon = Auth::user()->load(['city', 'gender']);


    if ($request->hasFile('photo')) {
             $photo = $request->file('photo');
             $path = $photo->store('uploads/profilePhoto', 'public');
             $fullPath = url(Storage::url($path));
     }

        $userIfon->update([
        'name' => $request['name'] ?? $userIfon->name,
        'city_id' => $request['city_id'] ?? $userIfon->city_id ,
        'phone' => $request['phone'] ?? $userIfon ->phone,
        'age' => $request['age'] ?? $userIfon->age,
        'gender_id' =>  $request['gender_id'] ?? $userIfon->gender_id ,
        'photo' => $fullPath ?? $userIfon->photo
        ]);

        $userIfon->refresh()->load(['city', 'gender']);

        $userDett = [
        'name' => $userIfon->name,
        'city_id' => ['id' => $userIfon->city_id , 'city_name' => $userIfon->city->name  ?? null],
        'phone' =>  $userIfon ->phone,
        'age' => $userIfon->age,
        'gender_id' => ['id' => $userIfon->gender_id , 'gender_type' => $userIfon->gender->type ?? null ],
        'photo' => $userIfon->photo
        ];
        $message = 'Your personal profile updated sucessfully';

        return ['personal profile' =>  $userDett , 'message' => $message];
    }

    //create wallet for user
    public function createWallet($request): array{
        $user = Auth::user();
        if($user->wallet != null){
            throw new Exception("You cannot create a wallet because you already have one", 400);
        }
        
        $wallet = Wallet::create([
          'user_id' => $user->id,
          'wallet_value' => $request ['wallet_value'],
          'wallet_password' => $request ['wallet_password']]);

        $message = 'Your wallet created sucessfully';

        return ['wallet' =>  $wallet , 'message' => $message];
     }


     //show wallet
     public function showWallet(): array{
        $user = Auth::user();

        if($user->wallet == null){
            throw new Exception("You do not have wallet yet create a wallet", 400);
        }

        $wallet = [
        'wallet_value' => $user->wallet->wallet_value,
        'wallet_careated_date' => $user->wallet->created_at->format('Y-m-d')
        ];

        $message = 'Your wallet retrived sucessfully';

        return ['wallet' =>  $wallet , 'message' => $message];     }
    }

