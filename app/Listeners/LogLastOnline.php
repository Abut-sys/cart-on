<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Carbon\Carbon;
use App\Models\User;

class LogLastOnline
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        if ($event->user instanceof User) {
            $event->user->update([
                'last_online_at' => Carbon::now(),
            ]);
        }
    }
}

