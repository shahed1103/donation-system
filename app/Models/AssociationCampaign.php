<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CampaignStatusUpdater;


class AssociationCampaign extends Model
{
        use CampaignStatusUpdater;
        
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
        'compaigns_time',
        'photo',
        'emergency_level',
        'tasks_start_time',
        'tasks_end_time'
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

    // public function volunteerTasks()
    // {
    // return $this->belongsToMany(VolunteerTask::class, 'association_campaign_task', 'association_campaign_id', 'volunteer_task_id');
    // }

        public function volunteerTasks()
    {
        return $this->hasMany(VolunteerTask::class , 'association_campaign_id');
    }

}
