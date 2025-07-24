<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class IndCompaign extends Model
{
        use HasFactory;


    protected $fillable = [
        'id',
        'title',
        'description',
        'classification_id',
        'location',
        'amount_required',
        'photo_id',
        'user_id',
        'acceptance_status_id',
        'campaign_status_id',
        'compaigns_time',
        'photo',
        'emergency_level'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

   public function classification(){
        return $this->belongsTo(Classification::class);
    }

    public function indCompaignsPhoto(){
        return $this->belongsTo(IndCompaigns_photo::class);
    }

        public function campaignStatus(){
        return $this->belongsTo(CampaignStatus::class);
    }

        public function acceptanceStatus(){
        return $this->belongsTo(AcceptanceStatus::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }


}
