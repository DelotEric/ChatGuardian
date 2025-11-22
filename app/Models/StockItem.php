<?php

namespace App\\Models;

use Illuminate\\Database\\Eloquent\\Factories\\HasFactory;
use Illuminate\\Database\\Eloquent\\Model;

class StockItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'quantity',
        'unit',
        'location',
        'restock_threshold',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'restock_threshold' => 'integer',
    ];

    protected $appends = ['is_low'];

    public function isLow(): bool
    {
        return $this->quantity <= $this->restock_threshold;
    }

    public function getIsLowAttribute(): bool
    {
        return $this->isLow();
    }
}
