<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cat extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sex',
        'birthdate',
        'status',
        'sterilized',
        'sterilized_at',
        'vaccinated',
        'vaccinated_at',
        'fiv_status',
        'felv_status',
        'notes',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'sterilized' => 'boolean',
        'sterilized_at' => 'date',
        'vaccinated' => 'boolean',
        'vaccinated_at' => 'date',
    ];

    public function stays()
    {
        return $this->hasMany(CatStay::class);
    }

    public function currentStay()
    {
        return $this->hasOne(CatStay::class)->whereNull('ended_at');
    }

    public function fosterFamilies()
    {
        return $this->belongsToMany(FosterFamily::class, 'cat_stays')->withPivot(['started_at', 'ended_at', 'outcome']);
    }
}
