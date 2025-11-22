<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::truncate();

        User::create([
            'name' => 'Admin Demo',
            'email' => 'admin@chatguardian.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Bénévole Démo',
            'email' => 'benevole@chatguardian.test',
            'password' => Hash::make('password'),
            'role' => 'benevole',
        ]);

        User::create([
            'name' => 'Famille Accueil Démo',
            'email' => 'famille@chatguardian.test',
            'password' => Hash::make('password'),
            'role' => 'famille',
        ]);
    }
}
