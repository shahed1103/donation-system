<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InkindDonationReservation;

class InkindDonationReservationsSeeder extends Seeder
{
    public function run(): void
    {
        $user_id = [
            5,
            7,
            8,
            9,
            10,
            6,
            6,
            8,
            10,
            9,
            5,
            7,
            8,
            9,
            10,
            6,
            6,
            8,
            10,
            9
        ];

        $inkind_donation_id = [
            2,
            3,
            2,
            1,
            4,
            5,
            6,
            7,
            7,
            7,
            8,
            10,
            2,
            10,
            20,
            18,
            17,
            15,
            4,
            12,
        ];

        $status_id = [
            1, // قيد الانتظار
            2, // تم الاستلام
            1,  // قيد الانتظار
            1, // قيد الانتظار
            2, // تم الاستلام
            1,  // قيد الانتظار
            1, // قيد الانتظار
            2, // تم الاستلام
            1,  // قيد الانتظار
            1, // قيد الانتظار
            2, // تم الاستلام
            1,  // قيد الانتظار
            1, // قيد الانتظار
            2, // تم الاستلام
            1,  // قيد الانتظار
            1, // قيد الانتظار
            2, // تم الاستلام
            1,  // قيد الانتظار
            1, // قيد الانتظار
            2, // تم الاستلام

        ];

        $amount = [
            1,
            2,
            3,
            1,
            2,
            3,
            1,
            2,
            3,
            1,
            2,
            3,
            1,
            2,
            3,
            1,
            2,
            3,
            1,
            2,
        ];

        for ($i = 0; $i < count($user_id); $i++) {
            InkindDonationReservation::create([
                'user_id'            => $user_id[$i],
                'inkind_donation_id' => $inkind_donation_id[$i],
                'status_id'          => $status_id[$i],
                'amount'             => $amount[$i]
            ]);
        }
    }
}
