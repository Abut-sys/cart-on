<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'product_id', 'address_id', 'voucher_code', 'quantity', 'shipping_method', 'amount'];

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
        return $this->hasMany(Order::class);
    }
}
