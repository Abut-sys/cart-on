<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'product_id', 'address_id', 'voucher_code', 'quantity', 'courier', 'shipping_service', 'shipping_cost', 'amount'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_code', 'code');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_checkouts')->withPivot('checkout_id', 'order_id')->withTimestamps();
    }

    public function claimVoucher()
    {
        return $this->belongsTo(ClaimVoucher::class);
    }
}
