<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssociationCampaignTask extends Model
{
        protected $fillable = [
        'association_campaign_id',
        'volunteer_task_id',
    ];
}
