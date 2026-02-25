<?php

namespace Auty\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OtpNotification extends Notification
{
    public function __construct(protected string $code) {}

    public function via(object $notifiable): array { return ['mail']; }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Login Code')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your one-time login code is:')
            ->line('**' . $this->code . '**')
            ->line('This code expires in ' . config('auty.otp.expires_in', 10) . ' minutes.')
            ->line('If you did not request this, you can safely ignore this email.');
    }
}
