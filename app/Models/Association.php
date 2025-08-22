<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Association extends Model
{
        use HasFactory;


    protected $fillable = [
        'name',
        'location',
        'description',
        'association_owner_id',
        'date_start_working',
        'date_end_working',
        'photo',


    ];

    public function associationCampaigns()
    {
        return $this->belongsToMany(AssociationCampaign::class, 'shared_association_campaigns');
    }

    public function owner()
{
    return $this->belongsTo(User::class, 'user_id');
}

}
