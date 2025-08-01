<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VolunteerProfile extends Model
{
        protected $fillable = [
        'user_id',
        'availability_type_id',
        'skills',
        'availability_hours',
        'preferred_tasks',
        'academic_major',
        'previous_volunteer_work',
    ];

    public function user()
    {
    return $this->belongsTo(User::class , 'user_id');
    }

    public function availabilityType()
    {
        return $this->belongsTo(AvailabilityType::class);
    }

    public function tasks()
{
    return $this->belongsToMany(VolunteerTask::class, 'task_volunteer_profile');
}


}
