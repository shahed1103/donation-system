<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
        protected $fillable = ['user_id', 'campaign_id', 'amount'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function IndCompaigns()
    {
        return $this->belongsTo(IndCompaigns::class);
    }
}

