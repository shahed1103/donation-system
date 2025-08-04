<?php
namespace App\Services;
use App\Models\IndCompaign;
use App\Models\Classification;
use App\Models\AcceptanceStatus;
use App\Models\CampaignStatus;
use App\Models\IndCompaigns_photo;
use App\Models\Donation;
use App\Models\User;
use App\Models\City;
use App\Models\Gender;
use App\Models\AvailabilityType;
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

class IndividualCompaignsService
{

    public function createIndiviCompa($request): array{

        $user= Auth::user()->id;

            $photoRanges = [
                1 => [1, 4],
                2 => [5, 8],
                3 => [9, 12],
                4 => [13, 16],
            ];
        $campaign = IndCompaign::create([
                'title' =>  $request['title'],
                'description' => $request['description'],
                'classification_id' => $request['classification_id'],
                'location' => $request['location'],
                'amount_required' =>  $request['amount_required'],
                'user_id' => $user,
                'photo_id' => rand(...$photoRanges[$request['classification_id']]),
                'compaigns_time' =>  $request['compaigns_time'],
       ]);

       $campaign->refresh();
       $classification_name = Classification::find($request['classification_id'])->classification_name;
       $acceptance_status_type = AcceptanceStatus::find($campaign->acceptance_status_id)->status_type;
       $campaign_status_type = CampaignStatus::find($campaign->campaign_status_id)->status_type;
       $campaign_photo = IndCompaigns_photo::find($campaign->photo_id)->photo;


       $campaign_dett = [
        'title' =>  $request['title'],
        'description' => $request['description'],
        'classification_id' => ['id' => $request['classification_id'], 'classification_name' => $classification_name] ,
        'photo_id' => ['id' => $campaign->photo_id , 'photo' => url(Storage::url($campaign_photo))] ,
        'location' => $request['location'],
        'amount_required' =>  $request['amount_required'],
        'acceptance_status_id'=>  ['id' => $campaign->acceptance_status_id, 'acceptance_status_type' => $acceptance_status_type],
        'campaign_status_id'=>  ['id' => $campaign->campaign_status_id, 'campaign_status_type' => $campaign_status_type],
        'compaigns_time' =>  $request['compaigns_time'],
       ];

        $message = 'Your campaign created sucessfully';

        return ['campaign' =>  $campaign_dett , 'message' => $message];
    }

// view my individual compaings active + complete + closed
     public function viewMyIndiviCompa(): array{
        $user= Auth::user()->id;

        $campaigns = IndCompaign::where('user_id' , $user)->get();


        $compaingAll = [];
        foreach ($campaigns as $compaign) {

                $classification_name = Classification::find($compaign->classification_id)->classification_name;
                $acceptance_status_type = AcceptanceStatus::find($compaign->acceptance_status_id)->status_type;
                $campaign_status_type = CampaignStatus::find($compaign->campaign_status_id)->status_type;
                $photo = IndCompaigns_photo::find($compaign->photo_id)->photo;

            $fullPath = url(Storage::url($photo));


            if($campaign_status_type === "Closed"){

            $compaingAll[] =
        [
        'id' =>  $compaign->id,
        'title' =>  $compaign->title,
        'photo_id' => ['id' =>$compaign->photo_id , 'photo' =>$fullPath],
        'amount_required' =>  $compaign->amount_required,
        'donation_amount' => 0,
        'acceptance_status_id'=>  ['id' => $compaign->acceptance_status_id, 'acceptance_status_type' => $acceptance_status_type],
        'campaign_status_id'=>  ['id' => $compaign->campaign_status_id, 'campaign_status_type' => $campaign_status_type],
        'compaigns_time_to_end' =>  "$compaign->compaigns_time Day",
        ];

            }
        if($campaign_status_type === "Active" || $campaign_status_type === "Complete"){
             $campaign_ids = Donation::where('campaign_id' , $compaign->id)->get();


        $total  = 0;
             foreach ($campaign_ids as $campaign_id) {
                $total += $campaign_id->amount;
             }
            $compaingAll[] =
        [
        'id' =>  $compaign->id,
        'title' =>  $compaign->title,
        'photo_id' => ['id' =>$compaign->photo_id , 'photo' =>$fullPath],
        'amount_required' =>  $compaign->amount_required,
        'donation_amount' => $total,
        'acceptance_status_id'=>  ['id' => $compaign->acceptance_status_id, 'acceptance_status_type' => $acceptance_status_type],
        'campaign_status_id'=>  ['id' => $compaign->campaign_status_id, 'campaign_status_type' => $campaign_status_type],
        'compaigns_time_to_end' => Carbon::now()->diff($compaign->compaigns_end_time)->format('%M Months %D Day %H Hours')
        ];
        }
        }

        $message = 'Your campaign retrived sucessfully';

        return ['campaign' =>  $compaingAll , 'message' => $message];
     }

// view individual compaings active
     public function viewIndiviCompa($id): array{
        $campaigns = IndCompaign::where('classification_id' , $id)->get();

        foreach ($campaigns as $campaign) {
                $campaign->updateStatus('individual');
            }

        $compaingAll = [];
        foreach ($campaigns as $compaign) {
                $classification_name = Classification::find($compaign->classification_id)->classification_name;
                $campaign_status_type = CampaignStatus::find($compaign->campaign_status_id)->status_type;
                $photo = IndCompaigns_photo::find($compaign->photo_id)->photo;

                $fullPath = url(Storage::url($photo));


        if($campaign_status_type === "Active"){
             $campaign_ids = Donation::where('campaign_id' , $compaign->id)->get();
        $total  = 0;
             foreach ($campaign_ids as $campaign_id) {
                $total += $campaign_id->amount;
             }

        $compaingAll [] =
        [
        'id' =>  $compaign->id,
        'title' =>  $compaign->title,
        'amount_required' =>  $compaign->amount_required,
        'donation_amount' => $total,
        'campaign_status_id'=>  ['id' => $compaign->campaign_status_id, 'campaign_status_type' => $campaign_status_type],
        'photo_id' => ['id' =>$compaign->photo_id , 'photo' =>$fullPath],
        'compaigns_time_to_end' => Carbon::now()->diff($compaign->compaigns_end_time)->format('%m Months %d Days %h Hours'),
        'type' => 'individual',
        'amount_to_Complete' => $compaign->amount_required - $total,

    ];
        }

        }

        $message = 'Your campaign retrived sucessfully';

        return ['campaign' =>   $compaingAll , 'message' => $message];
     }

