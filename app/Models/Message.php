<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'subject',
        'body',
        'is_read',
        'read_at',
        'sender_deleted',
        'recipient_deleted',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'sender_deleted' => 'boolean',
        'recipient_deleted' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    public function scopeInbox($query, $userId)
    {
        return $query->where('recipient_id', $userId)
            ->where('recipient_deleted', false)
            ->with('sender')
            ->latest();
    }

    public function scopeSent($query, $userId)
    {
        return $query->where('sender_id', $userId)
            ->where('sender_deleted', false)
            ->with('recipient')
            ->latest();
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}
