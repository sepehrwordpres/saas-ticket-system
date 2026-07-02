<?php

namespace App\Services\Notification;

use App\Models\User;
use Illuminate\Support\Facades\Mail;

class EmailProvider implements NotificationProviderInterface
{
    public function send(User $user, string $message): bool
    {
        // 💡 آدرس جیمیل خودت را اینجا بنویس تا ایمیل‌های تست به دست خودت برسد
        $myEmail = 'dukesepehr443@gmail.com'; 

        // متد استاندارد لاراول برای ارسال ایمیل متنی ساده
        Mail::raw($message, function ($mail) use ($myEmail) {
            $mail->to($myEmail)
                 ->subject('🔔 اعلان جدید سیستم تیکت');
        });
        
        logger("Email successfully sent via SMTP to {$myEmail}");
        return true;
    }
}