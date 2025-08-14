<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\InkindDonationAcceptence;

class InkindDonationAcceptenceStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $status = ['قيد الانتظار' , 'مقبول' ];

        for ($i=0; $i < 2 ; $i++) {
            InkindDonationAcceptence::query()->create([
           'status' => $status[$i] ,
            ]); }
    }
}
