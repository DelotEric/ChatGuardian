<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'city',
        'availability',
        'skills',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function feedingPoints()
    {
        return $this->belongsToMany(FeedingPoint::class)->withTimestamps();
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
}
