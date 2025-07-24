<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskVolunteerProfile extends Model
{
    protected $fillable = ['volunteer_profile_id',
                           'volunteer_task_id'];
}
