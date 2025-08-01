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

        $hours = [5, 3, 4];
        
        $number_volunter_need =  [10 , 20 , 25];

        for ($i = 0; $i < 3 ; $i++) {
            VolunteerTask::create([
                'name' => $names[$i],
                'description' => $descriptions[$i],
                'hours' => $hours[$i],
                'association_campaign_id' => rand(1,4),
                'number_volunter_need' => $number_volunter_need[$i]
            ]);
        }
    }
}
