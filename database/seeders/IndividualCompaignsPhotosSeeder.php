<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\IndCompaigns_photo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class IndividualCompaignsPhotosSeeder extends Seeder
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
            '5.png',
            '6.png',
            '7.png',
            '8.png',
            'h1.jpg',
            'h2.jpg',
            'h3.jpg',
            'h4.jpg',
            't1.jpg',
            't2.jpg',
            't3.jpg',
            't4.jpg',
        ];

        $fullPaths = [];

        $sourceDir = public_path('uploads/seeder_photos/');
        $targetDir = 'uploads/indCampignsphotos/';

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

        for ($i = 0 ; $i < 16 ; $i++) {
            if ($fullPaths[$i] !== null) {
                IndCompaigns_photo::query()->create([
                    'photo' => $fullPaths[$i],
                ]);
            }
        }
    }
}
