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
        $currentDate = Carbon::now();

        // Ambil semua voucher
        $vouchers = Voucher::all();

        foreach ($vouchers as $voucher) {
            if ($currentDate->isAfter($voucher->end_date)) {
                // Jika sudah lewat tanggal berakhir, set status sebagai tidak aktif
                // Jika Anda menyimpan status sebagai kolom di database, gunakan ini:
                $voucher->status = 'inactive';
            } elseif ($currentDate->isBetween($voucher->start_date, $voucher->end_date, null, '[]')) {
                // Jika dalam rentang tanggal, set status sebagai aktif
                $voucher->status = 'active';
            } else {
                // Jika di luar rentang tanggal, set status sebagai tidak aktif
                $voucher->status = 'inactive';
            }

            $voucher->save(); // Simpan perubahan
        }

        $this->info('Voucher statuses have been updated successfully.');
    }
}
