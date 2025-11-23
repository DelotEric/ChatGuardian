<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'contact_person',
        'email',
        'phone',
        'address',
        'city',
        'website',
        'services',
        'discount_rate',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'discount_rate' => 'decimal:2',
    ];
}
