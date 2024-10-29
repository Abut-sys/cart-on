<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Voucher;
use Carbon\Carbon;

class UpdateVoucherStatus extends Command
{
    protected $signature = 'vouchers:update-status';
    protected $description = 'Update the status of vouchers based on the current date and usage limits';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $currentDate = Carbon::now()->toDateString();

        // Fetch all vouchers to check their conditions
        $vouchers = Voucher::all();

        foreach ($vouchers as $voucher) {
            $isActive = $this->checkVoucherConditions($voucher, $currentDate);

            // Update the status if necessary
            if ($isActive && $voucher->status !== 'active') {
                $voucher->status = 'active';
                $voucher->save();
            } elseif (!$isActive && $voucher->status !== 'inactive') {
                $voucher->status = 'inactive';
                $voucher->save();
            }
        }

        $this->info('Voucher statuses have been updated successfully.');
    }

    private function checkVoucherConditions($voucher, $currentDate)
    {
        // Check date range
        if ($currentDate < $voucher->start_date || $currentDate > $voucher->end_date) {
            return false;
        }

        // Check usage limit
        if ($voucher->usage_limit <= 0) {
            return false;
        }

        return true;
    }
}
