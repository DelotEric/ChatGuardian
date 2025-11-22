<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatVetRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'cat_id',
        'visit_date',
        'clinic_name',
        'reason',
        'amount',
        'document_path',
        'notes',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function cat()
    {
        return $this->belongsTo(Cat::class);
    }
}
