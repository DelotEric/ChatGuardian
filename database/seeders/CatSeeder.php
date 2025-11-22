<?php

namespace Database\Seeders;

use App\Models\Cat;
use App\Models\CatStay;
use App\Models\FosterFamily;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CatSeeder extends Seeder
{
    public function run(): void
    {
        $families = FosterFamily::pluck('id', 'email');

        $cats = [
            [
                'name' => 'Moka',
                'sex' => 'female',
                'status' => 'fostered',
                'vaccinated' => true,
                'vaccinated_at' => now()->subMonths(2),
                'notes' => 'Très sociable, aime les enfants.',
                'stay' => ['family_email' => 'roux@example.test', 'started_at' => Carbon::now()->subWeeks(3)],
            ],
            [
                'name' => 'Pixel',
                'sex' => 'male',
                'status' => 'free',
                'notes' => 'Chat libre nourri sur site, suit les bénévoles.',
            ],
            [
                'name' => 'Comète',
                'sex' => 'female',
                'status' => 'adopted',
                'sterilized' => true,
                'sterilized_at' => now()->subMonths(6),
                'notes' => 'Adoptée début d’année.',
                'stay' => [
                    'family_email' => 'garcia@example.test',
                    'started_at' => Carbon::now()->subMonths(8),
                    'ended_at' => Carbon::now()->subMonths(7),
                    'outcome' => 'Adoption réussie',
                ],
            ],
            [
                'name' => 'Oreo',
                'sex' => 'male',
                'status' => 'fostered',
                'notes' => 'Craintif, progrès constants.',
                'stay' => ['family_email' => 'moreau@example.test', 'started_at' => Carbon::now()->subWeeks(1)],
            ],
            [
                'name' => 'Luna',
                'sex' => 'female',
                'status' => 'deceased',
                'notes' => 'Décédée suite à maladie (archive).',
            ],
        ];

        foreach ($cats as $catData) {
            $stay = $catData['stay'] ?? null;
            unset($catData['stay']);

            $cat = Cat::create($catData);

            if ($stay && $families->has($stay['family_email'])) {
                CatStay::create([
                    'cat_id' => $cat->id,
                    'foster_family_id' => $families[$stay['family_email']],
                    'started_at' => $stay['started_at'],
                    'ended_at' => $stay['ended_at'] ?? null,
                    'outcome' => $stay['outcome'] ?? null,
                ]);
            }
        }
    }
}
