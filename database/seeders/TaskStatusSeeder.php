<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TaskStatus;

class TaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            'قادمة',
            'منجزة',
            'تم الاعتذار' , 
            'قيد الانتظار'
        ];

        for ($i = 0; $i < 3; $i++) {
            TaskStatus::create([
                'name' => $names[$i],
            ]);
        }
    }
}
