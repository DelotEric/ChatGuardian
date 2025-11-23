<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'event_date',
        'event_time',
        'location',
        'type',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'event_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function scopeUpcoming($query, $limit = 3)
    {
        return $query->active()
            ->where('event_date', '>=', now()->toDateString())
            ->orderBy('event_date')
            ->orderBy('event_time')
            ->limit($limit);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getFormattedDateAttribute(): string
    {
        return $this->event_date->format('d M');
    }

    public function getTypeLabelsAttribute(): array
    {
        return [
            'adoption_day' => 'Journée d\'adoption',
            'training' => 'Formation',
            'meeting' => 'Réunion',
            'other' => 'Autre',
        ];
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->type_labels[$this->type] ?? 'Autre';
    }
}
