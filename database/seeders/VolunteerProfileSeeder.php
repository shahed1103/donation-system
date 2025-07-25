<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\VolunteerProfile;

class VolunteerProfileSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 0; $i < 10; $i++) {
            VolunteerProfile::create([
                'user_id' => rand(4, 15), 
                'skills' => 'Programming, Design, Leadership',
                'availability_type_id' => rand(1, 2), 
                'availability_hours' => rand(1, 5),
                'preferred_tasks' => 'Event organization, Supervision, Tech support',
                'academic_major' => 'Software Engineering',
                'previous_volunteer_work' => 'Participated in a park cleanup and health awareness campaign',
            ]);
        }
    }
}
