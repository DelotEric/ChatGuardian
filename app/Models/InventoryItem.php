<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'description',
        'unit',
        'quantity',
        'min_quantity',
        'unit_price',
        'storage_location',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'min_quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
    ];

    public function movements()
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function isLowStock(): bool
    {
        return $this->quantity <= $this->min_quantity;
    }
}
