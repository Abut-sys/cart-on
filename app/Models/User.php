<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'phone_number', 'email_verified_at', 'password', 'image_url', 'role', 'google_id', 'last_online_at'];

    protected $hidden = ['password', 'remember_token'];

    protected $dates = ['last_online_at'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_online_at' => 'datetime',
    ];

    public function isEmailVerified()
    {
        return $this->email_verified_at !== null;
    }

    public function userVouchers()
    {
        return $this->hasMany(UserVoucher::class);
    }

    public function vouchers()
    {
        return $this->belongsToMany(Voucher::class, 'user_voucher');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function checkouts()
    {
        return $this->hasMany(Checkout::class);
    }

    public function setPhoneNumberAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['phone_number'] = null;
            return;
        }

        $cleaned = preg_replace('/[^0-9]/', '', $value);

        if (str_starts_with($cleaned, '0')) {
            $cleaned = '62' . substr($cleaned, 1);
        }

        if (!str_starts_with($cleaned, '62')) {
            $cleaned = '62' . $cleaned;
        }

        $digitCount = strlen($cleaned) - 2;
        if ($digitCount < 10 || $digitCount > 11) {
            $this->attributes['phone_number'] = null;
            return;
        }

        $this->attributes['phone_number'] = '+' . $cleaned;
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function reviewProducts()
    {
        return $this->hasMany(ReviewProduct::class);
    }

    public function chatsFrom()
    {
        return $this->hasMany(Chat::class, 'from_user_id');
    }

    /**
     * Chat yang diterima oleh user ini
     */
    public function chatsTo()
    {
        return $this->hasMany(Chat::class, 'to_user_id');
    }

    /**
     * Semua chat yang melibatkan user ini
     */
    public function allChats()
    {
        return Chat::where('from_user_id', $this->id)->orWhere('to_user_id', $this->id);
    }

    /**
     * Dapatkan pesan terakhir dengan user tertentu
     */
    public function lastMessageWith($userId)
    {
        return Chat::where(function ($q) use ($userId) {
            $q->where('from_user_id', $this->id)->where('to_user_id', $userId);
        })
            ->orWhere(function ($q) use ($userId) {
                $q->where('from_user_id', $userId)->where('to_user_id', $this->id);
            })
            ->latest()
            ->first();
    }
}
