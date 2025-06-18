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
}
