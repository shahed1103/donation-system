<?php

namespace Database\Seeders;

use App\Models\Leader_form;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class LeaderFormSeeder extends Seeder
{
    public function run(): void
    {
        $locationTypes = ['منزل', 'مخيم', 'مدرسة', 'شارع', 'مركز صحي'];
        $beneficiaryTypes = ['أسر فقيرة', 'أطفال', 'مرضى', 'لاجئين'];
        $needTypes = ['غذاء', 'دواء', 'ملابس', 'تعليم'];

        for ($i = 1; $i <= 5; $i++) {
            Leader_form::create([
                'campaign_id' => $i,
                'visit_date' => Carbon::now()->subDays(rand(1, 30)),
                'leader_name' => 'القائد ' . $i,
                'location_type' => $locationTypes[array_rand($locationTypes)],
                'description' => 'وصف الحالة للموقع رقم ' . $i,
                'number_of_beneficiaries' => rand(10, 100),
                'beneficiary_type' => $beneficiaryTypes[array_rand($beneficiaryTypes)],
                'need_type' => $needTypes[array_rand($needTypes)],
                'is_need_real' => (bool)rand(0, 1),
                'has_other_support' => (bool)rand(0, 1),
                'marks_from_5' => rand(1, 5),
                'notes' => 'ملاحظة إضافية رقم ' . $i,
                'recommendation' => ['نوصي بالقبول', 'نوصي بالرفض', 'نوصي بإعادة التقييم'][rand(0, 2)],
            ]);
        }
    }
}
