<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\VolunteerProfile;
use App\Models\VolunteerTask;

class TaskVolunteerProfileSeeder extends Seeder
{
    public function run()
    {
        $profileIds = VolunteerProfile::pluck('id')->toArray();
        $taskIds = VolunteerTask::pluck('id')->toArray();

        if (empty($profileIds) || empty($taskIds)) {
            $this->command->warn('لا توجد بيانات كافية في volunteer_profiles أو volunteer_tasks.');
            return;
        }

        $status_ids = [1, 2, 3 , 4, 1 , 2, 3 , 4, 1, 2];

        $count = 10;

        for ($i = 0; $i < $count; $i++) {
            
            DB::table('task_volunteer_profile')->insert([
                'volunteer_profile_id' => $profileIds[array_rand($profileIds)],
                'volunteer_task_id' => $taskIds[array_rand($taskIds)],
                'status_id' => $status_ids[$i],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
