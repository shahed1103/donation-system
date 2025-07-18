<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            RolesPermissionsSeeder::class,
            ]);

        $this->call([
            AcceptanceStatusSeeder::class,
        ]);

        $this->call([
            CampaignStatusSeeder::class,
        ]);

        $this->call([
            ClassificationSeeder::class,
        ]);
        $this->call([
            IndividualCompaignsSeeder::class,
        ]);

        $this->call([
            DonationsSeeder::class,
        ]);

          $this->call([
            AssociationSeeder::class,
        ]);


    }
}
