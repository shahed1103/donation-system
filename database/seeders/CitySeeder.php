<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = ['الرياض', 'جدة', 'الدمام', 'مكة', 'المدينة', 'الخبر'];

        foreach ($cities as $name) {
            City::firstOrCreate(['name' => $name]);
        }
    }
}
