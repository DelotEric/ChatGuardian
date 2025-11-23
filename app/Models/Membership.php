<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'year',
        'amount',
        'payment_date',
        'payment_method',
        'receipt_number',
        'receipt_issued',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'receipt_issued' => 'boolean',
        'amount' => 'decimal:2',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
