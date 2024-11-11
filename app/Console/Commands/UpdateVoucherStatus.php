<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Voucher;
use Carbon\Carbon;

class UpdateVoucherStatus extends Command
{
    protected $signature = 'vouchers:update-status';
    protected $description = 'Update the status of vouchers based on the current date';

    public function handle()
    {
        $today = Carbon::today();

        // Update vouchers that should be active
        Voucher::where('status', 'inactive')
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->update(['status' => 'active']);

        // Update vouchers that should be inactive  
        Voucher::where('status', 'active')
            ->where('end_date', '<', $today)
            ->update(['status' => 'inactive']);

        // Voucher::where(...)->update([...]);: Query untuk memperbarui status voucher berdasarkan tanggal.

        $this->info('Voucher statuses updated successfully.');
    }
}
