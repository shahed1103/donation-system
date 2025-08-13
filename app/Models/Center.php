<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Center extends Model
{
        use HasFactory;

        protected $fillable = [
        'center_name',
        'location',
    ];

    public function inkindDonations(){
        return $this->hasMany(InkindDonation::class);
    }
}
