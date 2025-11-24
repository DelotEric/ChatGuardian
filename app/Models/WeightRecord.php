<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeightRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'cat_id',
        'weight',
        'measured_at',
        'measured_by',
        'notes',
    ];

    protected $casts = [
        'measured_at' => 'date',
        'weight' => 'decimal:2',
    ];

    public function cat()
    {
        return $this->belongsTo(Cat::class);
    }
}
