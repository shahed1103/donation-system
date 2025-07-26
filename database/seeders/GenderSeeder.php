<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Gender;


class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = ['أنثى' , 'ذكر'];

        for($i = 0 ; $i<2 ; $i++){
            Gender::create([
            'type' => $types[$i]
            ]);
            }
    }
}
