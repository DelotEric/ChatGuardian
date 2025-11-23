<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedingPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'description',
    ];

    public function volunteers()
    {
        return $this->belongsToMany(Volunteer::class)->withTimestamps();
    }
}
