<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends BaseResetPassword
{
    protected $resetUrl;

    public function __construct($token, $resetUrl = null)
    {
        parent::__construct($token);
        $this->resetUrl = $resetUrl;
    }

    protected function resetUrl($notifiable)
    {
        // Use custom URL if provided
        if ($this->resetUrl) {
            return $this->resetUrl;
        }

        // Default fallback (should not normally be used in SPA)
        return url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));
    }
}
