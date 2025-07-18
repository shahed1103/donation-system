<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class CampaignStatus extends Model
{
        use HasFactory;


    protected $fillable = [
        'id',
        'status_type',
    ];

        public function indCampaigns()
    {
        return $this->hasMany(IndCompaigns::class);
    }

        public function associationCampaigns()
    {
        return $this->hasMany(AssociationCampaign::class);
    }
}
