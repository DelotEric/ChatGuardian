<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdoptionApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'cat_id',
        'status',
        'applicant_name',
        'applicant_email',
        'applicant_phone',
        'applicant_address',
        'housing_type',
        'has_garden',
        'family_composition',
        'other_pets',
        'motivation',
    ];

    protected $casts = [
        'has_garden' => 'boolean',
    ];

    public function cat()
    {
        return $this->belongsTo(Cat::class);
    }
}
