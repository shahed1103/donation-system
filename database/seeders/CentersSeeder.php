<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Center;

class CentersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            'مركز الهلال الأحمر',
            'مركز الإغاثة الإنسانية',
            'مركز التبرعات العامة',
        ];

        $locations = [
            'دمشق - المزة',
            'حلب - الشهباء',
            'حمص - شارع الدبلان',
        ];

        $space = [
            '200',
            '222',
            '800'
         ];
        $have_frez = [
            '1',
            '1',
            '0'
         ];

        for ($i = 0; $i < count($names); $i++) {
            Center::create([
                'center_name' => $names[$i],
                'location'    => $locations[$i],
                'space' => $space[$i],
                'have_frez' => $have_frez[$i]
            ]);
        }
    }
}

