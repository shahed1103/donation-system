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
            AssociationCampaignsSeeder::class,
        ]);
        $this->call([
            AssociationsSeeder::class,
        ]);
        $this->call([
            DonationAssociationCampaignsSeeder::class,
        ]);
        $this->call([
            SharedAssociationCampaignsSeeder::class,
        ]);

        $this->call([
            DonationsSeeder::class,
        ]);

        $this->call([
            CitySeeder::class]);

        $this->call([
            IndividualCompaignsPhotosSeeder::class]);

        $this->call([
            AvailabilityTypeSeeder::class]);

        $this->call([
            VolunteerProfileSeeder::class]);

        $this->call([
            TaskStatusSeeder::class]);

        $this->call([
            VolunteerTasksSeeder::class]);

        // $this->call([
        //     AssociationCampaignTaskSeeder::class]);

        $this->call([
            TaskVolunteerProfileSeeder::class]);

        $this->call([
            GenderSeeder::class]);

        $this->call([
            LeaderFormSeeder::class,
        ]);

////
        $this->call([
            CentersSeeder::class]);

        $this->call([
            DonationTypesSeeder::class]);

        $this->call([
            InkindDonationPhotosSeeder::class]);

        $this->call([
            StatusOfDonationsSeeder::class]);

        $this->call([
            InkindDonationAcceptenceStatusesSeeder::class]); 
                       
        $this->call([
            InkindDonationsSeeder::class]);

        $this->call([
            ReservationStatusesSeeder::class]);
        
        $this->call([
            InkindDonationReservationsSeeder::class]);
        }

}
