<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiftDonation extends Model
{
    protected $fillable = [
        'donation_id',
        'recipient_name',
        'recipient_phone',
        'message',
        'token',
    ];

    public function donationAssociationCampaigns()
    {
        return $this->belongsTo(DonationAssociationCampaign::class);
    }}
