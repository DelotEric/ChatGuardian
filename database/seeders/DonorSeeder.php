<?php

namespace Database\Seeders;

use App\Models\Donor;
use Illuminate\Database\Seeder;

class DonorSeeder extends Seeder
{
    public function run(): void
    {
        $donors = [
            ['name' => 'Association des Amis des Chats', 'email' => 'amischats@example.test', 'address' => '12 rue des Lilas'],
            ['name' => 'Julie Cohen', 'email' => 'julie@example.test', 'address' => '5 avenue des Pins'],
            ['name' => 'Marc Etienne', 'email' => 'marc@example.test', 'address' => '34 boulevard Lumière'],
        ];

        foreach ($donors as $donor) {
            Donor::firstOrCreate(
                ['email' => $donor['email']],
                $donor
            );
        }
    }
}
