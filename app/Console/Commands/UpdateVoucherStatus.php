<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Voucher;
use Carbon\Carbon;
use App\Events\VoucherStatusChanged;

class UpdateVoucherStatus extends Command
{
    protected $signature = 'vouchers:update-status';
    protected $description = 'Update the status of vouchers based on the current date';

    public function handle()
    {
        $today = Carbon::today();

        $activeVouchers = Voucher::where('status', 'inactive')
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->pluck('id');

        if ($activeVouchers->isNotEmpty()) {
            Voucher::whereIn('id', $activeVouchers)
                ->update(['status' => 'active']);

            foreach ($activeVouchers as $voucherId) {
                event(new VoucherStatusChanged(Voucher::find($voucherId)));
            }
        }

        $inactiveVouchers = Voucher::where('status', 'active')
            ->where('end_date', '<', $today)
            ->pluck('id');

        if ($inactiveVouchers->isNotEmpty()) {
            Voucher::whereIn('id', $inactiveVouchers)
                ->update(['status' => 'inactive']);

            foreach ($inactiveVouchers as $voucherId) {
                event(new VoucherStatusChanged(Voucher::find($voucherId)));
            }
        }

        $this->info('Voucher statuses updated successfully.');
    }
}
