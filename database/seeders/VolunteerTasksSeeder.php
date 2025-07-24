<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VolunteerTask;

class VolunteerTasksSeeder extends Seeder
{
    public function run()
    {
        $names = [
            'تنظيم الفعاليات',
            'التوعية المجتمعية',
            'الدعم اللوجستي',
        ];

        $descriptions = [
            'المساعدة في تنظيم وإدارة الفعاليات التطوعية.',
            'تنفيذ حملات توعية في المجتمع.',
            'توفير الدعم اللوجستي للحملات المختلفة.',
        ];

        $status_ids = [1, 2, 3];

        $hours = [5, 3, 4];

        for ($i = 0; $i < 3 ; $i++) {
            VolunteerTask::create([
                'name' => $names[$i],
                'description' => $descriptions[$i],
                'status_id' => $status_ids[$i],
                'hours' => $hours[$i],
            ]);
        }
    }
}
