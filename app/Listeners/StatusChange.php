<?php

namespace App\Listeners;

use App\Events\VoucherStatusChanged;
use App\Models\User;
use App\Notifications\VoucherStatusChangedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class StatusChange implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(VoucherStatusChanged $event): void
    {
        $voucher = $event->voucher;

        User::where('role', 'admin')->get()->each(function ($admin) use ($voucher) {
            $admin->notify(new VoucherStatusChangedNotification($voucher));
        });
    }
}
