<?php

namespace App\Services;

use Midtrans\Snap;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Transaction;

class PaymentService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.sanitization');
        Config::$is3ds = config('midtrans.validation');
    }

    public function generateSnapTokenForOrder(string $orderId, int $finalPrice, User $user, array $midtransItems): string
    {
        $transactionDetails = [
            'order_id' => $orderId,
            'gross_amount' => (int) $finalPrice,
        ];

        $billingAddress = $user->profile->addresses->first();
        $customerDetails = [
            'first_name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone_number ?? '',
            'billing_address' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone_number ?? '',
                'address' => $billingAddress->address_line1 ?? '',
                'city' => $billingAddress->city ?? '',
                'postal_code' => $billingAddress->postal_code ?? '',
                'country_code' => 'IDN',
            ],
        ];

        $params = [
            'transaction_details' => $transactionDetails,
            'customer_details' => $customerDetails,
            'item_details' => $midtransItems,
            'expiry' => [
                'start_time' => now()->format('Y-m-d H:i:s O'),
                'unit' => 'hours',
                'duration' => 24,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            Log::info("midtrans items: " . json_encode($midtransItems));
            return $snapToken;
        } catch (Exception $e) {
            Log::error("Gagal membuat Snap Token Midtrans untuk Order ID {$orderId}: " . $e->getMessage());
            throw new Exception("Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi nanti.");
        }
    }

    public function getTransactionStatus(string $orderId)
    {
        try {
            return Transaction::status($orderId);
        } catch (Exception $e) {
            Log::error("Error fetching Midtrans transaction status for order {$orderId}: " . $e->getMessage());
            return null;
        }
    }

    public function cancelTransaction(string $orderId)
    {
        try {
            return Transaction::cancel($orderId);
        } catch (Exception $e) {
            Log::error("Error cancelling Midtrans transaction for order {$orderId}: " . $e->getMessage());
            return null;
        }
    }
}
