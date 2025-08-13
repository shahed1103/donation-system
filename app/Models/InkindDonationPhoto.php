<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InkindDonationPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'photo',
        'inkind_donation_id',
    ];

    public function inkindDonation(){
        return $this->belongsTo(InkindDonation::class);
    }
}
