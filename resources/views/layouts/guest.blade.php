<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-100 antialiased bg-slate-950 min-h-screen flex flex-col justify-center items-center p-4 relative overflow-hidden selection:bg-indigo-500 selection:text-white">
        
        {{-- افکت گلاسمورفیسم و نئون در پس‌زمینه صفحات ورود/ثبت‌نام --}}
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-indigo-600/10 rounded-full blur-3xl -z-10 animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-cyan-600/10 rounded-full blur-3xl -z-10"></div>

        <div class="w-full max-w-md my-6">
            {{-- بخش لوگو یا نام سیستم --}}
            <div class="flex flex-col items-center mb-8">
                <a href="/" class="text-3xl font-black bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent drop-shadow-[0_0_15px_rgba(99,102,241,0.2)]">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <p class="text-xs text-slate-400 mt-2">سامانه هوشمند پشتیبانی و مدیریت تیکت</p>
            </div>

            {{-- باکس فرم‌ها با استایل شیشه‌ای (Glassmorphism) --}}
            <div class="glass-card rounded-3xl p-8 shadow-2xl border border-slate-800/80 backdrop-blur-xl relative">
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-1/2 h-[2px] bg-gradient-to-r from-transparent via-indigo-500 to-transparent"></div>
                {{ $slot }}
            </div>
        </div>
    </body>
</html>