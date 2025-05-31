<?php

namespace App\Console\Commands;

use App\Enums\OrderStatusEnum;
use App\Enums\PaymentStatusEnum;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpirePendingOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expire-pending-orders';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update pending orders that have expired';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredOrders = Order::where('payment_status', PaymentStatusEnum::Pending)
            ->where('order_date', '<', Carbon::now()->subHours(24))
            ->get();

        foreach ($expiredOrders as $order) {
            $order->update([
                'payment_status' => PaymentStatusEnum::Failed,
                'order_status' => OrderStatusEnum::Canceled,
            ]);

            $this->info("Order {$order->id} marked as failed.");
        }

        return 0;
    }
}
