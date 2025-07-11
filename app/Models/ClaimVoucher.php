<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimVoucher extends Model
{
    use HasFactory;

    protected $table = 'claim_voucher';

    protected $fillable = [
        'user_id',
        'voucher_id',
        'quantity',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function checkout()
    {
        return $this->hasMany(Checkout::class);
    }
}
