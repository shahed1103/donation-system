<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndCompaigns_photo extends Model
{
        use HasFactory;


    protected $fillable = [
        'id',
        'photo'
    ];

        public function indCampaigns()
    {
        return $this->hasMany(IndCompaign::class);
    }


}
