<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_number',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'postal_code',
        'birth_date',
        'join_date',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'join_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function hasValidMembership($year = null): bool
    {
        $year = $year ?? date('Y');
        return $this->memberships()->where('year', $year)->exists();
    }

    protected static function booted()
    {
        static::creating(function ($member) {
            if (empty($member->member_number)) {
                $latestMember = static::latest('id')->first();
                $nextNumber = $latestMember ? ((int) substr($latestMember->member_number, 3)) + 1 : 1;
                $member->member_number = 'ADH' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
            }
        });
    }
}
