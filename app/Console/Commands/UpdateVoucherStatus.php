<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Voucher;
use App\Models\User;
use App\Notifications\VoucherNotification;

class UpdateVoucherStatus extends Command
{
    protected $signature = 'vouchers:update-status';
    protected $description = 'Update the status of vouchers based on the current date';

    public function handle()
    {
        $this->info('Updating voucher statuses...');

        $count = 0;

        Voucher::all()->each(function ($voucher) use (&$count) {
            $oldStatus = $voucher->status;
            $voucher->updateStatus();

            if ($voucher->status !== $oldStatus) {
                $this->info("Voucher [{$voucher->code}] status changed: {$oldStatus} -> {$voucher->status}");
                $count++;

                if ($voucher->status === 'inactive') {
                    foreach ($voucher->claimedUsers as $user) {
                        $user->notify(new VoucherNotification(
                            "Your voucher {$voucher->code} has expired."
                        ));
                    }

                    $admins = User::where('role', 'admin')->get();
                    foreach ($admins as $admin) {
                        $admin->notify(new VoucherNotification(
                            "Voucher {$voucher->code} has expired."
                        ));
                    }
                }
            }
        });

        $this->info("Finished! {$count} voucher(s) updated.");
    }
}
