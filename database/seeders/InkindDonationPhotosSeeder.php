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
            1, // ملابس شتوية للأطفال
            2, // سلال غذائية
            3, // أسرّة خشبية
        ];

        for ($i = 0; $i < count($photos); $i++) {
            InkindDonationPhoto::create([
                'photo'               => $fullPaths[$i],
                'inkind_donation_id'  => $inkind_donation_ids[$i],
            ]);
        }
    }
}
