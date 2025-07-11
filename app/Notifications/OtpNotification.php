<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OtpNotification extends Notification
{
    use Queueable;

    protected $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->greeting('Hello!')
            ->line('Thank you for using our services.')
            ->line('To proceed with your request, please use the One-Time Password (OTP) provided below:')
            ->with(['otp' => $this->otp])
            ->line('This code is valid for the next 10 minutes. Please do not share it with anyone.')
            ->line('If you did not request this code, please ignore this email or contact our support team.')
            ->salutation('Regards, The CartON Team');
    }
}
