<?php

namespace Database\Seeders;

use App\Models\FosterFamily;
use Illuminate\Database\Seeder;

class FosterFamilySeeder extends Seeder
{
    public function run(): void
    {
        $families = [
            ['name' => 'Famille Roux', 'email' => 'roux@example.test', 'city' => 'Lyon', 'capacity' => 2, 'preferences' => 'Chatons sociables'],
            ['name' => 'Famille Garcia', 'email' => 'garcia@example.test', 'city' => 'Paris', 'capacity' => 1, 'preferences' => 'Adultes calmes'],
            ['name' => 'Famille Moreau', 'email' => 'moreau@example.test', 'city' => 'Bordeaux', 'capacity' => 3, 'preferences' => 'Chats craintifs'],
            ['name' => 'Famille Petit', 'email' => 'petit@example.test', 'city' => 'Nantes', 'capacity' => 2, 'preferences' => 'Sans préférence'],
        ];

        foreach ($families as $family) {
            FosterFamily::firstOrCreate(
                ['email' => $family['email']],
                $family
            );
        }
    }
}
