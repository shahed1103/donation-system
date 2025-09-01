<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Association;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class AssociationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            'جمعية الخير',
            'التعليم للجميع',
            'مؤسسة الصحة أولاً',
        ];

        $locations = [
            'الرياض-الملز',
            'جدة-التحلية',
            'الدمام-الراكة',
        ];


        $descriptions = [
            'منظمة مكرسة لدعم الأسر المحتاجة وتعزيز التضامن الاجتماعي.',
            'توفر فرص تعليمية مجانية للطلاب من خلفيات ذات دخل منخفض.',
            'تقدم خدمات الرعاية الصحية الأساسية لأكثر المجتمعات ضعفًا.',
        ];


         $association_owner_id = [
                    2,
                    2,
                    2        ];

         $date_start_working = ['2020-01-01' , ' 2020-01-01' , '2020-01-01'];
         $date_end_working = ['2020-01-01' , ' 2020-01-01' , '2020-01-01'];


        $photos = [
                    'asso2.jpg',
                    'asso3.jpg',
                    'asso4.jpg',
                ];

        $fullPaths = [];

        $sourceDir = public_path('uploads/seeder_photos/');
        $targetDir = 'uploads/assocphotos/';

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


        for ($i = 0; $i < 3; $i++) {
            Association::create([
                'name' => $names[$i],
                'location' => $locations[$i],
                'photo' => $fullPaths[$i],
                'description' => $descriptions[$i],
                'association_owner_id' => $association_owner_id[$i],
                'date_start_working' => $date_start_working[$i],
                'date_end_working' => $date_end_working [$i]
            ]);
        }
    }
}
