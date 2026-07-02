<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - پلتفرم مدرن پشتیبانی</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#030712] text-slate-100 antialiased font-sans selection:bg-indigo-500 selection:text-white min-h-screen flex flex-col justify-between overflow-y-auto overflow-x-hidden relative select-none">

    {{-- نوار ناوبری شیشه‌ای بالا --}}
    <nav class="w-full fixed top-0 left-0 z-50 border-b border-slate-900/50 bg-[#030712]/60 backdrop-blur-xl">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-xl font-black bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent drop-shadow-[0_0_15px_rgba(99,102,241,0.3)]">
                    {{ config('app.name', 'Laravel') }}
                </span>
            </div>
            
            <div class="flex items-center gap-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-xs font-bold text-indigo-400 hover:text-indigo-300 transition-colors bg-indigo-500/10 border border-indigo-500/20 px-4 py-2 rounded-xl shadow-[0_0_15px_rgba(99,102,241,0.05)]">
                            ورود به میز کار من ←
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-xs font-semibold text-slate-400 hover:text-white transition-colors">ورود به پنل</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-xs font-bold text-white bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 hover:opacity-90 px-4 py-2 rounded-xl shadow-lg shadow-indigo-500/20 transition-all hover:-translate-y-0.5">
                                ایجاد حساب کاربری
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    {{-- اتمسفر سه‌بعدی و محیط تیکت‌های شناور کج شده (3D Perspective Environment) --}}
    <div class="absolute inset-0 pointer-events-none z-0 mt-16 h-full" style="perspective: 1600px; transform-style: preserve-3d;">
        
        {{-- هاله نوری عمیق بک‌گراند --}}
        <div class="absolute top-1/4 left-1/4 w-[600px] h-[600px] bg-indigo-600/[0.04] rounded-full blur-[140px] transform -translate-z-50"></div>
        <div class="absolute bottom-1/4 right-1/3 w-[600px] h-[600px] bg-pink-600/[0.03] rounded-full blur-[140px] transform -translate-z-50"></div>

        <div class="absolute top-[10%] left-[6%] max-w-[220px] bg-slate-900/40 backdrop-blur-md border border-sky-500/30 p-3 rounded-xl rounded-bl-none shadow-[5px_5px_20px_rgba(0,0,0,0.5),_0_0_15px_rgba(56,189,248,0.1)] animate-float-3d" 
             style="animation-delay: 0s; transform: rotateY(25deg) rotateX(10deg) translateZ(50px);">
            <div class="text-[9px] font-bold text-sky-400 mb-0.5 text-right">User #249</div>
            <p class="text-[11px] text-slate-300 text-right leading-relaxed">تراکنش من ناموفق بود ولی مبلغ کسر شده.</p>
        </div>

        <div class="absolute top-[14%] right-[7%] max-w-[220px] bg-slate-900/40 backdrop-blur-md border border-emerald-500/30 p-3 rounded-xl rounded-br-none shadow-[-5px_5px_20px_rgba(0,0,0,0.5),_0_0_15px_rgba(52,211,153,0.1)] animate-float-3d" 
             style="animation-delay: 2s; transform: rotateY(-25deg) rotateX(10deg) translateZ(40px);">
            <div class="text-[9px] font-bold text-emerald-400 mb-0.5 text-right">Support (احمد)</div>
            <p class="text-[11px] text-slate-300 text-right leading-relaxed">درخواست شما به دپارتمان مالی ارجاع شد.</p>
        </div>

        <div class="absolute top-[45%] left-[4%] max-w-[200px] bg-purple-950/20 backdrop-blur-md border border-purple-500/30 p-3 rounded-xl shadow-[5px_5px_20px_rgba(0,0,0,0.5),_0_0_15px_rgba(168,85,247,0.1)] animate-float-3d" 
             style="animation-delay: 4s; transform: rotateY(30deg) translateZ(20px);">
            <div class="text-[9px] font-bold text-purple-400 mb-0.5 text-right">🔒 Internal Note</div>
            <p class="text-[11px] text-slate-300 text-right leading-relaxed">سوییچ تیکت با موفقیت رفرش شد.</p>
        </div>

        <div class="absolute bottom-[35%] right-[6%] max-w-[220px] bg-slate-900/40 backdrop-blur-md border border-amber-500/30 p-3 rounded-xl rounded-bl-none shadow-[-5px_5px_20px_rgba(0,0,0,0.5),_0_0_15px_rgba(245,158,11,0.1)] animate-float-3d" 
             style="animation-delay: 1s; transform: rotateY(-20deg) rotateX(-5deg) translateZ(60px);">
            <div class="text-[9px] font-bold text-amber-400 mb-0.5 text-right">User #112</div>
            <p class="text-[11px] text-slate-300 text-right leading-relaxed">با راهنمایی شما خطا برطرف شد. مرسی.</p>
        </div>
    </div>

    {{-- هیرو سکشن اصلی وسط صفحه --}}
    <div class="relative z-10 max-w-4xl mx-auto text-center space-y-8 flex flex-col items-center pt-36 px-6">
        
        <span class="px-3 py-1 text-[10px] font-bold tracking-wide text-indigo-400 bg-indigo-500/10 border border-indigo-500/20 rounded-full backdrop-blur-sm shadow-[0_0_15px_rgba(99,102,241,0.1)]">
            ⚡ پلتفرم متمرکز و هماهنگ مدیریت تیکتینگ
        </span>
        
        <h1 class="text-4xl sm:text-6xl font-black text-slate-100 tracking-tight leading-tight max-w-3xl">
            سرعتِ پاسخگویی را در <br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 drop-shadow-[0_0_25px_rgba(168,85,247,0.35)]">اتومیشن ساختاریافته</span> لمس کنید
        </h1>
        
        <p class="text-slate-400 text-xs sm:text-sm max-w-xl leading-relaxed opacity-85">
            در این سامانه، تمام گفتگوها در دپارتمان‌های مجهز با سرعت بالا، لایه امنیتی یادداشت داخلی کارشناسان و مانیتورینگ کارآمد به صورت یکپارچه مدیریت می‌شوند.
        </p>

        <div class="pt-4 flex items-center justify-center gap-4 w-full">
            @auth
                <a href="{{ route('user.tickets.create') }}" 
                   class="px-8 py-3.5 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 hover:opacity-90 text-white font-bold text-xs rounded-xl transition-all shadow-xl shadow-indigo-500/20 hover:-translate-y-0.5 cursor-pointer">
                    ثبت تیکت پشتیبانی جدید
                </a>
                <a href="{{ url('/dashboard') }}" 
                   class="px-8 py-3.5 bg-slate-900/60 hover:bg-slate-900 text-slate-300 font-semibold text-xs rounded-xl border border-slate-800/80 transition-all hover:-translate-y-0.5 backdrop-blur-sm">
                    ورود به میز کار
                </a>
            @else
                <a href="{{ route('login') }}" 
                   class="px-8 py-3.5 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 hover:opacity-90 text-white font-bold text-xs rounded-xl transition-all shadow-xl shadow-indigo-500/20 hover:-translate-y-0.5 cursor-pointer">
                    شروع گفتگو و ارسال درخواست
                </a>
                <a href="{{ route('register') }}" 
                   class="px-8 py-3.5 bg-slate-900/60 hover:bg-slate-900 text-slate-300 font-semibold text-xs rounded-xl border border-slate-800/80 transition-all hover:-translate-y-0.5 backdrop-blur-sm">
                    ایجاد حساب کاربری جدید
                </a>
            @endauth
        </div>
    </div>

    {{-- کار‌های سه‌بعدی معرفی ویژگی‌ها (3D Isometric Grid Features) --}}
    <div class="relative z-10 max-w-5xl mx-auto px-6 my-24 w-full" style="perspective: 1200px;">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8" style="transform-style: preserve-3d;">
            
            <div class="glass-card p-6 rounded-2xl border border-slate-800/50 bg-slate-900/20 backdrop-blur-xl relative overflow-hidden transition-all duration-500 hover:hover-card-3d shadow-[0_10px_30px_rgba(0,0,0,0.3)]"
                 style="transform: rotateX(15deg) rotateY(-10deg);">
                <div class="w-10 h-10 rounded-xl bg-pink-500/10 border border-pink-500/20 flex items-center justify-center text-pink-400 mb-4 shadow-[0_0_15px_rgba(236,72,153,0.1)]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-200 mb-2">📂 تفکیک هوشمند دپارتمان‌ها</h3>
                <p class="text-xs text-slate-400 leading-relaxed">ارجاع خودکار و منظم درخواست‌ها به بخش‌های تخصصی (فنی، مالی، فروش) جهت تسریع در فرآیند تحلیل و پاسخگویی.</p>
            </div>

            <div class="glass-card p-6 rounded-2xl border border-slate-800/50 bg-slate-900/20 backdrop-blur-xl relative overflow-hidden transition-all duration-500 hover:hover-card-3d shadow-[0_10px_30px_rgba(0,0,0,0.3)]"
                 style="transform: rotateX(15deg) rotateY(0deg);">
                <div class="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 mb-4 shadow-[0_0_15px_rgba(99,102,241,0.1)]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-200 mb-2">🔒 یادداشت‌های محرمانه درون‌سازمانی</h3>
                <p class="text-xs text-slate-400 leading-relaxed">امکان ثبت مستندات فنی و یادداشت‌های داخلی پنهان برای مدیریت سیستم، بدون ایجاد شلوغی در محیط گفتگوی کاربر.</p>
            </div>

            <div class="glass-card p-6 rounded-2xl border border-slate-800/50 bg-slate-900/20 backdrop-blur-xl relative overflow-hidden transition-all duration-500 hover:hover-card-3d shadow-[0_10px_30px_rgba(0,0,0,0.3)]"
                 style="transform: rotateX(15deg) rotateY(10deg);">
                <div class="w-10 h-10 rounded-xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center text-cyan-400 mb-4 shadow-[0_0_15px_rgba(6,182,212,0.1)]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-200 mb-2">📊 مانیتورینگ وضعیت تیکت‌ها</h3>
                <p class="text-xs text-slate-400 leading-relaxed">ردیابی آنی مراحل بررسی درخواست از وضعیت «جدید» و «در انتظار» تا مرحله صدور پاسخ نهایی توسط کارشناس سیستم.</p>
            </div>

        </div>
    </div>

    {{-- بخش آمار پایینی صفحه --}}
    <div class="relative z-10 max-w-4xl w-full mx-auto grid grid-cols-3 gap-2 pb-12 px-6 border-t border-slate-900/60 pt-6">
        <div class="text-center space-y-0.5">
            <div class="text-sm sm:text-lg font-bold text-indigo-400 font-mono tracking-tight">
                {{ $stats['tickets_resolved'] ?? '1,420' }}+
            </div>
            <div class="text-[9px] text-slate-500 font-medium">پشتیبانی موفق دیتابیس</div>
        </div>
        
        <div class="text-center space-y-0.5 border-x border-slate-950">
            <div class="text-sm sm:text-lg font-bold text-slate-300 font-mono tracking-tight">
                {{ $stats['response_time'] ?? 'کمتر از ۱۰ دقیقه' }}
            </div>
            <div class="text-[9px] text-slate-500 font-medium">میانگین زمان پاسخ</div>
        </div>
        
        <div class="text-center space-y-0.5">
            <div class="text-sm sm:text-lg font-bold text-emerald-400 font-mono tracking-tight">
                {{ $stats['satisfaction_rate'] ?? '98.5%' }}
            </div>
            <div class="text-[9px] text-slate-500 font-medium">نرخ رضایتمندی کاربران</div>
        </div>
    </div>

    <style>
        /* انیمیشن شناوری سه‌بعدی تیکت‌های پس‌زمینه */
        @keyframes floating3D {
            0% { transform: rotateY(var(--tw-rotate-y, 20deg)) rotateX(var(--tw-rotate-x, 10deg)) translateY(0px) translateZ(30px); }
            50% { transform: rotateY(var(--tw-rotate-y, 22deg)) rotateX(var(--tw-rotate-x, 12deg)) translateY(-15px) translateZ(45px); }
            100% { transform: rotateY(var(--tw-rotate-y, 20deg)) rotateX(var(--tw-rotate-x, 10deg)) translateY(0px) translateZ(30px); }
        }
        .animate-float-3d {
            animation: floating3D 8s ease-in-out infinite;
        }

        /* استایل‌های کاستوم هاور کارت‌های سه بعدی ویژگی‌ها */
        .hover-card-3d {
            transform: rotateX(0deg) rotateY(0deg) translateY(-8px) translateZ(10px) !important;
            border-color: rgba(99, 102, 241, 0.4) !important;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6), 0 0 20px rgba(99, 102, 241, 0.15) !important;
        }
    </style>
</body>
</html>