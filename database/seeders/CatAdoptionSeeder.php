<?php

namespace Database\Seeders;

use App\Models\Cat;
use App\Models\CatAdoption;
use Illuminate\Database\Seeder;

class CatAdoptionSeeder extends Seeder
{
    public function run(): void
    {
        $cat = Cat::where('status', 'adopted')->first();

        if (! $cat) {
            return;
        }

        CatAdoption::firstOrCreate(
            ['cat_id' => $cat->id],
            [
                'adopter_name' => 'Pauline Martin',
                'adopter_email' => 'pauline.martin@example.test',
                'adopter_phone' => '06 45 11 22 33',
                'adopter_address' => '12 rue des Lilas, 31000 Toulouse',
                'adopted_at' => now()->subMonths(7),
                'fee' => 120,
                'notes' => "Adoption finalisée après période d'essai en famille d'accueil.",
            ]
        );
    }
}
