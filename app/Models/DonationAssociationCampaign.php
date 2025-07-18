<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonationAssociationCampaign extends Model
{
        protected $fillable = ['user_id', 'association_campaign_id', 'amount'];


        public function user()
    {
        return $this->belongsTo(User::class);
    }

        public function associationCompaigns()
    {
        return $this->belongsTo(AssociationCompaign::class);
    }
}
