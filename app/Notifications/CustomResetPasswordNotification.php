<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;
    public $email;

    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $frontendUrl = config('app.frontend_url', 'http://localhost:5173');
        $resetUrl = $frontendUrl . '/reset-password/' . $this->token . '?email=' . urlencode($this->email);

        return (new MailMessage)
            ->subject('Reset Your Password')
            ->greeting('Hello,')
            ->line('You requested a password reset for your account.')
            ->action('Reset Password', $resetUrl)
            ->line('If you didn\'t request this, no further action is required.')
            ->salutation('Regards, ' . config('app.name'));
    }
}