<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = ['profile_id', 'address_line1', 'address_line2', 'city', 'state', 'city_id', 'postal_code', 'country', 'latitude', 'longitude'];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function checkouts()
    {
        return $this->hasMany(Checkout::class);
    }
}
