<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'legal_name',
        'siret',
        'email',
        'phone',
        'address',
        'postal_code',
        'city',
        'country',
        'iban',
        'bic',
        'website',
        'api_token',
    ];

    public static function defaults(): array
    {
        return [
            'name' => 'ChatGuardian',
            'legal_name' => 'ChatGuardian Association',
            'email' => 'contact@chatguardian.test',
            'phone' => '+33 6 12 34 56 78',
            'address' => '123 Rue des Chats Libres',
            'postal_code' => '75000',
            'city' => 'Paris',
            'country' => 'France',
            'siret' => '000 000 000 00000',
            'iban' => 'FR76 3000 4000 5000 6000 7000 890',
            'bic' => 'BNPAFRPP',
            'website' => 'https://chatguardian.test',
            'api_token' => 'demo-api-key',
        ];
    }
}
