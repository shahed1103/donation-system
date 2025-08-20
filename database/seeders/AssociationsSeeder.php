<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Association;

class AssociationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            'Goodwill Association',
            'Education for All',
            'Health First Foundation',
        ];

        $locations = [
            'Riyadh',
            'Jeddah',
            'Dammam',
        ];

        $descriptions = [
            'An organization dedicated to supporting underprivileged families and promoting social solidarity.',
            'Provides free educational opportunities for students from low-income backgrounds.',
            'Delivers essential healthcare services to the most vulnerable communities.',
        ];

         $association_owner = [
                    2,
                    2,
                    2        ];

         $date_start_working = ['2020-01-01' , ' 2020-01-01' , '2020-01-01'];
         $date_end_working = ['2020-01-01' , ' 2020-01-01' , '2020-01-01'];


        for ($i = 0; $i < 3; $i++) {
            Association::create([
                'name' => $names[$i],
                'location' => $locations[$i],
                'description' => $descriptions[$i],
                'association_owner_id' => $association_owner[$i],
                'date_start_working' => $date_start_working[$i],
                'date_end_working' => $date_end_working [$i]
            ]);
        }
    }
}
