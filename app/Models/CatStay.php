<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatStay extends Model
{
    use HasFactory;

    protected $fillable = [
        'cat_id',
        'foster_family_id',
        'started_at',
        'ended_at',
        'outcome',
        'notes',
    ];

    protected $casts = [
        'started_at' => 'date',
        'ended_at' => 'date',
    ];

    public function cat()
    {
        return $this->belongsTo(Cat::class);
    }

    public function fosterFamily()
    {
        return $this->belongsTo(FosterFamily::class);
    }
}
