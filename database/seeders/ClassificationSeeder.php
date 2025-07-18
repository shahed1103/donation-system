<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Classification;

class ClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                                $classifications = ['healthy' , 'Educational' , 'cleanliness' , 'environmental'];

        for ($i=0; $i < 4 ; $i++) {
            Classification::query()->create([
           'classification_name' => $classifications[$i] ,
            ]); }
    }

}
