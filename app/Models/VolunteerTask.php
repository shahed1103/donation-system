<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VolunteerTask extends Model
{
    protected $fillable = ['name' , 'description' , 'hours' , 'number_volunter_need' , 'association_campaign_id'];

    public function volunteerProfiles()
    {
    return $this->belongsToMany(VolunteerProfile::class, 'task_volunteer_profile');
    }

    public function associationCampaigns(){
        return $this->belongsTo(AssociationCampaign::class , 'association_campaign_id');
    }

}
