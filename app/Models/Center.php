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
              'space' ,
                'have_frez'
    ];

    public function inkindDonations(){
        return $this->hasMany(InkindDonation::class);
    }
}
