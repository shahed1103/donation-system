<?php

namespace App\Services;


use Illuminate\Support\Carbon;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Session;
use Exception;
use Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Association;
use App\Models\User;
use App\Models\DonationAssociationCampaign;
use App\Models\Donation;
use App\Models\IndCompaign;
use App\Models\Classification;
use App\Models\AssociationCampaign;
use App\Models\IndCompaigns_photo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use App\Http\Responses\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Throwable;

class MobileHomeService
{
    //search campigns
    public function searchCampaigns(Request $request): array
    {
        $campaigns = [];

        if($request->has('association_name')){
        $associations = Association::where('name', 'like', '%' . $request['association_name'] . '%')->get();

        foreach ($associations as $association) {
            $activeCampaigns = $association->associationCampaigns()
                                        ->where('campaign_status_id', 1)
                                        ->distinct('id')
                                        ->get();

            foreach ($activeCampaigns as $activeCampaign) {
            $totalDonations = DonationAssociationCampaign::where('association_campaign_id', $activeCampaign->id)
                                                        ->sum('amount');
            $campaigns [] = [
                    'id' =>  $activeCampaign->id,
                    'title' => $activeCampaign->title,
                    'amount_required' => $activeCampaign->amount_required,
                    'donation_amount' => $totalDonations,
                    'photo' => url(Storage::url($activeCampaign->photo)),
                    'campaign_status_id' => [
                        'id' => $activeCampaign->campaign_status_id,
                        'campaign_status_type' => $activeCampaign->campaignStatus->status_type
                    ],
                    'compaigns_time_to_end' => Carbon::now()->diff($activeCampaign->compaigns_end_time)->format('%m Months %d Days %h Hours'),
                ];
                }
        }

            $message = 'this association campaigns are retrived sucessfully';

            return ['campaign' => $campaigns , 'message' => $message];
    }

    if($request->has('classification_name')){
        $classification = Classification::where('classification_name', 'like', '%' . $request['classification_name'] . '%')->first();

        if ($classification) {
            $assocCampaignsByClass = AssociationCampaign::where('classification_id', $classification->id)
                                                        ->where('campaign_status_id', 1)
                                                        ->get();



            foreach ($assocCampaignsByClass as $assocCampaignsByClas) {
            $totalDonations = DonationAssociationCampaign::where('association_campaign_id', $assocCampaignsByClas->id)
                                                        ->sum('amount');
            $campaigns [] = [
                    'id' =>  $assocCampaignsByClas->id,
                    'title' => $assocCampaignsByClas->title,
                    'amount_required' => $assocCampaignsByClas->amount_required,
                    'donation_amount' => $totalDonations,
                    'photo' => url(Storage::url($assocCampaignsByClas->photo)),
                    'campaign_status_id' => [
                        'id' => $assocCampaignsByClas->campaign_status_id,
                        'campaign_status_type' => $assocCampaignsByClas->campaignStatus->status_type
                    ],
                    'compaigns_time_to_end' => Carbon::now()->diff($assocCampaignsByClas->compaigns_end_time)->format('%m Months %d Days %h Hours'),
                ];
                }

            $individualCampaigns = IndCompaign::where('classification_id', $classification->id)
                                                    ->where('campaign_status_id', 1)
                                                    ->get();

            foreach ($individualCampaigns as $individualCampaign) {
            $totalDonations = Donation::where('campaign_id', $individualCampaign->id)
                                                        ->sum('amount');

            $photo = IndCompaigns_photo::find($individualCampaign->photo_id)->photo;
            $fullPath = url(Storage::url($photo));   

            $campaigns [] = [
                    'id' =>  $individualCampaign->id,
                    'title' => $individualCampaign->title,
                    'amount_required' => $individualCampaign->amount_required,
                    'donation_amount' => $totalDonations,
                    'campaign_status_id' => [
                        'id' => $individualCampaign->campaign_status_id,
                        'campaign_status_type' => $individualCampaign->campaignStatus->status_type
                    ],
                    'photo_id' => [
                        'id' =>$individualCampaign->photo_id ,
                        'photo' => $fullPath
                    ],
                    'compaigns_time_to_end' => Carbon::now()->diff($individualCampaign->compaigns_end_time)->format('%m Months %d Days %h Hours'),
                ];
                }
        }
                $message = 'campaigns with this classification are retrived sucessfully';

            return ['campaign' => $campaigns , 'message' => $message];
    }

            $message = 'there is no campigns' ;

            return ['campaign' => $campaigns , 'message' => $message];

    }

    // view emergency compaings active
    public function emergencyCompaings(): array {
     $associationCamignsEme = AssociationCampaign::where('campaign_status_id' , 1)
                                                    ->orderBy('emergency_level', 'desc')
                                                    ->get();

     $individualCamignsEme = IndCompaign::where('campaign_status_id' , 1)
                                        ->orderBy('emergency_level', 'desc')
                                        ->get();

    $campaigns = [];
    foreach ($associationCamignsEme as $associationCamignsEm) {
        $totalDonations = DonationAssociationCampaign::where('association_campaign_id', $associationCamignsEm->id)
                                                        ->sum('amount');
            $campaigns [] = [
            'id' => $associationCamignsEm->id,
            'title' => $associationCamignsEm->title,
            'location' => $associationCamignsEm->location,
            'donation_amount' => $totalDonations,
            'photo' => url(Storage::url($associationCamignsEm->photo)),
            'emergency_level' => $associationCamignsEm->emergency_level,
        ];
    }

    foreach ($individualCamignsEme as $individualCamignsEm) {
        $totalDonations = Donation::where('campaign_id', $individualCamignsEm->id)
                                    ->sum('amount');
        $photo = IndCompaigns_photo::find($individualCamignsEm->photo_id)->photo;
        $fullPath = url(Storage::url($photo));

            $campaigns [] = [
            'id' => $individualCamignsEm->id,
            'title' => $individualCamignsEm->title,
            'location' => $individualCamignsEm->location,
            'donation_amount' => $totalDonations,
            'photo' => ['id' =>$individualCamignsEm->photo_id ,'photo' => $fullPath],
            'emergency_level' => $individualCamignsEm->emergency_level,
        ];
    }

        usort($campaigns, function ($a, $b) {
        return $b['emergency_level'] <=> $a['emergency_level'];
    });

        foreach ($campaigns as &$campaign) {
        unset($campaign['emergency_level']);
    }

    $message = 'emergency compaings are retrived sucessfully' ;

    return ['emergency compaings' => $campaigns , 'message' => $message];
    } 
}


 