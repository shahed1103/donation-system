<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\AssociationCampaign;
use App\Models\VolunteerTask;

class AssociationCampaignTaskSeeder extends Seeder
{
    public function run()
    {
        $campaignIds = AssociationCampaign::pluck('id')->toArray();
        $taskIds = VolunteerTask::pluck('id')->toArray();

        if (empty($campaignIds) || empty($taskIds)) {
            $this->command->info('لا يوجد بيانات كافية في الحملات أو المهام!');
            return;
        }

        $count = 10;

        for ($i = 0; $i < $count; $i++) {
            DB::table('association_campaign_task')->insert([
                'association_campaign_id' => $campaignIds[array_rand($campaignIds)],
                'volunteer_task_id' => $taskIds[array_rand($taskIds)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
