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
        $verificationTimelimit = Carbon::now()->subHours(2);
        $batchSize = 100; // Adjust the batch size as needed

        do {
            $users = User::whereNull('email_verified_at')
                ->where('created_at', '<', $verificationTimelimit)
                ->limit($batchSize)
                ->get();

            $userCount = $users->count();

            if ($userCount > 0) {
                User::whereIn('id', $users->pluck('id'))->delete();
                $this->info("Unverified users deleted successfully: {$userCount} users.");
            }
        } while ($userCount > 0);
    }
}
