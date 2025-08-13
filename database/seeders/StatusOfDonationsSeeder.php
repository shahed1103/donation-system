<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StatusOfDonation;

class StatusOfDonationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            'جديد',
            'مستعمل - بحالة ممتازة',
            'مستعمل - بحالة جيدة',
            'مستعمل - بحاجة إلى صيانة بسيطة',
            'غير صالح للاستخدام',
        ];

        foreach ($statuses as $status) {
            StatusOfDonation::create([
                'status' => $status,
            ]);
        }
    }
}
