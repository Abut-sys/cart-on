<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Carbon\Carbon;
use App\Models\User;

class LogRegisterOnline
{
    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        if ($event->user instanceof User) {
            $event->user->update([
                'last_online_at' => Carbon::now(),
            ]);
        }
    }
}

