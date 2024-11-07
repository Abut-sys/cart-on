<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteUnverifiedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-unverified-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete users who have not verified their email within a specific timeframe';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Tentukan batas waktu untuk verifikasi (misal, 2 jam)
        $verificationTimelimit = Carbon::now()->subHours(2);

        // Hapus pengguna yang tidak terverifikasi
        $usersDeleted = User::whereNull('email_verified_at')
            ->where('created_at', '<', $verificationTimelimit)
            ->delete();

        $this->info("Unverified users deleted successfully: {$usersDeleted} users.");
    }
}
