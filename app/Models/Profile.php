<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'profile_picture',
        'address',
        'gender',
        'date_of_birth',
    ];

    /**
     * Relationship with User model
     */
     // Relasi ke User
     public function user()
     {
         return $this->belongsTo(User::class);
     }

     // Relasi ke Address (One to Many)
     public function addresses()
     {
         return $this->hasMany(Address::class);
     }
}
