<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cat;
use App\Models\Partner;

class MedicalCare extends Model
{
    use HasFactory;

    protected $fillable = [
        'cat_id',
        'type',
        'title',
        'description',
        'care_date',
        'next_due_date',
        'status',
        'partner_id',
        'cost',
        'notes',
        'responsible_type',
        'responsible_id',
    ];

    protected $casts = [
        'care_date' => 'date',
        'next_due_date' => 'date',
        'cost' => 'decimal:2',
    ];

    public function cat()
    {
        return $this->belongsTo(Cat::class);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function responsible()
    {
        return $this->morphTo();
    }
}
