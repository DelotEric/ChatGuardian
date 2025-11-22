<?php

namespace Database\Seeders;

use App\Models\Cat;
use App\Models\CatReminder;
use Illuminate\Database\Seeder;

class CatReminderSeeder extends Seeder
{
    public function run(): void
    {
        $moka = Cat::where('name', 'Moka')->first();
        $pixel = Cat::where('name', 'Pixel')->first();
        $comete = Cat::where('name', 'Comète')->first();

        $reminders = [
            [
                'cat' => $moka,
                'title' => 'Rappel vaccin TCL',
                'type' => 'vaccine',
                'due_date' => now()->addWeeks(3),
                'notes' => 'Programmer avec la clinique CityVet',
            ],
            [
                'cat' => $moka,
                'title' => 'Contrôle post-ségrégation',
                'type' => 'health',
                'due_date' => now()->addDays(10),
                'notes' => 'Valider cicatrisation et comportement en FA',
            ],
            [
                'cat' => $pixel,
                'title' => 'Vérifier site de nourrissage',
                'type' => 'admin',
                'due_date' => now()->addWeek(),
                'notes' => 'Rencontrer les riverains, poser affiche',
            ],
            [
                'cat' => $comete,
                'title' => 'Suivi adoption 6 mois',
                'type' => 'adoption_followup',
                'due_date' => now()->subDays(3),
                'status' => 'pending',
                'notes' => 'Appeler l’adoptant pour nouvelles et photos',
            ],
        ];

        foreach ($reminders as $data) {
            if (!$data['cat']) {
                continue;
            }

            $status = $data['status'] ?? 'pending';
            unset($data['status']);
            $cat = $data['cat'];
            unset($data['cat']);

            CatReminder::create(array_merge($data, [
                'cat_id' => $cat->id,
                'status' => $status,
            ]));
        }
    }
}
