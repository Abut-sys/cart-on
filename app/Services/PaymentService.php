<?php

namespace App\Services;

use Midtrans\Snap;
use App\Models\Product;
use App\Models\SubVariant;

class PaymentService
{
    public function generateSnapToken(Product $product, SubVariant $variant, $quantity, $user, $orderId = null, $grossAmount = null)
    {
        $orderId = $orderId ?: uniqid('ORDER-');
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
}
