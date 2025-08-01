<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leader_form extends Model
{

    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'visit_date',
        'leader_name',
        'location_type',
        'description',
        'number_of_beneficiaries',
        'beneficiary_type',
        'need_type',
        'is_need_real',
        'has_other_support',
        'marks_from_5',
        'notes',
        'recommendation',
    ];


    public function campaign()
    {
        return $this->belongsTo(IndCompaign::class, 'campaign_id');
    }
}

