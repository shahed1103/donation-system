<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Association;

class AssociationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                $name = ['alrahma' , 'ghaith' , 'tarahum'];
                $description = ['alrahmades' , 'ghaithdes' , 'tarahumdes'];
                $location = ['alrahmaloc' , 'ghaithloc' , 'tarahumloc'];

        for ($i=0; $i < 3 ; $i++) {
            Association::query()->create([
           'name' => $name[$i] ,
             'description' => $description[$i] ,
               'location' => $location[$i] ,
            ]); }
    }
}
