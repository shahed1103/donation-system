<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DonationType extends Model
{
        use HasFactory;


    protected $fillable = [
        'donation_Type',
    ];

    public function inkindDonations(){
        return $this->hasMany(InkindDonation::class);
    }
}
