<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InkindDonation extends Model
{
         use HasFactory;

        protected $fillable = [
        'donation_type_id',
        'name_of_donation',
        'amount',
        'description',
        'status_of_donation_id',
        'center_id',
    ];

    public function donationType(){
        return $this->belongsTo(DonationType::class);
    }

    public function center(){
        return $this->belongsTo(Center::class);
    }

    public function statusOfDonation(){
        return $this->belongsTo(StatusOfDonation::class);
    }

    public function inkindDonationPhotos(){
        return $this->hasMany(InkindDonationPhoto::class);
    }
}