      // Get individual campaign details
    public function showIndiviCampaignDetails($campaignId):array{
    $compaign = IndCompaign::with(['user', 'classification' , 'campaignStatus' , 'donations'])->findOrFail($campaignId);
    $total = $compaign->donations->sum('amount');

    $photo = IndCompaigns_photo::find($compaign->photo_id)->photo;
    $fullPath = url(Storage::url($photo));

    $lastDonation = $compaign->donations->sortByDesc('created_at')->first();

    $targetPath = 'uploads/det/defualtProfilePhoto.png';
    $userPhoto = $compaign->user->photo
             ? url(Storage::url($compaign->user->photo))
             : url(Storage::url($targetPath)) ;

         $compaingDet = [];
         $compaingDet[] = [
            'title' => $compaign->title,
            'amount_required' => $compaign->amount_required,
            'donation_amount' => $total,
            'campaign_status' => [
                  'id' => $compaign->campaign_status_id,
                  'type' => $compaign->campaignStatus->status_type
            ],
            'photo_id' => [
                  'id' =>$compaign->photo_id ,
                  'photo' => $fullPath
            ],
            'compaigns_time_to_end' => Carbon::now()->diff($compaign->compaigns_end_time)->format('%m Months %d Days %h Hours'),
            'description' => $compaign->description,
            'campaign_start_time' => $compaign->compaigns_start_time,
            'campaign_end_time' => $compaign->compaigns_end_time,
            'last_donation_time' => $lastDonation ? $lastDonation->created_at->diffForHumans() : 'no Donations yet',
            'location' => $compaign->location,
            'classification' => [
                  'id' => $compaign->classification_id,
                  'type' => $compaign->classification->classification_name
            ],
            'user' => [
                'name' => $compaign->user->name,
                'photo' => $userPhoto,
            ],
            'type' => 'individual',
            'amount_to_Complete' => $compaign->amount_required - $total,
    ];

        $message = 'individual campaign details are retrived sucessfully';

         return ['campaign' => $compaingDet , 'message' => $message];
}

     //1 view all classifications
    public function getClassification():array{
        $classifications = Classification::all();
        foreach ($classifications as $classification) {
            $classifications_name [] = ['id' => $classification->id  , 'classification_name' => $classification->classification_name];
        }
        $message = 'all classifications are retrived successfully';

        return ['classifications' =>  $classifications_name , 'message' => $message];
     }

    //2 view all Availability Type
    public function getAvailabilityType():array{
        $availabilityTypes = AvailabilityType::all();
        foreach ($availabilityTypes as $availabilityType) {
            $availabilityTypes_name [] = ['id' => $availabilityType->id  , 'AvailabilityType_name' => $availabilityType->name];
        }
        $message = 'all availability types are retrived successfully';

        return ['availabilityTypes' =>  $availabilityTypes_name , 'message' => $message];
     }

    //3 view all cities
    public function getCities():array{
        $cities = City::all();
        foreach ($cities as $city) {
            $cities_name [] = ['id' => $city->id  , 'city_name' => $city->name];
        }
        $message = 'all cities are retrived successfully';

        return ['cities' =>  $cities_name , 'message' => $message];
     }

    //4 view all genders
    public function getGender():array{
        $gender = Gender::all();
        foreach ($gender as $gen) {
            $gender_name [] = ['id' => $gen->id  , 'gender_type' => $gen->type];
        }
        $message = 'all genders are retrived successfully';

        return ['gender' =>  $gender_name , 'message' => $message];
     }

    }
