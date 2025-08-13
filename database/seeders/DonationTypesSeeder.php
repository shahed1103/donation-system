<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DonationType;

class DonationTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'ملابس',
            'مواد غذائية',
            'أثاث منزلي',
            'أدوات كهربائية',
            'كتب وأدوات تعليمية',
            'معدات طبية',
        ];

        for ($i = 0; $i < count($types); $i++) {
            DonationType::create([
                'donation_Type' => $types[$i],
            ]);
        }
    }
}
