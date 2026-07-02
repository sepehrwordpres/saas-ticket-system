<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-xl bg-gradient-to-r from-indigo-400 to-cyan-400 bg-clip-text text-transparent leading-tight">
                    @if(Auth::user()->role === 'admin' || Auth::user()->is_admin)
                        میز کار مدیریت سیستم تیکتینگ 🛠️
                    @else
                        میز کار من خوش آمدید 👋
                    @endif
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    @if(Auth::user()->role === 'admin' || Auth::user()->is_admin)
                        گزارش لحظه‌ای و مدیریت تیکت‌های کل کاربران سیستم
                    @else
                        خلاصه وضعیت سرویس‌ها و درخواست‌های پشتیبانی شما
                    @endif
                </p>
            </div>
            
            {{-- دکمه دسترسی سریع برای ارسال تیکت (مخصوص کاربر عادی) --}}
            @if(!(Auth::user()->role === 'admin' || Auth::user()->is_admin))
                <a href="{{ route('user.tickets.create') }}" class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-bold text-sm px-5 py-3 rounded-xl shadow-lg shadow-indigo-500/20 transition-all hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    ثبت درخواست پشتیبانی جدید
                </a>
            @endif
        </div>
    </x-slot>

    <div class="space-y-8 mt-4">
        
        {{-- بخش اول: کارت‌های آمار و ویجت‌های هوشمند متصل به دیتابیس --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <div class="glass-card rounded-2xl p-6 flex items-center justify-between shadow-xl relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-blue-500/10 rounded-full blur-xl group-hover:bg-blue-500/20 transition-all"></div>
                <div class="space-y-2">
                    <span class="text-xs font-medium text-slate-400 block">تیکت‌های در جریان</span>
                    <span class="text-3xl font-black text-blue-400 font-mono">{{ $activeTickets }}</span>
                </div>
                <div class="p-3 rounded-xl bg-blue-500/10 text-blue-400 border border-blue-500/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 4.412 9 8z"/></svg>
                </div>
            </div>

            <div class="glass-card rounded-2xl p-6 flex items-center justify-between shadow-xl relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-500/10 rounded-full blur-xl group-hover:bg-emerald-500/20 transition-all"></div>
                <div class="space-y-2">
                    <span class="text-xs font-medium text-slate-400 block">پاسخ‌های داده شده</span>
                    <span class="text-3xl font-black text-emerald-400 font-mono">{{ $answeredTickets }}</span>
                </div>
                <div class="p-3 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                </div>
            </div>

            <div class="glass-card rounded-2xl p-6 flex items-center justify-between shadow-xl relative overflow-hidden group sm:col-span-2 lg:col-span-1">
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-purple-500/10 rounded-full blur-xl group-hover:bg-purple-500/20 transition-all"></div>
                <div class="space-y-2">
                    <span class="text-xs font-medium text-slate-400 block">مجموع کل تیکت‌ها</span>
                    <span class="text-3xl font-black text-purple-400 font-mono">{{ $totalTickets }}</span>
                </div>
                <div class="p-3 rounded-xl bg-purple-500/10 text-purple-400 border border-purple-500/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                </div>
            </div>

        </div>

        {{-- بخش دوم: جدول ۵ تیکت آخر دیتابیس --}}
        <div class="glass-card rounded-2xl shadow-2xl overflow-hidden">
            <div class="p-5 border-b border-slate-800 bg-slate-900/30 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-200 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-indigo-500 shadow-[0_0_8px_#6366f1]"></span>
                    آخرین وضعیت تیکت‌های پشتیبانی
                </h3>
                
                @if(Auth::user()->role === 'admin' || Auth::user()->is_admin)
                    <a href="{{ route('admin.tickets.index') }}" class="text-xs text-indigo-400 hover:text-indigo-300 font-semibold transition-colors">
                        مدیریت کل تیکت‌ها ←
                    </a>
                @else
                    <a href="{{ route('user.tickets.index') }}" class="text-xs text-indigo-400 hover:text-indigo-300 font-semibold transition-colors">
                        مشاهده همه تیکت‌های من ←
                    </a>
                @endif
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-900/10 text-slate-400 border-b border-slate-900">
                            <th class="p-4 font-semibold">موضوع تیکت</th>
                            <th class="p-4 font-semibold">دپارتمان مربوطه</th>
                            <th class="p-4 font-semibold">وضعیت سیستم</th>
                            <th class="p-4 font-semibold text-center">عملیات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/30">
                        @forelse($recentTickets as $ticket)
                            <tr class="hover:bg-slate-800/10 transition-colors">
                                <td class="p-4 font-medium text-slate-200 max-w-xs truncate">{{ $ticket->title }}</td>
                                <td class="p-4 text-slate-400">{{ $ticket->department->title['fa'] ?? 'بخش نامشخص' }}</td>
                                <td class="p-4">
                                    @if($ticket->status === 'new')
                                        <span class="px-2 py-0.5 rounded-full bg-teal-500/10 text-teal-400 border border-teal-500/20">جدید</span>
                                    @elseif($ticket->status === 'pending')
                                        <span class="px-2 py-0.5 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20">در انتظار بررسی</span>
                                    @elseif($ticket->status === 'answered')
                                        <span class="px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">پاسخ داده شده</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full bg-slate-700/20 text-slate-500 border border-slate-700/30">بسته شده</span>
                                    @endif
                                </td>
                                <td class="p-4 text-center">
                                    @if(Auth::user()->role === 'admin' || Auth::user()->is_admin)
                                        <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="inline-flex items-center justify-center bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 font-semibold px-3 py-1 rounded-lg border border-indigo-500/20 transition-all">
                                            بررسی کارشناس
                                        </a>
                                    @else
                                        <a href="{{ route('user.tickets.index') }}" class="inline-flex items-center justify-center bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold px-3 py-1 rounded-lg transition-all">
                                            مشاهده گفتگو
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-12 text-center text-slate-500">
                                    <p class="text-sm">هنوز هیچ درخواست پشتیبانی در سیستم ثبت نشده است.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>