<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_date',
        'unique_order_id',
        'address',
        'courier',
        'shipping_service',
        'shipping_cost',
        'amount',
        'payment_status',
        'order_status',
        'resi_number',
        'estimated_delivery',
    ];

    public function checkouts()
    {
        return $this->belongsToMany(Checkout::class, 'order_checkouts', 'order_id', 'checkout_id');
    }
}
