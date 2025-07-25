<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AvailabilityType;


class AvailabilityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            'Daily',
            'Weakly',
        ];

        for ($i = 0; $i < 2; $i++) {
            AvailabilityType::create([
                'name' => $names[$i],
            ]);
        }
    }
}
