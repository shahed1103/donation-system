<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class InkindDonationAcceptence extends Model
{
        use HasFactory;

        protected $fillable = [
        'status',
    ];

        public function inkindDonations(){
        return $this->hasMany(InkindDonation::class);
    }
}
