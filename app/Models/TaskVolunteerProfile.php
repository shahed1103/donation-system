<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskVolunteerProfile extends Model
{    protected $table = 'task_volunteer_profile';

    protected $fillable = ['volunteer_profile_id',
                           'volunteer_task_id',
                            'status_id'
                        ];

    public function status()
    {
        return $this->belongsTo(TaskStatus::class);
    }

    public function volunteerProfile()
{
    return $this->belongsTo(VolunteerProfile::class, 'volunteer_profile_id');
}
}
