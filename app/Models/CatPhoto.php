<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cat;

class CatPhoto extends Model
{
    use HasFactory;

    protected $fillable = ['cat_id', 'path'];

    public function cat()
    {
        return $this->belongsTo(Cat::class);
    }
}
