<?php


namespace App\Services;

use App\Models\User;
use App\Models\IndCompaigns;
use App\Models\Classification;
use App\Models\AcceptanceStatus;
use App\Models\CampaignStatus;
use App\Models\Donation;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Session;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use App\Http\Responses\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Throwable;

class IndividualCompaignsService
{

    public function createIndiviCompa($request): array{
    
        $user= Auth::user()->id;

        $campaign = IndCompaigns::create([
                'title' =>  $request['title'],
                'description' => $request['description'],
                'classification_id' => $request['classification_id'],
                'location' => $request['location'],
                'amount_required' =>  $request['amount_required'],
                'user_id' => $user,
                'compaigns_time' =>  $request['compaigns_time'],
            //    'acceptance_status_id'=>  $request['acceptance_status_id'],
            //    'campaign_status_id'=>  $request['campaign_status_id'],
       ]);
       $campaign->refresh();
       $classification_name = Classification::find($request['classification_id'])->classification_name;
       $acceptance_status_type = AcceptanceStatus::find($campaign->acceptance_status_id)->status_type;
       $campaign_status_type = CampaignStatus::find($campaign->campaign_status_id)->status_type;
       $campaign_dett = [
        'title' =>  $request['title'],
        'description' => $request['description'],
        'classification_id' => ['id' => $request['classification_id'], 'classification_name' => $classification_name] ,
        'location' => $request['location'],
        'amount_required' =>  $request['amount_required'],
        'acceptance_status_id'=>  ['id' => $campaign->acceptance_status_id, 'acceptance_status_type' => $acceptance_status_type],
        'campaign_status_id'=>  ['id' => $campaign->campaign_status_id, 'campaign_status_type' => $campaign_status_type],
        'compaigns_time' =>  $request['compaigns_time'],
];

        $message = 'Your campaign created sucessfully';

        return ['campaign' =>  $campaign_dett , 'message' => $message];
    }

// view my individual compaings active + closed
     public function viewMyIndiviCompa(): array{
         $user= Auth::user()->id;

        $campaigns = IndCompaigns::where('user_id' , $user)->get();
        $compaingAll = [];
        foreach ($campaigns as $compaign) {
                $classification_name = Classification::find($compaign->classification_id)->classification_name;
                $acceptance_status_type = AcceptanceStatus::find($compaign->acceptance_status_id)->status_type;
                $campaign_status_type = CampaignStatus::find($compaign->campaign_status_id)->status_type;
            if($campaign_status_type === "Closed"){

            $compaingAll[] = 
        [ 
        'title' =>  $compaign->title,
        'amount_required' =>  $compaign->amount_required,
        'donation_amount' => 0,
        'acceptance_status_id'=>  ['id' => $compaign->acceptance_status_id, 'acceptance_status_type' => $acceptance_status_type],
        'campaign_status_id'=>  ['id' => $compaign->campaign_status_id, 'campaign_status_type' => $campaign_status_type],
        'compaigns_time' =>  $compaign->compaigns_time,
        ];
             
            }
        if($campaign_status_type === "Active"){
             $campaign_ids = Donation::where('campaign_id' , $compaign->id)->get();
        $total  = 0;
             foreach ($campaign_ids as $campaign_id) {
                $total += $campaign_id->amount;
             }
            $compaingAll[] = 
        [ 
        'title' =>  $compaign->title,
        'amount_required' =>  $compaign->amount_required,
        'donation_amount' => $total,
        'acceptance_status_id'=>  ['id' => $compaign->acceptance_status_id, 'acceptance_status_type' => $acceptance_status_type],
        'campaign_status_id'=>  ['id' => $compaign->campaign_status_id, 'campaign_status_type' => $campaign_status_type],
        'compaigns_time' =>  $compaign->compaigns_time,
        ];
        }
        }

        $message = 'Your campaign retrived sucessfully';

        return ['campaign' =>  $compaingAll , 'message' => $message];
     }

// view individual compaings active 
     public function viewIndiviCompa($id): array{
        $campaigns = IndCompaigns::where('classification_id' , $id)->get();
         $compaingAll = [];
        foreach ($campaigns as $compaign) {
                $classification_name = Classification::find($compaign->classification_id)->classification_name;
                $campaign_status_type = CampaignStatus::find($compaign->campaign_status_id)->status_type;

        if($campaign_status_type === "Active"){
             $campaign_ids = Donation::where('campaign_id' , $compaign->id)->get();
        $total  = 0;
             foreach ($campaign_ids as $campaign_id) {
                $total += $campaign_id->amount;
             }

        $compaingAll [] = 
        [ 
        'title' =>  $compaign->title,
        'amount_required' =>  $compaign->amount_required,
        'donation_amount' => $total,
        'campaign_status_id'=>  ['id' => $compaign->campaign_status_id, 'campaign_status_type' => $campaign_status_type],
        'compaigns_time' =>  $compaign->compaigns_time,
        ];
        }

        }

        $message = 'Your campaign retrived sucessfully';

        return ['campaign' =>   $compaingAll , 'message' => $message];
     }

    //       public function getCities(){
    //     $cities = City::all();
    //     foreach ($cities as $city) {
    //         $cities_name [] = ['id' => $city->id  , 'city_name' => $city->city_name];
    //     }
    //     $all['cities'] = $cities_name;
    //     $message = 'all cities are retrived successfully';
    //     return Response::Success($all, $message);
    //  }
//view individual compaings complete


}
