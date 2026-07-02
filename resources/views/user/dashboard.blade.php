@extends('Layout.app')

@section('title', 'پیشخوان کاربری')

@section('content')
<div class="flex min-h-screen bg-slate-900 text-slate-100 font-sans antialiased">
    
    <aside class="w-64 bg-slate-950 text-slate-200 flex flex-col justify-between fixed h-full right-0 top-0 z-40 border-l border-slate-800 shadow-2xl">
        <div>
            <div class="h-16 flex items-center justify-center border-b border-slate-800 px-6">
                <span class="text-lg font-black tracking-wide text-amber-500">میز کاربری</span>
            </div>

            <nav class="mt-6 px-4 space-y-1">
                <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('user.dashboard') ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }} transition-all">
                    <span>📊</span> <span>پیشخوان (داشبورد)</span>
                </a>
                <a href="{{ route('user.tickets.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('user.tickets.index') ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }} transition-all">
                    <span>✉️</span> <span>تیکت‌های من</span>
                </a>
                <a href="{{ route('user.tickets.create') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('user.tickets.create') ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }} transition-all">
                    <span>➕</span> <span>ارسال تیکت جدید</span>
                </a>
            </nav>
        </div>

        <div class="p-4 border-t border-slate-800 space-y-3">
            <div class="flex items-center gap-3 px-2">
                <div class="w-8 h-8 rounded-full bg-amber-500/20 text-amber-400 flex items-center justify-center font-bold text-xs">
                    U
                </div>
                <div class="text-xs">
                    <p class="font-bold text-slate-200">{{ auth()->user()->name ?? 'کاربر سیستم' }}</p>
                </div>
            </div>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-xs font-semibold text-rose-400 hover:bg-rose-500/10 rounded-xl transition-all text-right">
                    <span>🚪</span> <span>خروج از حساب</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 mr-64 flex flex-col min-h-screen">
        
        <header class="h-16 bg-slate-950 border-b border-slate-800 px-8 flex items-center justify-between sticky top-0 z-30">
            <div class="text-sm font-bold text-slate-300">
                خوش آمدید، {{ auth()->user()->name ?? 'کاربر' }} 👋
            </div>
            <div class="text-xs text-slate-500 font-medium">
                سیستم مدیریت تیکتینگ دپارتمان‌محور
            </div>
        </header>

        <main class="p-8 flex-1">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="bg-slate-950 p-6 rounded-2xl border border-slate-800 shadow-lg">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-400">کل تیکت‌های ارسال شده</span>
                        <span class="text-2xl">📩</span>
                    </div>
                    <p class="text-3xl font-black text-white mt-4">{{ $stats['total'] }}</p>
                </div>

                <div class="bg-slate-950 p-6 rounded-2xl border border-slate-800 shadow-lg">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-400">تیکت‌های در جریان (باز)</span>
                        <span class="text-2xl">⏳</span>
                    </div>
                    <p class="text-3xl font-black text-amber-400 mt-4">{{ $stats['open'] }}</p>
                </div>

                <div class="bg-slate-950 p-6 rounded-2xl border border-slate-800 shadow-lg">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-400">تیکت‌های حل شده (بسته)</span>
                        <span class="text-2xl">✅</span>
                    </div>
                    <p class="text-3xl font-black text-emerald-400 mt-4">{{ $stats['closed'] }}</p>
                </div>

            </div>
        </main>

    </div>
</div>
@endsection