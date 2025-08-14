<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InkindDonation;

class InkindDonationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $donation_type_id = [
            1, // ملابس
            2, // مواد غذائية
            3, // أثاث
        ];

        $name_of_donation = [
            'ملابس شتوية للأطفال',
            'سلال غذائية متكاملة',
            'أسرّة خشبية مع فرش',
        ];

        $amount = [
            40,
            25,
            23,
        ];

        $description = [
            'معاطف، كنزات، بناطيل شتوية بحالة ممتازة',
            'أرز، سكر، زيت، معلبات، شاي',
            'أسرّة مفرد بحالة جيدة مع فرش نظيف',
        ];

        $status_of_donation_id = [
            2, // مستعمل - بحالة ممتازة
            1, // جديد
            3, // مستعمل - بحالة جيدة
        ];

        $center_id = [
            1, // مركز الهلال الأحمر
            2, // مركز الإغاثة الإنسانية
            1, // مركز الهلال الأحمر
        ];

        $owner_id = [
            5,
            6,
            7,
        ];

        $inkindDonation_acceptence_id = [1,2,2];

        for ($i = 0; $i < count($donation_type_id); $i++) {
            InkindDonation::create([
                'donation_type_id'      => $donation_type_id[$i],
                'name_of_donation'      => $name_of_donation[$i],
                'amount'                => $amount[$i],
                'description'           => $description[$i],
                'status_of_donation_id' => $status_of_donation_id[$i],
                'center_id'             => $center_id[$i],
                'owner_id'              => $owner_id[$i],
                'inkindDonation_acceptence_id' => $inkindDonation_acceptence_id[$i]
                
            ]);
        }
    }
}
