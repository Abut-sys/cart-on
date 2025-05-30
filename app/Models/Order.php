<?php

namespace App\Models;

use App\Enums\OrderStatusEnum;
use App\Enums\PaymentStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['order_date', 'unique_order_id', 'address', 'courier', 'shipping_service', 'shipping_cost', 'amount', 'payment_status', 'order_status'];
    protected $casts = [
        'order_date' => 'datetime', // This will automatically cast to Carbon instance
        'order_status' => OrderStatusEnum::class,
        'payment_status' => PaymentStatusEnum::class,
    ];

    public function checkouts()
    {
        return $this->belongsToMany(Checkout::class, 'order_checkouts', 'order_id', 'checkout_id');
    }

    public function product()
    {
        // Jika Product menggunakan soft delete
        if (method_exists(Product::class, 'bootSoftDeletes')) {
            return $this->belongsTo(Product::class)
                ->withTrashed()
                ->withDefault([
                    'name' => 'Produk Telah Dihapus',
                    'price' => 0,
                    'description' => '',
                ]);
        }

        return $this->belongsTo(Product::class)->withDefault([
            'name' => 'Produk Tidak Tersedia',
            'price' => 0,
            'description' => '',
        ]);
    }

    public function getUserAttribute()
    {
        return $this->checkouts->first()->user ?? null;
    }
}
