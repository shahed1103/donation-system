<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;

class CitySeeder extends Seeder
{
    public function run(): void
    {
                $cities = ['حمص',   //1
                'دير الزور',        //2
                'الحسكة',           //3
                'حماة',             //4
                'حلب',              //5
                'دمشق',             //6
                'اللائقية',          //7
                'طرطوس',            //8
                'إدلب',             //9
                'درعا'              //10
                ];

        foreach ($cities as $name) {
            City::firstOrCreate(['name' => $name]);
        }
    }
}
