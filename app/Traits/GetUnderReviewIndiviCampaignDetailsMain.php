<?php

namespace App\Traits;

use Storage;
use App\Models\IndCompaign;
use App\Models\IndCompaigns_photo;




trait GetUnderReviewIndiviCampaignDetailsMain
{
    public function getUnderReviewIndiviCampaignDetailsMain($campaignId):array{
        $compaign = IndCompaign::with(['user', 'classification' , 'campaignStatus' , 'donations'])->findOrFail($campaignId);
        $photo = IndCompaigns_photo::find($compaign->photo_id)->photo;
        $fullPath = url(Storage::url($photo));
        $targetPath = 'uploads/det/defualtProfilePhoto.png';
        $userPhoto = $compaign->user->photo
                ? url(Storage::url($compaign->user->photo))
                : url(Storage::url($targetPath)) ;

            $compaingDet = [];
            $compaingDet[] = [
                'title' => $compaign->title,
                'amount_required' => $compaign->amount_required,
                'compaigns_time' => $compaign->compaigns_time,
                'campaign_status' => [
                    'id' => $compaign->campaign_status_id,
                    'type' => $compaign->campaignStatus->status_type
                ],
                'photo_id' => [
                    'id' =>$compaign->photo_id ,
                    'photo' => $fullPath
                ],
                'description' => $compaign->description,
                'location' => $compaign->location,
                'classification' => [
                    'id' => $compaign->classification_id,
                    'type' => $compaign->classification->classification_name
                ],
                'user' => [
                    'name' => $compaign->user->name,
                    'photo' => $userPhoto,
                ]
        ];

            $message = 'individual campaign details are retrived sucessfully';
            return ['campaign' => $compaingDet , 'message' => $message];
    }
}
