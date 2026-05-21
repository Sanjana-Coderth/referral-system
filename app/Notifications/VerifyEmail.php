<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class VerifyEmail extends Notification implements ShouldQueue
{
    use Queueable;

    public static $createUrlCallback;

    public static $toMailCallback;

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build mail notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        if (static::$toMailCallback) {
            return call_user_func(
                static::$toMailCallback,
                $notifiable,
                $verificationUrl
            );
        }

        return $this->buildMailMessage($verificationUrl);
    }

    /**
     * Email template.
     */
    protected function buildMailMessage(string $url): MailMessage
    {
        return (new MailMessage)
            ->subject(__('email.verify_email'))
            ->line(__('email.verify_email_click'))
            ->action(__('email.verify_email'), $url)
            ->line(__('email.verify_email_ignore'))
            ->line(__('email.good_luck'));
    }

    /**
     * Generate verification URL.
     */
    protected function verificationUrl(object $notifiable): string
    {
        if (static::$createUrlCallback) {
            return call_user_func(
                static::$createUrlCallback,
                $notifiable
            );
        }

        $url = URL::temporarySignedRoute(
            'user.verification.verify',
            Carbon::now()->addMinutes(
                Config::get('auth.verification.expire', 60)
            ),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1(
                    $notifiable->getEmailForVerification()
                ),
            ]
        );

        return $url;
    }

    /**
     * Custom URL callback.
     */
    public static function createUrlUsing(callable $callback): void
    {
        static::$createUrlCallback = $callback;
    }

    /**
     * Custom mail callback.
     */
    public static function toMailUsing(callable $callback): void
    {
        static::$toMailCallback = $callback;
    }
}
