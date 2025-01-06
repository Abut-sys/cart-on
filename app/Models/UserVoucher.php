<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVoucher extends Model
{
    use HasFactory;

    protected $table = 'user_voucher';

    protected $fillable = ['claim_voucher_id', 'checkout_id'];

    public function claimVoucher()
    {
        return $this->belongsTo(ClaimVoucher::class);
    }

    public function checkout()
    {
        return $this->belongsTo(Checkout::class);
    }
}
