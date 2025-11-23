<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'publish_date',
        'is_published',
        'author_id',
    ];

    protected $casts = [
        'publish_date' => 'date',
        'is_published' => 'boolean',
    ];

    public function author()
    {
        return $this->belongsTo(\App\Models\User::class, 'author_id');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->where('publish_date', '<=', now());
    }

    public function scopeRecent($query, $limit = 4)
    {
        return $query->published()->latest('publish_date')->limit($limit);
    }

    public function getShortContentAttribute(): string
    {
        return \Str::limit($this->content, 100);
    }
}
