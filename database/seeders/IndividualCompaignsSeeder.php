<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IndCompaign;
use Carbon\Carbon;

class IndividualCompaignsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $titles = [
            'دعم علاج السرطان',
            'كتب لدار الأيتام',
            'تنظيف الحي',
            'مبادرة زراعة الأشجار',
            'المساعدات الطبية للاجئين',
            'أدوات تعليمية للأطفال في المناطق الريفية',
            'حملة جمع النفايات',
            'مراقبة جودة الهواء',
            'الدعم الصحي الطارئ',
            'المنح الدراسية للطلاب',
            'حملة تنظيف الشاطئ'
        ];

        $descriptions = [
            'جمع التبرعات لمساعدة مرضى السرطان في تحمل تكاليف العلاج الكيميائي.',
            'توفير الكتب التعليمية لدار الأيتام.',
            'تنظيم حملة تنظيف في حيّنا المحلي.',
            'الهدف هو زراعة 500 شجرة في المناطق الحضرية.',
            'توفير الاحتياجات الطبية الأساسية للعائلات النازحة.',
            'توزيع أدوات مدرسية للأطفال في القرى النائية.',
            'تحسين sanitation من خلال جمع النفايات المنظم.',
            'تركيب حساسات لتتبع التلوث في المدينة.',
            'الدعم الصحي الفوري للحالات الحرجة.',
            'مساعدة الطلاب من الأسر الفقيرة في الحصول على التعليم العالي.',
            'تنظيف النفايات البلاستيكية والقمامة من الشاطئ.'
        ];

        $locations = [
            'عمان، الأردن',
            'القاهرة، مصر',
            'الرياض، المملكة العربية السعودية',
            'بيروت، لبنان',
            'إربد، الأردن',
            'تونس، تونس',
            'الخرطوم، السودان',
            'مسقط، عمان',
            'رام الله، فلسطين',
            'الجزائر، الجزائر',
            'جدة، المملكة العربية السعودية'
        ];


        $photoRanges = [
                1 => [1, 4],
                2 => [5, 8],
                3 => [9, 12],
                4 => [13, 16],
        ];

        $classification_ids = [1, 2, 3, 4 , 1, 2, 3, 4 , 1, 2, 3]; // healthy, Educational, cleanliness, environmental
        $acceptance_status_ids = [1, 2, 3 , 2 , 1, 2, 3 , 2 , 1, 2, 3]; // Under review, Approved, Rejected
        $campaign_status_ids = [2, 1, 2 , 3 , 2, 1, 2 , 3 , 2, 1, 2 ]; // Active, Closed, Complete
        $emergency_level = [1,2,2,5,3,2,3,5,4,1,4];
        $amount_required = [70000,50000,100000,20000,30000,20000,30000,50000,40000,100000,40000];


        for ($i = 0; $i < 11; $i++) {
        $startTime = null;
        $endTime = null;

        if ($acceptance_status_ids[$i] == 2) {
            $startTime = Carbon::now();
            $endTime = (clone $startTime)->addDays(rand(7, 30));
        }

            IndCompaign::query()->create([
                'title' => $titles[$i],
                'description' => $descriptions[$i],
                'classification_id' => $classification_ids[$i],
                'location' => $locations[$i],
                'amount_required' => $amount_required[$i],
                'user_id' => rand(1, 5),
                'photo_id' => rand(...$photoRanges[$classification_ids[$i]]),
                'acceptance_status_id' => $acceptance_status_ids[$i],
                'campaign_status_id' => $campaign_status_ids[$i],
                'compaigns_start_time' => $startTime,
                'compaigns_end_time' => $endTime,
                'compaigns_time' => rand(7, 30),
                'emergency_level' => $emergency_level[$i],

            ]);
        }
    }
}
