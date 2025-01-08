<?php

namespace App\Observers;

use App\Models\Voucher;
use Carbon\Carbon;

class VoucherObserver
{
    /**
     * Handle the Voucher "creating" event.
     */
    public function creating(Voucher $voucher): void
    {
        $this->setVoucherStatus($voucher);
    }

    /**
     * Handle the Voucher "updating" event.
     */
    public function updating(Voucher $voucher): void
    {
        $this->setVoucherStatus($voucher);
    }

    /**
     * Set the status of the voucher based on start_date and end_date.
     */
    private function setVoucherStatus(Voucher $voucher): void
    {
        $today = Carbon::today();

        if ($today->between($voucher->start_date, $voucher->end_date)) {
            $voucher->status = 'active';
        } else {
            $voucher->status = 'inactive';
        }
    }

    /**
     * Handle the Voucher "created" event.
     */
    public function created(Voucher $voucher): void
    {
        //
    }

    /**
     * Handle the Voucher "updated" event.
     */
    public function updated(Voucher $voucher): void
    {
        //
    }

    /**
     * Handle the Voucher "deleted" event.
     */
    public function deleted(Voucher $voucher): void
    {
        //
    }

    /**
     * Handle the Voucher "restored" event.
     */
    public function restored(Voucher $voucher): void
    {
        //
    }

    /**
     * Handle the Voucher "force deleted" event.
     */
    public function forceDeleted(Voucher $voucher): void
    {
        //
    }
}
