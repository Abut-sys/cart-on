<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'checkout_id', 'order_date', 'unique_order_id', 'address', 'amount', 'payment_status', 'order_status'
    ];

    public function checkout()
    {
        return $this->belongsTo(Checkout::class);
    }
}
