<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InkindDonationReservation;

class InkindDonationReservationsSeeder extends Seeder
{
    public function run(): void
    {
        $user_id = [
            2,
            3,
            1
        ];

        $inkind_donation_id = [
            1,
            1,
            2
        ];

        $status_id = [
            1, // قيد الانتظار
            2, // تمت الموافقة
            1  // قيد الانتظار
        ];

        for ($i = 0; $i < count($user_id); $i++) {
            InkindDonationReservation::create([
                'user_id'            => $user_id[$i],
                'inkind_donation_id' => $inkind_donation_id[$i],
                'status_id'          => $status_id[$i],
            ]);
        }
    }
}
