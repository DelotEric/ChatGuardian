<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatAdoption extends Model
{
    use HasFactory;

    protected $fillable = [
        'cat_id',
        'adopter_name',
        'adopter_email',
        'adopter_phone',
        'adopter_address',
        'adopted_at',
        'fee',
        'notes',
    ];

    protected $casts = [
        'adopted_at' => 'date',
        'fee' => 'decimal:2',
    ];

    public function cat()
    {
        return $this->belongsTo(Cat::class);
    }
}
