<?php

namespace App\Services\Notification;

use App\Models\User;

interface NotificationProviderInterface
{
    /**
     * متد مشترک برای ارسال نوتیفیکیشن
     */
    public function send(User $user, string $message): bool;
}