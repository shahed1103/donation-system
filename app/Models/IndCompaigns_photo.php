<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndCompaigns_photo extends Model
{
        use HasFactory;


    protected $fillable = [
        'id',
        'title',
        'description',
        'classification_id',
        'location',
        'amount_required',
        //'photo_id',
        ///
        'user_id',
        'acceptance_status_id',
        'campaign_status_id'
    ];
    
        public function indCampaigns()
    {
        return $this->hasMany(IndCompaigns::class);
    }
}
