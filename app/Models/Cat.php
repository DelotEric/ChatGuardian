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
        'photo_path',
        'adopter_id',
        'adopted_at',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'sterilized' => 'boolean',
        'sterilized_at' => 'date',
        'vaccinated' => 'boolean',
        'vaccinated_at' => 'date',
        'adopted_at' => 'date',
    ];

    public function adopter()
    {
        return $this->belongsTo(Adopter::class);
    }

    public function photos()
    {
        return $this->hasMany(CatPhoto::class);
    }

    public function medicalCares()
    {
        return $this->hasMany(MedicalCare::class);
    }

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

    public function weightRecords()
    {
        return $this->hasMany(WeightRecord::class)->orderBy('measured_at', 'desc');
    }

    public function getLatestWeightAttribute()
    {
        return $this->weightRecords()->first()?->weight;
    }

    public function weightHistory()
    {
        return $this->weightRecords()->orderBy('measured_at', 'asc')->get();
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'free' => 'Libre',
            'foster' => 'En famille d\'accueil',
            'adopted' => 'Adopté',
            'deceased' => 'Décédé',
            default => $this->status,
        };
    }

    public function getFivLabelAttribute()
    {
        return match ($this->fiv_status) {
            'positive' => 'Positif',
            'negative' => 'Négatif',
            'unknown' => 'Inconnu',
            default => 'Inconnu',
        };
    }

    public function getFelvLabelAttribute()
    {
        return match ($this->felv_status) {
            'positive' => 'Positif',
            'negative' => 'Négatif',
            'unknown' => 'Inconnu',
            default => 'Inconnu',
        };
    }
}
