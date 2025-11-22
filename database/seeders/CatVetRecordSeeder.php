<?php

namespace Database\Seeders;

use App\Models\Cat;
use App\Models\CatVetRecord;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CatVetRecordSeeder extends Seeder
{
    public function run(): void
    {
        $cats = Cat::all();

        if ($cats->isEmpty()) {
            return;
        }

        foreach ($cats as $cat) {
            CatVetRecord::create([
                'cat_id' => $cat->id,
                'visit_date' => Carbon::now()->subWeeks(rand(1, 8)),
                'clinic_name' => 'Clinique du centre',
                'reason' => 'Contrôle + vermifuge',
                'amount' => 45,
                'notes' => 'Rappel prévu dans 6 mois.',
            ]);

            if ($cat->status === 'foster' || $cat->status === 'fostered') {
                CatVetRecord::create([
                    'cat_id' => $cat->id,
                    'visit_date' => Carbon::now()->subWeeks(rand(2, 12)),
                    'clinic_name' => 'Cabinet vétérinaire des Lilas',
                    'reason' => 'Vaccin TCL',
                    'amount' => 58.5,
                    'notes' => 'Carnet tamponné, ok.',
                ]);
            }
        }
    }
}
