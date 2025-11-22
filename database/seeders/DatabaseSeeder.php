<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            OrganizationSeeder::class,
            UserSeeder::class,
            VolunteerSeeder::class,
            FosterFamilySeeder::class,
            CatSeeder::class,
            CatAdoptionSeeder::class,
            CatPhotoSeeder::class,
            CatVetRecordSeeder::class,
            DonorSeeder::class,
            DonationSeeder::class,
            FeedingPointSeeder::class,
            StockItemSeeder::class,
            ActivityLogSeeder::class,
        ]);
    }
}
