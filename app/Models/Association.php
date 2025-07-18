<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Association extends Model
{
        use HasFactory;


    protected $fillable = [
        'name',
        'location',
        'description'
    ];

    public function associationCampaigns()
    {
        return $this->belongsToMany(AssociationCampaign::class, 'shared_association_campaigns', 'association_id', 'association_campaign_id');
    }

}
