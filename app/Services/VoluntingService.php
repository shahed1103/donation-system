<?php


namespace App\Services;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\DonationAssociationCampaign;
use Illuminate\Support\Facades\Session;
use App\Models\TaskVolunteerProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\AssociationCampaign;
use Spatie\Permission\Models\Role;
use Illuminate\Http\JsonResponse;
use App\Http\Responses\Response;
use App\Models\CampaignStatus;
use App\Models\Classification;
use App\Models\VolunteerTask;
use Illuminate\Http\Request;
use App\Models\Association;
use App\Models\Donation;
use App\Models\User;
use Carbon\Carbon;
use Throwable;
use Exception;
use Storage;

class VoluntingService
{

   //get all volunting campigns
   public function getAllVoluntingCampigns() : array{
    $voluntings = VolunteerTask::with('associationCampaigns.classification' , 'associationCampaigns.campaignStatus')->get();
    $seenCampaigns = [];
    $det = [];

    foreach ($voluntings as $volunting) {
        $campaign = $volunting->associationCampaigns;
        $taskCount = $voluntings->where('associationCampaigns.id', $campaign->id)->count();

        if ($campaign && !in_array($campaign->id, $seenCampaigns) && $campaign->campaign_status_id == 1) {
            $seenCampaigns[] = $campaign->id;

            $det[] = [
                'id' => $campaign->id,
                'title' => $campaign->title,
                'classification_id' => ['id' => $campaign->classification_id , 'classification_name' => $campaign->classification->classification_name],
                'photo' => url(Storage::url($campaign->photo)) ,
                'campaign_status_id' => ['id' => $campaign->campaign_status_id , 'campaign_status_type' =>  $campaign->campaignStatus->status_type],
                'number_of_tasks' => $taskCount,
            ];
        }
    }

         $message = 'Volunting campaigns are retrived sucessfully';

         return ['Volunting campaigns' => $det , 'message' => $message];
   }

   //get volunting campign details
   public function getVoluntingCampigndetails($campaignId) : array{
   $campaign = AssociationCampaign::with('classification' , 'campaignStatus' , 'volunteerTasks')->findOrFail($campaignId);

   $taskDet = [];
   $det = [];

    foreach ($campaign->volunteerTasks as $task) {
        if ($task->number_volunter_need > 0) {
            $taskDet[] = [
                'id' => $task->id,
                'task_name' => $task->name,
                'number_volunter_need' => $task->number_volunter_need,
            ];
        }
    }

      $det[] = [
            'id' => $campaign->id,
            'title' => $campaign->title,
            'description' => $campaign->description,
            'location' => $campaign->location,
            'classification_id' => ['id' => $campaign->classification_id , 'classification_name' => $campaign->classification->classification_name],
            'photo' => url(Storage::url($campaign->photo)) ,
            'campaign_status_id' => ['id' => $campaign->campaign_status_id , 'campaign_status_type' =>  $campaign->campaignStatus->status_type],
            'number_of_tasks' => $campaign->volunteerTasks->count(),
            'tasks' => $taskDet,
            'campaign_start_time' => $campaign->compaigns_start_time,
            'campaign_end_time' => $campaign->compaigns_end_time, 
            'tasks_time' => "$campaign->tasks_start_time - $campaign->tasks_end_time",
     
         ];

         $message = 'Volunting campaign details are retrived sucessfully';

         return ['Volunting campaign' => $det , 'message' => $message];
   }

   //get task details
   public function getTaskDetails($taskID) : array{
     $task = VolunteerTask::findOrFail($taskID);

     $taskDet = [
      'task_name' => $task->name,
      'description' => $task->description,
      'hours' => $task->hours,
      'number_volunter_need' => $task->number_volunter_need,
     ];

      $message = 'task details are retrived sucessfully';

      return ['task' => $taskDet , 'message' => $message];
   }

   //volunting request
   public function voluntingRequest($taskId) : array{
    $user = Auth::user();

   //  $user->load('volunteerProfile');

    if(!$user->volunteerProfile){
         throw new Exception("You must create your volunteer profile before requesting to volunteer", 403);
    }

     $voluntingRequest = TaskVolunteerProfile::create([
         'volunteer_profile_id' => $user->volunteerProfile->id,
         'volunteer_task_id' => $taskId,
         'status_id' => 4
      ]);

      $message = 'Your request to volunteer has been sent for review';

      return ['volunting request' => $voluntingRequest , 'message' => $message];
   }
}
