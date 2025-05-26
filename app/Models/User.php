<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'phone_number', 'email_verified_at', 'password', 'image_url', 'role', 'google_id'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
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

        // Konversi 0 di awal ke 62
        if (str_starts_with($cleaned, '0')) {
            $cleaned = '62' . substr($cleaned, 1);
        }

        // Pastikan diawali 62
        if (!str_starts_with($cleaned, '62')) {
            $cleaned = '62' . $cleaned;
        }

        // Validasi panjang digit setelah 62 (10-11 digit)
        $digitCount = strlen($cleaned) - 2;
        if ($digitCount < 10 || $digitCount > 11) {
            throw new \InvalidArgumentException('Nomor telepon harus +62 diikuti 10 atau 11 digit');
        }

        $this->attributes['phone_number'] = '+' . $cleaned;
    }
}
