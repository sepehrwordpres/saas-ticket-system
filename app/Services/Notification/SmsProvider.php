<?php

namespace App\Services\Notification;

use App\Models\User;
use Illuminate\Support\Facades\Http;

class SmsProvider implements NotificationProviderInterface
{
    public function send(User $user, string $message): bool
    {
        // ۱. گرفتن شماره موبایل کاربر از دیتابیس
        $phoneNumber = $user->phone; // مطمئن شو که نام فیلد در جدول کاربرانت همین است

        if (!$phoneNumber) {
            logger("خطا: شماره موبایلی برای کاربر {$user->name} یافت نشد.");
            return false;
        }

        // ۲. ارسال درخواست به وب‌سرویس ملی‌پيامک (ارسال بر اساس پترن/قالب آماده)
        $response = Http::post('https://rest.payamak-panel.com/api/SendSMS/BaseServiceNumber', [
            'username' => env('MELIPYAMAK_USERNAME'),
            'password' => env('MELIPYAMAK_PASSWORD'),
            'text'     => $message, // یا در صورت استفاده از پترن اختصاصی، آرایه‌ای از متغیرها
            'to'       => $phoneNumber,
            'bodyId'   => env('MELIPYAMAK_BODY_ID'), // شناسه قالب تایید شده در پنل شما
        ]);

        // ۳. بررسی وضعیت ارسال
        if ($response->successful()) {
            logger("پیامک با موفقیت از طریق ملی‌پيامک به شماره {$phoneNumber} ارسال شد.");
            return true;
        }

        logger("خطا در ارسال پیامک ملی‌پيامک: " . $response->body());
        return false;
    }
}