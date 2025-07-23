<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssociationCampaign extends Model
{
        protected $fillable = [
        'id',
        'classification_id',
        'title',
        'description',
        'location',
        'amount_required',
        'campaign_status_id',
        'compaigns_start_time',
        'compaigns_end_time',
        'photo',
        'emergency_level'
    ];

        public function campaignStatus(){
        return $this->belongsTo(CampaignStatus::class);
    }
        public function classification(){
        return $this->belongsTo(Classification::class);
    }

        public function associations()
    {
        return $this->belongsToMany(Association::class, 'shared_association_campaigns');
    }

        public function donationAssociationCampaigns()
    {
        return $this->hasMany(DonationAssociationCampaign::class);
    }
}
