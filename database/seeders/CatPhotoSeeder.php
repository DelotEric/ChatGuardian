<?php

namespace Database\Seeders;

use App\Models\Cat;
use App\Models\CatPhoto;
use Illuminate\Database\Seeder;

class CatPhotoSeeder extends Seeder
{
    public function run(): void
    {
        $cats = Cat::query()->take(3)->get();

        foreach ($cats as $index => $cat) {
            CatPhoto::create([
                'cat_id' => $cat->id,
                'path' => 'images/cat-placeholder.svg',
                'caption' => match ($index) {
                    0 => 'Portrait de présentation',
                    1 => 'Arrivée sur site',
                    default => 'Photo de suivi',
                },
            ]);
        }
    }
}
