<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // اگر کاربر لاگین نکرده باشد، یا کاربر عادی باشد (مقدار آن 0 باشد)
        if (!auth()->check() || auth()->user()->is_admin == 0) {
            
            return redirect()->route('user.tickets.index')
                ->with('error', 'شما دسترسی لازم برای ورود به این بخش را ندارید.');
        }

        // اگر کارشناس (1) یا ادمین اصلی (2) بود، بدون مشکل به مسیرش ادامه می‌دهد
        return $next($request);
    }
}