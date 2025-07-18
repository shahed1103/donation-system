<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Classification extends Model
{
        use HasFactory;


    protected $fillable = [
        'id',
        'classification_name',
    ];

        public function indCampaigns()
    {
        return $this->hasMany(IndCompaigns::class);
    }
}
