<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InkindDonationPhoto;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class InkindDonationPhotosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $photos = [
            '1.jpg',
            '2.jpg',
            '3.jpg',
            '4.jpg',
            '5.jpg',
            '6.jpg',
            '7.jpg',
            '8.jpg',
            '9.jpg',
            '10.jpg',
        ];

        $sourceDir = public_path('uploads/seeder_photos/');
        $targetDir = 'uploads/inkindDonations/';

        foreach ($photos as $photo) {
            $sourcePath = $sourceDir . $photo;
            $targetPath = $targetDir . $photo;

            if (File::exists($sourcePath)) {
                Storage::disk('public')->put($targetPath, File::get($sourcePath));

                $fullPath =  $targetPath;
                $fullPaths[] = $fullPath;
            } else {
                $fullPaths[] = null;
            }
        }


        $inkind_donation_ids = [
            1, // ملابس شتوية للأطفال     1
            2, // سلال غذائية متكامل    2
            3, // أسرّة خشبية مع فرش    3
            
            4, //حرامات شتوي'    4

            5, //ملابس مدرسية لأطفال الابتدائي   5
            6, // ديارة طفل حديث الولاد   6
            7, // بطاريات ليدات  7


            8, // 8 كتب تاسع وبكالوريا
            9, // أدوية ضغط وسكر  9
            10,  //   قرطاسية كاملة 10

            11, // ملابس شتوية للأطفال     1
            12, // سلال غذائية متكامل    2
            13, // أسرّة خشبية مع فرش    3

            14, //حرامات شتوي'    4
            15, //ملابس مدرسية لأطفال الابتدائي   5
            16, // ديارة طفل حديث الولاد   6

            17, // بطاريات ليدات  7
            18, // 8 كتب تاسع وبكالوريا
            19, // أدوية ضغط وسكر  9

            20,  //   قرطاسية كاملة 10
        ];

        for ($i = 0; $i < count($photos); $i++) {
            $photoIndex = $i % count($photos);

            InkindDonationPhoto::create([
                'photo'               => $fullPaths[$photoIndex],
                'inkind_donation_id'  => $inkind_donation_ids[$i],
            ]);
        }
    }
}
