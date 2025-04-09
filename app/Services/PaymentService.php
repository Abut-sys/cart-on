<?php

namespace App\Services;

use Midtrans\Snap;
use App\Models\Product;
use App\Models\SubVariant;
use App\Models\Voucher;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentService
{
    public function generateSnapToken(Product $product, SubVariant $variant, $quantity, $user, $orderId = null, $grossAmount = null)
    {
        $orderId = $orderId ?: 'ORDER-' . uniqid('', true) . '-' . Str::random(6);
        
        $grossAmount = $grossAmount ?: $product->price * $quantity;

        $transactionDetails = [
            'order_id' => $orderId,
            'gross_amount' => $grossAmount,
        ];

        $customerDetails = [
            'first_name' => $user->name,
            'email' => $user->email,
        ];

        $itemDetails = [
            [
                'id' => $product->id,
                'price' => $product->price,
                'quantity' => $quantity,
                'name' => $product->name,
            ],
        ];

        return Snap::getSnapToken([
            'transaction_details' => $transactionDetails,
            'customer_details' => $customerDetails,
            'item_details' => $itemDetails,
        ]);
    }

    public function generateSnapTokenFromCart($carts, $user, $voucher = null)
    {
        $orderId = 'ORDER-' . uniqid('', true) . '-' . Str::random(6);
        $totalPrice = 0;
        $items = [];

        foreach ($carts as $cart) {
            $items[] = [
                'id' => $cart->product->id,
                'price' => $cart->product->price,
                'quantity' => $cart->quantity,
                'name' => $cart->product->name,
            ];
            $totalPrice += $cart->product->price * $cart->quantity;
        }

        if ($voucher) {
            $totalPrice -= $voucher->discount_value;
            $totalPrice = max(0, $totalPrice);
        }

        $transactionDetails = [
            'order_id' => $orderId,
            'gross_amount' => $totalPrice,
        ];

        $customerDetails = [
            'first_name' => $user->name,
            'email' => $user->email,
        ];

        return Snap::getSnapToken([
            'transaction_details' => $transactionDetails,
            'customer_details' => $customerDetails,
            'item_details' => $items,
        ]);
    }
}
