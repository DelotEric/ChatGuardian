<?php

namespace Database\Seeders;

use App\Models\StockItem;
use Illuminate\Database\Seeder;

class StockItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'name' => 'Croquettes adultes',
                'category' => 'Alimentation',
                'quantity' => 24,
                'unit' => 'sacs',
                'location' => 'Local associatif',
                'restock_threshold' => 10,
                'notes' => 'Royal Canin 10kg',
            ],
            [
                'name' => 'Litière agglomérante',
                'category' => 'Hygiène',
                'quantity' => 6,
                'unit' => 'sacs',
                'location' => 'Garage bénévole',
                'restock_threshold' => 8,
                'notes' => 'Prévoir réappro fin du mois',
            ],
            [
                'name' => 'Pipettes antiparasitaires',
                'category' => 'Santé',
                'quantity' => 14,
                'unit' => 'unités',
                'location' => 'Armoire véto',
                'restock_threshold' => 6,
                'notes' => 'Effipro 1-10kg',
            ],
            [
                'name' => 'Boîtes chatons',
                'category' => 'Alimentation',
                'quantity' => 5,
                'unit' => 'boîtes',
                'location' => 'Local associatif',
                'restock_threshold' => 12,
                'notes' => 'Format 200g',
            ],
        ];

        foreach ($items as $item) {
            StockItem::create($item);
        }
    }
}
