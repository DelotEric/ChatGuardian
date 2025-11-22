<?php

namespace Database\Seeders;

use App\Models\Volunteer;
use Illuminate\Database\Seeder;

class VolunteerSeeder extends Seeder
{
    public function run(): void
    {
        $volunteers = [
            ['name' => 'Anaïs Dupont', 'email' => 'anais@example.test', 'phone' => '0600000001'],
            ['name' => 'Karim Lefèvre', 'email' => 'karim@example.test', 'phone' => '0600000002'],
            ['name' => 'Lucie Bernard', 'email' => 'lucie@example.test', 'phone' => '0600000003'],
            ['name' => 'Paul Martin', 'email' => 'paul@example.test', 'phone' => '0600000004'],
        ];

        foreach ($volunteers as $volunteer) {
            Volunteer::firstOrCreate(
                ['email' => $volunteer['email']],
                $volunteer
            );
        }
    }
}
