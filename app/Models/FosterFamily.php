<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FosterFamily extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'postal_code',
        'city',
        'capacity',
        'preferences',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function stays()
    {
        return $this->hasMany(CatStay::class);
    }

    public function cats()
    {
        return $this->belongsToMany(Cat::class, 'cat_stays')->withPivot(['started_at', 'ended_at', 'outcome']);
    }
}
