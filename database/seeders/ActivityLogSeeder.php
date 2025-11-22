<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Cat;
use App\Models\CatAdoption;
use App\Models\CatVetRecord;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->where('role', 'admin')->first();
        $cat = Cat::query()->first();
        $donation = Donation::query()->first();
        $adoption = CatAdoption::with('cat')->first();
        $vet = CatVetRecord::with('cat')->first();

        if ($cat) {
            ActivityLog::create([
                'user_id' => $admin?->id,
                'subject_type' => Cat::class,
                'subject_id' => $cat->id,
                'action' => 'cat.created',
                'description' => 'Chat ajouté au registre (démo).',
            ]);
        }

        if ($donation) {
            ActivityLog::create([
                'user_id' => $admin?->id,
                'subject_type' => Donation::class,
                'subject_id' => $donation->id,
                'action' => 'donation.created',
                'description' => 'Don saisi pour ' . $donation->donor->full_name,
            ]);
        }

        if ($adoption && $adoption->cat) {
            ActivityLog::create([
                'user_id' => $admin?->id,
                'subject_type' => Cat::class,
                'subject_id' => $adoption->cat->id,
                'action' => 'adoption.created',
                'description' => 'Adoption enregistrée pour ' . $adoption->adopter_name,
            ]);
        }

        if ($vet && $vet->cat) {
            ActivityLog::create([
                'user_id' => $admin?->id,
                'subject_type' => Cat::class,
                'subject_id' => $vet->cat->id,
                'action' => 'vet.created',
                'description' => 'Visite vétérinaire saisie (démo).',
            ]);
        }
    }
}
