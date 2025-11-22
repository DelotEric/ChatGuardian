<?php

namespace Database\Seeders;

use App\Models\FeedingPoint;
use Illuminate\Database\Seeder;

class FeedingPointSeeder extends Seeder
{
    public function run(): void
    {
        $points = [
            ['name' => 'Parc de la Tête d’Or', 'latitude' => 45.784, 'longitude' => 4.852, 'description' => 'Site de nourrissage surveillé deux fois par semaine.'],
            ['name' => 'Quais Saône', 'latitude' => 45.763, 'longitude' => 4.835, 'description' => 'Groupe de chats libres, présence accrue en fin de journée.'],
            ['name' => 'Zone industrielle Est', 'latitude' => 45.751, 'longitude' => 4.920, 'description' => 'Nourrissage sécurisé, nécessité de pièges pour stérilisation.'],
        ];

        foreach ($points as $point) {
            FeedingPoint::firstOrCreate(
                ['name' => $point['name']],
                $point
            );
        }
    }
}
