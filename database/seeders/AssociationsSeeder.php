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
            'جمعية نقطة حليب',          //4
            'مؤسسة عطايا الخير',       //5
            'جمعية سند',                //6
            'جمعية كن عونا',           //7
            'جمعية حوران ' ,           //8
            ' مؤسسة الإحسان' ,          //9
            'جمعية ارتقاء'             //10

        ];

        $locations = [
            'دمشق - المزة',           //1
            'حلب - الشهباء',          //2
            'حمص - شارع الدبلان',      //3
            'اللاذقية - الشاطئ' ,      //4
            'حماة - مورك' ,            //5
            'طرطوس - الناحية',         //6
            'إدلب - الدنا',            //7
            'درعا - ساحة الدلة',      //8
            'دمشق - الميدان' ,        //9
            'دمشق - المخيم'           //10
        ];


        $descriptions = [
            'منظمة مكرسة لدعم الأسر المحتاجة وتعزيز التضامن الاجتماعي.',
            'توفر فرص تعليمية مجانية للطلاب من خلفيات ذات دخل منخفض.',
            'تقدم خدمات الرعاية الصحية الأساسية لأكثر المجتمعات ضعفًا.',
            'منظمة مكرسة لدعم الأسر المحتاجة وتعزيز التضامن الاجتماعي.',
            'توفر فرص تعليمية مجانية للطلاب من خلفيات ذات دخل منخفض.',
            'تقدم خدمات الرعاية الصحية الأساسية لأكثر المجتمعات ضعفًا.',
            'منظمة مكرسة لدعم الأسر المحتاجة وتعزيز التضامن الاجتماعي.',
            'توفر فرص تعليمية مجانية للطلاب من خلفيات ذات دخل منخفض.',
            'تقدم خدمات الرعاية الصحية الأساسية لأكثر المجتمعات ضعفًا.',
            'تقدم خدمات الرعاية الصحية الأساسية لأكثر المجتمعات ضعفًا.',
        ];

//3 - 15->24
         $association_owner_id = [
                    3,
                    3,
                    22,
                    15,
                    16,
                    17,
                    18,
                    19,
                    20,
                    21
     ];

         $date_start_working = ['2020-01-01' , ' 2020-01-01' , '2020-01-01' ,'2020-01-01' ,
         ' 2020-01-01' , '2020-01-01', '2020-01-01' ,
        ' 2020-01-01' , '2020-01-01','2020-01-01' ];


         $date_end_working = ['2026-01-01' , ' 2027-01-01' , '2028-01-01' ,
        '2026-01-01' , ' 2027-01-01' , '2028-01-01',
    '2026-01-01' , ' 2027-01-01' , '2028-01-01',
'2026-01-01'];


        $photos = [
                    'asso2.jpg',
                    'asso3.jpg',
                    'asso4.jpg',
                    'asso2.jpg',
                    'asso3.jpg',
                    'asso4.jpg',
                    'asso2.jpg',
                    'asso3.jpg',
                    'asso4.jpg',
                    'asso2.jpg',

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


        for ($i = 0; $i < 10; $i++) {
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
