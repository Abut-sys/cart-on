<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Pengirim pesan
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    // Penerima pesan
    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    // Scope untuk mendapatkan pesan antara dua user
    public function scopeBetweenUsers($query, $user1Id, $user2Id)
    {
        return $query->where(function ($q) use ($user1Id, $user2Id) {
            $q->where('from_user_id', $user1Id)
              ->where('to_user_id', $user2Id);
        })->orWhere(function ($q) use ($user1Id, $user2Id) {
            $q->where('from_user_id', $user2Id)
              ->where('to_user_id', $user1Id);
        });
    }

    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }
}
