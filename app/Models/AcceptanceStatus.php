<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class AcceptanceStatus extends Model
{
        use HasFactory;


    protected $fillable = [
        'id',
        'status_type',
    ];

        public function indCampaigns()
    {
        return $this->hasMany(IndCompaign::class);
    }
}
