<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AcceptanceStatus;

class AcceptanceStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                $status = ['Under review' , 'Approved' , 'Rejected'];

        for ($i=0; $i < 3 ; $i++) {
            AcceptanceStatus::query()->create([
           'status_type' => $status[$i] ,
            ]); }
    }
}
