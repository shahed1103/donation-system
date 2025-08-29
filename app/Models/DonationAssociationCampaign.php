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
        return $this->belongsTo(AssociationCampaign::class , 'association_campaign_id');
    }

    /**
     * Get all of the gifts for the DonationAssociationCampaign
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function gifts(): HasMany
    {
        return $this->hasMany(GiftDonation::class, 'donation_id' );
    }
}
