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
            'health (1).jpg',
            'health (2).jpg',
            'health (3).jpg',
            'health (4).jpg',
            'educ1.jpg',
            'educ2.jpg',
            'educ3.jpg',
            'educ4.jpg',
            'clean (1).jpg',
            'clean (2).jpg',
            'clean (3).jpg',
            'clean (4).jpg',
            'envo (1).jpg',
            'envo (2).jpg',
            'envo (3).jpg',
            'envo (4).jpg',
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
