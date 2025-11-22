<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id',
        'amount',
        'donated_at',
        'payment_method',
        'receipt_number',
        'is_receipt_sent',
    ];

    protected $casts = [
        'donated_at' => 'date',
        'is_receipt_sent' => 'boolean',
    ];

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }
}
