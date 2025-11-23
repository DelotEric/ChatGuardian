<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_item_id',
        'type',
        'quantity',
        'date',
        'reason',
        'reference',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'date' => 'date',
        'quantity' => 'decimal:2',
    ];

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::created(function ($movement) {
            $item = $movement->inventoryItem;
            if ($movement->type === 'in') {
                $item->increment('quantity', $movement->quantity);
            } else {
                $item->decrement('quantity', $movement->quantity);
            }
        });
    }
}
