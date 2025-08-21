<?php


namespace App\Services;

use App\Models\User;
use App\Models\IndCompaign;
use App\Models\Classification;
use App\Models\Association;
use App\Models\CampaignStatus;
use App\Models\DonationAssociationCampaign;
use App\Models\Donation;
use App\Models\VolunteerTask;

use App\Models\SharedAssociationCampaign;
use App\Models\AssociationCampaign;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Session;
use Exception;
use Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use App\Http\Responses\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Throwable;
use Carbon\Carbon;

class AdminService
{

public function totalAssociationDonationsByYear(int $owner_id, int $year): array
{
    $association = Association::where('association_owner_id' , $owner_id ) ->first();
    $sharedAssociation = SharedAssociationCampaign:: where('association_id' ,$association->id ) ->first();

    $startOfYear = Carbon::createFromDate($year, 1, 1)->startOfDay();
    $endOfYear = Carbon::createFromDate($year, 12, 31)->endOfDay();

    $total = DonationAssociationCampaign::where('association_campaign_id',
     $sharedAssociation->association_campaign_id)
        ->whereBetween('created_at', [$startOfYear, $endOfYear])
        ->sum('amount');

    $message = "Total donations for year {$year} retrieved successfully";
    return [
        'total' => $total,
        'message' => $message
    ];
}


public function getMonthlyDonationsByYear(int $owner_id, int $year): array
{
    $association = Association::where('association_owner_id' , $owner_id ) ->first();
    $sharedAssociation = SharedAssociationCampaign:: where('association_id' ,$association->id ) ->first();

    $donationsByMonth = DonationAssociationCampaign::where('association_campaign_id',
     $sharedAssociation->association_campaign_id)
        ->whereYear('created_at', $year)
        ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
        ->groupBy('month')
        ->orderBy('month')
        ->get();

    $monthlyTotals = [];
    for ($i = 1; $i <= 12; $i++) {
        $monthlyTotals[$i] = 0;
    }

    foreach ($donationsByMonth as $row) {
        $monthlyTotals[(int)$row->month] = (float)$row->total;
    }
    $message = "Monthly donation totals for year {$year} retrieved successfully";

    return [
        'data' => $monthlyTotals,
        'message' => $message
    ];
}



public function getActiveCampaignsCount(int $owner_id , $year): array
{

    $association = Association::where('association_owner_id' , $owner_id ) ->first();

    $startOfYear = Carbon::createFromDate($year, 1, 1)->startOfDay();
    $endOfYear = Carbon::createFromDate($year, 12, 31)->endOfDay();


    $campaignIds = SharedAssociationCampaign::where('association_id', $association->id)
        ->pluck('association_campaign_id');

    $activeCampaignsCount = AssociationCampaign::whereIn('id', $campaignIds)
        ->where('campaign_status_id', 1)
        ->whereBetween('created_at', [$startOfYear, $endOfYear])
        ->count();

    $message = 'Active campaigns count retrieved successfully';

    return [
        'count' => $activeCampaignsCount,
        'message' => $message
    ];
}

public function getCompleteCampaignsCount(int $owner_id , $year): array
{

    $association = Association::where('association_owner_id' , $owner_id ) ->first();
    $startOfYear = Carbon::createFromDate($year, 1, 1)->startOfDay();
    $endOfYear = Carbon::createFromDate($year, 12, 31)->endOfDay();

    $campaignIds = SharedAssociationCampaign::where('association_id', $association->id)
        ->pluck('association_campaign_id');

    $activeCampaignsCount = AssociationCampaign::whereIn('id', $campaignIds)
        ->where('campaign_status_id', 3)
        ->whereBetween('created_at', [$startOfYear, $endOfYear])
        ->count();

    $message = 'Active campaigns count retrieved successfully';

    return [
        'count' => $activeCampaignsCount,
        'message' => $message
    ];
}

public function getDonationCountsByClassByYear(int $owner_id, int $year): array
{
    $association = Association::where('association_owner_id', $owner_id)->firstOrFail();

    $startOfYear = Carbon::createFromDate($year, 1, 1)->startOfDay();
    $endOfYear   = Carbon::createFromDate($year, 12, 31)->endOfDay();

    $campaignIds = $association->associationCampaigns()->pluck('association_campaigns.id');
$counts = DonationAssociationCampaign::whereIn('association_campaign_id', $campaignIds)
    ->whereBetween('donation_association_campaigns.created_at', [$startOfYear, $endOfYear]) // <-- هنا التحديد
    ->selectRaw('association_campaigns.classification_id, COUNT(*) as total')
    ->join('association_campaigns', 'donation_association_campaigns.association_campaign_id', '=', 'association_campaigns.id')
    ->groupBy('association_campaigns.classification_id')
    ->pluck('total', 'association_campaigns.classification_id');


    $data = [
        'healthy'       => $counts[1] ?? 0,
        'Educational'   => $counts[2] ?? 0,
        'cleanliness'   => $counts[3] ?? 0,
        'environmental' => $counts[4] ?? 0,
    ];

    $message = "Donation counts by classification for year {$year} retrieved successfully";

    return [
        'data' => $data,
        'message' => $message
    ];
}


public function AssociationDetails($owner_id): array
      {
         $association = Association::where('association_owner_id', $owner_id)->firstOrFail();
         $campaignIds = SharedAssociationCampaign::where('association_id', $association->id)
            ->pluck('association_campaign_id');

         $totalCampaigns = $campaignIds->count();
         $totalDonations = DonationAssociationCampaign::whereIn('association_campaign_id', $campaignIds)
            ->sum('amount');

        //  $completedCampaigns = $this->getAssociationCompaingsComplete($id);
        //  $activeCampaigns = $this->getAssociationsCampaignsActive($id);
         $association_owner = User::find($association->association_owner_id);
         $associationDet = [];

        $associationDet[] = [
            'id' => $association->id,
            'association_name' => $association->name,
            'association_description' => $association->description,
            'location' => $association->location,
            'association_owner' => $association_owner->name,
            'date_start_working' => $association -> date_start_working,
            'date_end_working' => $association -> date_end_working,
            'total_donations' => $totalDonations,
            // 'completed_campaigns' => $completedCampaigns,
            // 'active_campaigns' => $activeCampaigns
            ];
            $message = 'association details are retrived sucessfully';

         return ['association' => $associationDet , 'message' => $message];
      }






public function getCampaignsStatus(): array
{
        $status = CampaignStatus::select('id', 'status_type')->get()
            ->map(function ($status) {
                return [
                    'id'   => $status->id,
                    'status_type' => $status->status_type, ]; });
        return [
            'status' => $status,
            'message' => 'done' ];
}


public function HealthyAssociationsCampaigns($association_id , $campaignStatus): array
{
         $campaignIds = SharedAssociationCampaign::where('association_id', $association_id)
            ->pluck('association_campaign_id');

         $campaigns = AssociationCampaign::with(['classification', 'campaignStatus'])
            ->whereIn('id', $campaignIds)
            ->where('campaign_status_id', $campaignStatus)
            ->where('classification_id', 1)
            ->get();

         $compaingAll = [];

         foreach ($campaigns as $campaign) {
            $totalDonations = DonationAssociationCampaign::where('association_campaign_id', $campaign->id)
                  ->sum('amount');

            $compaingAll[] = [
                  'id' =>  $campaign->id,
                  'title' => $campaign->title,
                  'photo' => url(Storage::url($campaign->photo)),
                  'amount_required' => $campaign->amount_required,
                  'donation_amount' => $totalDonations,
                  'campaign_status_id' => [
                     'id' => $campaign->campaign_status_id,
                     'campaign_status_type' => $campaign->campaignStatus->status_type
                  ],
                  'compaigns_time_to_end' => Carbon::now()->diff($campaign->compaigns_end_time)->format('%m Months %d Days %h Hours'),
            ]; }
            $message = 'Your campaign retrived sucessfully';
         return ['campaign' => $compaingAll, 'message' => $message];
}

public function EducationalAssociationsCampaigns($association_id , $campaignStatus): array
{
         $campaignIds = SharedAssociationCampaign::where('association_id', $association_id)
            ->pluck('association_campaign_id');

         $campaigns = AssociationCampaign::with(['classification', 'campaignStatus'])
            ->whereIn('id', $campaignIds)
            ->where('campaign_status_id', $campaignStatus)
            ->where('classification_id', 2)
            ->get();

         $compaingAll = [];

         foreach ($campaigns as $campaign) {
            $totalDonations = DonationAssociationCampaign::where('association_campaign_id', $campaign->id)
                  ->sum('amount');

            $compaingAll[] = [
                  'id' =>  $campaign->id,
                  'title' => $campaign->title,
                  'photo' => url(Storage::url($campaign->photo)),
                  'amount_required' => $campaign->amount_required,
                  'donation_amount' => $totalDonations,
                  'campaign_status_id' => [
                     'id' => $campaign->campaign_status_id,
                     'campaign_status_type' => $campaign->campaignStatus->status_type
                  ],
                  'compaigns_time_to_end' => Carbon::now()->diff($campaign->compaigns_end_time)->format('%m Months %d Days %h Hours'),
            ]; }
            $message = 'Your campaign retrived sucessfully';
         return ['campaign' => $compaingAll, 'message' => $message];
}

public function CleanlinessAssociationsCampaigns($association_id , $campaignStatus): array
{
         $campaignIds = SharedAssociationCampaign::where('association_id', $association_id)
            ->pluck('association_campaign_id');

         $campaigns = AssociationCampaign::with(['classification', 'campaignStatus'])
            ->whereIn('id', $campaignIds)
            ->where('campaign_status_id', $campaignStatus)
            ->where('classification_id', 3)
            ->get();

         $compaingAll = [];

         foreach ($campaigns as $campaign) {
            $totalDonations = DonationAssociationCampaign::where('association_campaign_id', $campaign->id)
                  ->sum('amount');

            $compaingAll[] = [
                  'id' =>  $campaign->id,
                  'title' => $campaign->title,
                  'photo' => url(Storage::url($campaign->photo)),
                  'amount_required' => $campaign->amount_required,
                  'donation_amount' => $totalDonations,
                  'campaign_status_id' => [
                     'id' => $campaign->campaign_status_id,
                     'campaign_status_type' => $campaign->campaignStatus->status_type
                  ],
                  'compaigns_time_to_end' => Carbon::now()->diff($campaign->compaigns_end_time)->format('%m Months %d Days %h Hours'),
            ]; }
            $message = 'Your campaign retrived sucessfully';
         return ['campaign' => $compaingAll, 'message' => $message];
}

public function EnvironmentalAssociationsCampaigns($association_id , $campaignStatus): array
{
         $campaignIds = SharedAssociationCampaign::where('association_id', $association_id)
            ->pluck('association_campaign_id');

         $campaigns = AssociationCampaign::with(['classification', 'campaignStatus'])
            ->whereIn('id', $campaignIds)
            ->where('campaign_status_id', $campaignStatus)
            ->where('classification_id', 4)
            ->get();

         $compaingAll = [];

         foreach ($campaigns as $campaign) {
            $totalDonations = DonationAssociationCampaign::where('association_campaign_id', $campaign->id)
                  ->sum('amount');

            $compaingAll[] = [
                  'id' =>  $campaign->id,
                  'title' => $campaign->title,
                  'photo' => url(Storage::url($campaign->photo)),
                  'amount_required' => $campaign->amount_required,
                  'donation_amount' => $totalDonations,
                  'campaign_status_id' => [
                     'id' => $campaign->campaign_status_id,
                     'campaign_status_type' => $campaign->campaignStatus->status_type
                  ],
                'compaigns_time_to_end' => Carbon::now()->diff($campaign->compaigns_end_time)->format('%m Months %d Days %h Hours'),
            ]; }
            $message = 'Your campaign retrived sucessfully';
         return ['campaign' => $compaingAll, 'message' => $message];
}




public function AssociationAdmin ($owner_id) {
    // $association = Association:: firstWhere('id', $association_id);
    // $admin_id = $association -> association_owner_id;

 $admin = User:: firstWhere('id', $owner_id );

 $adminDet = [];
 $adminDet[] = [
                  'id' =>  $admin->id,
                  'name' => $admin->name,
                  'email' => $admin->email,
                  'phone' => $admin->phone,
 ];

        $message = 'Your admin retrived sucessfully';
        return ['admin' => $adminDet, 'message' => $message];
}




public function getVoluntingCampigns($campaignStatus) : array{
    $voluntings = VolunteerTask::with('associationCampaigns.classification' , 'associationCampaigns.campaignStatus')->get();
    $seenCampaigns = [];
    $det = [];

    foreach ($voluntings as $volunting) {
        $campaign = $volunting->associationCampaigns;
        $taskCount = $voluntings->where('associationCampaigns.id', $campaign->id)->count();
        if ($campaign && !in_array($campaign->id, $seenCampaigns) && $campaign->campaign_status_id == $campaignStatus) {
             $tasksC = $campaign->volunteerTasks->sum('number_volunter_need');

             if($tasksC <= 0){
               continue;
             }

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




   public function getVoluntingCompDetails($campaignId) : array{
   $campaign = AssociationCampaign::with('classification' , 'campaignStatus' , 'volunteerTasks')->findOrFail($campaignId);

   $taskDet = [];
   $det = [];
    foreach ($campaign->volunteerTasks as $task) {
        if ($task->number_volunter_need > 0) {
            $taskDet[] = [
                'id' => $task->id,
                'task_name' => $task->name,
                'number_volunter_need' => $task->number_volunter_need,
                'description' => $task->description,
                'hours' => $task -> hours
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
            'tasks' => $taskDet,
            'campaign_start_time' => $campaign->compaigns_start_time,
            'campaign_end_time' => $campaign->compaigns_end_time,
            'compaigns_time' => $campaign ->compaigns_time,
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






    public function createAssociationCampaign(array $data): array
    {

        if (isset($data['photo']) && $data['photo'] instanceof \Illuminate\Http\UploadedFile) {
            $path = $data['photo']->store('campaigns', 'public');
            $data['photo'] = $path;
        }

        $campaign = AssociationCampaign::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'location' => $data['location'],
            'classification_id' => $data['classification_id'],
            'amount_required' => $data['amount_required'],
            'campaign_status_id' => 1,
            'photo' => $data['photo'],
            'compaigns_start_time' => $data['compaigns_start_time'],
            'compaigns_end_time' => $data['compaigns_end_time'],
            'compaigns_time' => $data['compaigns_time'],
            'emergency_level' => $data['emergency_level'],
        ]);


        $campaign->refresh();
        if (!empty($data['tasks'])) {
            foreach ($data['tasks'] as $task) {
                VolunteerTask::create([
                    'name' => $task['name'],
                    'description' => $task['description'],
                    'number_volunter_need' => $task['number_volunter_need'],
                    'hours' => $task['hours'],
                    'association_campaign_id' => $campaign->id,
                ]);
            }
        }


    $classification_name = Classification::find($campaign->classification_id)->classification_name ?? null;
    $campaign_status_type = CampaignStatus::find(1)->status_type ?? null;

        $campaign_dett = [
            'id' => $campaign->id,
            'title' => $campaign->title,
            'description' => $campaign->description,
            'classification_id' => [
                'id' => $campaign->classification_id,
                'classification_name' => $classification_name
            ],
            'photo' => $campaign->photo ? url(Storage::url($campaign->photo)) : null,
            'location' => $campaign->location,
            'amount_required' => $campaign->amount_required,
            'campaign_status_id' => [
                'id' => $campaign->campaign_status_id,
                'campaign_status_type' => $campaign_status_type
            ],
            'compaigns_start_time' => $campaign->compaigns_start_time,
            'compaigns_end_time' => $campaign->compaigns_end_time,
            'emergency_level' => $campaign->emergency_level,
            'tasks' => $campaign->volunteerTasks()->get(['id', 'name', 'description', 'number_volunter_need', 'hours']),
        ];

        $message = 'Your campaign created successfully';

        return ['campaign' => $campaign_dett, 'message' => $message];
    }
}

