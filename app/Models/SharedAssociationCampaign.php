<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SharedAssociationCampaign extends Model
{
        protected $fillable = [
        'id',
        'association_id',
        'association_campaign_id',
    ];
}
