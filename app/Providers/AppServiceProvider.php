<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Services\Notification\NotificationProviderInterface; // 💡 وارد کردن اینترفیس
use App\Services\Notification\EmailProvider; // 💡 وارد کردن کلاس درایور ایمیل
use App\Services\Notification\SmsProvider;   // 💡 وارد کردن کلاس درایور اس‌ام‌اس (ملی پیامک)

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // 👑 جادوی اتصال دو کلاس در لاراول:
        // با این خط به لاراول می‌گوییم هر جا کنترلری "NotificationProviderInterface" را خواست، کلاس "EmailProvider" را به آن تحویل بده.
        // فردا روزی اگر خواستی کل پروژه اس‌ام‌اس شود، کافیست EmailProvider را پاک کنی و جایش SmsProvider بنویسی.
        $this->app->bind(NotificationProviderInterface::class, EmailProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // فقط ادمین اصلی (سطح 2) اجازه مدیریت دپارتمان و پشتیبان‌ها را دارد
        Gate::define('super-admin', function (User $user) {
            return $user->is_admin === 2;
        });
    }
}