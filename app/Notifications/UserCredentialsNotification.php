<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;

class UserCredentialsNotification extends Notification
{
    use Queueable;

    public $user;
    public $plainPassword;
    public $loginUrl;

    public function __construct(User $user, $plainPassword)
    {
        $this->user = $user;
        $this->plainPassword = $plainPassword;
        $this->loginUrl = config('app.url') . '/login';
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Welcome to ' . config('app.name') . ' - Your Account Details')
            ->view('emails.user-credentials', [
                'user' => $this->user,
                'password' => $this->plainPassword,
                'loginUrl' => $this->loginUrl,
            ]);
    }
}