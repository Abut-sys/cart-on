<?php

namespace App\Console;

use App\Models\Voucher;
use App\Notifications\VoucherNotification;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('vouchers:update-status')->dailyAt('00:00');
        $schedule->command('app:delete-unverified-users')->hourly();
        $schedule->command('orders:expire-pending')->everyMinute();

        $schedule->call(function () {
            $tomorrow = Carbon::tomorrow()->toDateString();

            $vouchers = Voucher::whereDate('end_date', $tomorrow)->get();

            foreach ($vouchers as $voucher) {
                foreach ($voucher->claimVoucher as $claim) {
                    $user = $claim->user;
                    if ($user) {
                        $user->notify(new VoucherNotification(
                            "Voucher {$voucher->code} will expire tomorrow!",
                            route('your-vouchers')
                        ));
                    }
                }
            }
        })->daily();

        $schedule->call(function () {
            $yesterday = Carbon::yesterday()->toDateString();

            $vouchers = Voucher::whereDate('end_date', '<=', $yesterday)->get();

            foreach ($vouchers as $voucher) {
                foreach ($voucher->claimVoucher as $claim) {
                    $user = $claim->user;
                    if ($user) {
                        $user->notify(new VoucherNotification(
                            "Voucher {$voucher->code} has expired!",
                            route('your-vouchers')
                        ));
                    }
                }
            }
        })->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

    protected $commands = [
        \App\Console\Commands\UpdateVoucherStatus::class,
    ];
}
