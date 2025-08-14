<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReservationStatus;

class ReservationStatusesSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'قيد الانتظار',  // pending
            'تم الاستلام'  ,   // received
            'ملغي',           // cancelled
        ];

        for ($i = 0; $i < count($names); $i++) {
            ReservationStatus::create([
                'name' => $names[$i]
            ]);
        }
    }
}
