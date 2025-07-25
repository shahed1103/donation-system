<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VolunteerTask extends Model
{
    protected $fillable = ['name' , 'description' , 'status_id' , 'hours' , 'association_campaign_id'];

    public function volunteerProfiles()
    {
    return $this->belongsToMany(VolunteerProfile::class, 'task_volunteer_profile');
    }

    public function status()
    {
        return $this->belongsTo(TaskStatus::class);
    }

    // public function associationCampaigns()
    // {
    // return $this->belongsToMany(AssociationCampaign::class, 'association_campaign_task', 'volunteer_task_id', 'association_campaign_id');
    // }

    public function associationCampaigns(){
        return $this->belongsTo(AssociationCampaign::class , 'association_campaign_id');
    }

}
