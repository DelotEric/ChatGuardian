<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            VolunteerSeeder::class,
            FosterFamilySeeder::class,
            CatSeeder::class,
            DonorSeeder::class,
            DonationSeeder::class,
            FeedingPointSeeder::class,
        ]);
    }
}
