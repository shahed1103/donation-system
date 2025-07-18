<?php


namespace App\Services;

use App\Models\User;
use App\Models\IndCompaigns;
use App\Models\Classification;
use App\Models\Association;
use App\Models\CampaignStatus;
use App\Models\DonationAssociationCampaign;
use App\Models\SharedAssociationCampaign;
use App\Models\AssociationCampaign;
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
use Carbon\Carbon;

class AssociationCompaignsService
{

    // view associations compaings active
     public function viewAssociationsCompaingsActive($id): array{
        $associationsCampaigns = AssociationCampaign::where('campaign_status_id' , 1)
                                                    ->where('classification_id', $id)
                                                    ->get();
        $compaingAll = [];
        foreach ($associationsCampaigns as $associationCampaign) {
                $classification_name = Classification::find($associationCampaign->classification_id)->classification_name;
                $campaign_status_type = CampaignStatus::find($associationCampaign->campaign_status_id)->status_type;
          //  if($campaign_status_type === "Closed"){

        $campaign_ids = DonationAssociationCampaign::where('association_campaign_id' , $associationCampaign->id)->get();


        $total  = 0;
             foreach ($campaign_ids as $campaign_id) {
                $total += $campaign_id->amount;
             }

            $compaingAll[] = 
        [ 
        'title' =>  $associationCampaign->title,
        'amount_required' =>  $associationCampaign->amount_required,
        'donation_amount' => $total,
        'campaign_status_id'=>  ['id' => $associationCampaign->campaign_status_id, 'campaign_status_type' => $campaign_status_type],
        'compaigns_time_to_end' => Carbon::now()->diff($associationCampaign->compaigns_end_time)->format('%M Months %D Day %H Hours')
        ];
             
            }
    //     if($campaign_status_type === "Active" || $campaign_status_type === "Complete"){
        //      $campaign_ids = Donation::where('campaign_id' , $compaign->id)->get();


        // $total  = 0;
        //      foreach ($campaign_ids as $campaign_id) {
        //         $total += $campaign_id->amount;
        //      }
    //         $compaingAll[] = 
    //     [ 
    //     'title' =>  $compaign->title,
    //     'amount_required' =>  $compaign->amount_required,
    //     'donation_amount' => $total,
    //     'acceptance_status_id'=>  ['id' => $compaign->acceptance_status_id, 'acceptance_status_type' => $acceptance_status_type],
    //     'campaign_status_id'=>  ['id' => $compaign->campaign_status_id, 'campaign_status_type' => $campaign_status_type],
    //     // 'compaigns_time' =>  $compaign->compaigns_time,
    //     'compaigns_time_to_end' => Carbon::now()->diff($compaign->compaigns_end_time)->format('%M Months %D Day %H Hours')
    //     ];
    //   //  }
    //     }

        $message = 'Your campaign retrived sucessfully';

        return ['associations Campaigns' =>  $compaingAll , 'message' => $message];
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
        // 'compaigns_time' =>  $compaign->compaigns_time,
        'compaigns_time_to_end' => $compaign->compaigns_end_time - $compaign->compaigns_start_time,
        ];
        }

        }

        $message = 'Your campaign retrived sucessfully';

        return ['campaign' =>   $compaingAll , 'message' => $message];
     }

}
