<?php

namespace App\Services\Notification;

use App\Models\User;
use InvalidArgumentException;

class NotificationService
{
    protected array $providers = [
        'email' => EmailProvider::class,
        'sms'   => SmsProvider::class,
    ];

    /**
     * متد جادویی ارسال که با تغییر کلمه کلیدی، رفتار را تغییر می‌دهد
     */
    public function sendNotification(string $method, User $user, string $message): bool
    {
        if (!array_key_exists($method, $this->providers)) {
            throw new InvalidArgumentException("این کانال ارسال پشتیبانی نمی‌شود: {$method}");
        }

        // ساخت شیء از کلاس مربوطه به صورت داینامیک
        $providerClass = $this->providers[$method];
        $provider = new $providerClass();

        return $provider->send($user, $message);
    }
}