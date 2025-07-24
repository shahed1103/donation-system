<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvailabilityType extends Model
{
    protected $fillable = ['name'];

    public function volunteerProfiles()
    {
        return $this->hasMany(VolunteerProfile::class);
    }
}
