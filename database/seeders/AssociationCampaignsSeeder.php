<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AssociationCampaign;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class AssociationCampaignsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    $titles = [
        'مبادرة دعم الصحة',
        'حملة توفير المستلزمات التعليمية',
        'تنظيف الحي',
        'حملة زراعة الأشجار',
    ];

    $descriptions = [
        'تقديم المساعدة الطبية للمجتمعات المحرومة.',
        'توفير الكتب والمواد للطلاب ذوي الدخل المنخفض.',
        'تنظيم حدث تنظيف على مستوى المجتمع.',
        'زراعة الأشجار لتحسين جودة الهواء والبيئة.',
    ];


        $photos = [
                    '1.jpg',
                    '2.jpg',
                    '3.jpg',
                    '4.jpg',
                ];

        $tasks_start_time = ['08:00:00' , '10:30:00', '13:00:00', '15:45:00'];

        $tasks_end_time = ['09:30:00' , '12:00:00' , '14:30:00' , '17:00:00'];

        $fullPaths = [];

        $sourceDir = public_path('uploads/seeder_photos/');
        $targetDir = 'uploads/assocCampignsphotos/';

        foreach ($photos as $photo) {
            $sourcePath = $sourceDir . $photo;
            $targetPath = $targetDir . $photo;

            if (File::exists($sourcePath)) {
                Storage::disk('public')->put($targetPath, File::get($sourcePath));

                // $fullPath = url(Storage::url($targetPath));
                $fullPath =  $targetPath;

                $fullPaths[] = $fullPath;
            } else {
                $fullPaths[] = null;
            }
        }

        $locations = [
            'الرياض - مركز المدينة',
            'جدة - الكورنيش',
            'الدمام - الواجهة البحرية',
            'مكة - الحرم المكي',
        ];


        $amounts = [50000, 30000, 20000, 40000];
        $emergency_level = [1,2,2,5];

        $classificationIds = [1, 1, 3, 4];
        $statusIds = [1, 2, 1 , 1];

        $compaigns_time = [1, 2, 1 , 1];

        for ($i = 0; $i < 4; $i++) {
            AssociationCampaign::create([
                'classification_id' => $classificationIds[$i],
                'title' => $titles[$i],
                'description' => $descriptions[$i],
                'location' => $locations[$i],
                'amount_required' => $amounts[$i],
                'campaign_status_id' => $statusIds[$i],
                'photo' => $fullPaths[$i],
                'tasks_start_time' => $tasks_start_time[$i],
                'tasks_end_time' => $tasks_end_time[$i],
                'emergency_level' => $emergency_level[$i],
                'compaigns_time' => $compaigns_time[$i],
                'compaigns_start_time' => Carbon::now()->subDays(rand(1, 10)),
                'compaigns_end_time' => Carbon::now()->addDays(rand(10, 30)),
            ]);
        }
    }
}
