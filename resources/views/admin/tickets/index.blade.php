@extends('layouts.app')

@section('content')
<div class="space-y-8">

    <div>
        <h2 class="text-2xl font-bold bg-gradient-to-r from-amber-400 to-orange-400 bg-clip-text text-transparent">میز کار مدیریت تیکت‌ها (پنل کارشناس)</h2>
        <p class="text-xs text-slate-400 mt-1">مدیریت، پاسخ‌گویی و تغییر وضعیت تمام درخواست‌های پشتیبانی سیستم</p>
    </div>

    <!-- 🔍 بخش فیلترهای پیشرفته سیستم (Glassmorphism Style) -->
    <div class="glass-card rounded-2xl p-5 shadow-xl border border-slate-800/60 bg-slate-900/20">
        <form action="{{ route('admin.tickets.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end text-xs">
            
            <!-- جستجوی متنی -->
            <div class="space-y-2">
                <label class="text-slate-400 font-medium block">جستجوی موضوع یا کد تیکت</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="مثلاً: لود نشدن سایت..." 
                       class="w-full bg-slate-950/60 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 placeholder-slate-600 focus:outline-none focus:border-amber-500/50 transition-colors">
            </div>

            <!-- فیلتر وضعیت -->
            <div class="space-y-2">
                <label class="text-slate-400 font-medium block">وضعیت تیکت</label>
                <select name="status" class="w-full bg-slate-950/60 border border-slate-800 rounded-xl px-4 py-3 text-slate-300 focus:outline-none focus:border-amber-500/50 transition-colors">
                    <option value="">همه وضعیت‌ها</option>
                    <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>جدید (بررسی نشده)</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>پاسخ کاربر / معلق</option>
                    <option value="answered" {{ request('status') === 'answered' ? 'selected' : '' }}>پاسخ کارشناس</option>
                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>بسته شده</option>
                </select>
            </div>

            <!-- فیلتر دپارتمان -->
            <div class="space-y-2">
                <label class="text-slate-400 font-medium block">دپارتمان</label>
                <select name="department_id" class="w-full bg-slate-950/60 border border-slate-800 rounded-xl px-4 py-3 text-slate-300 focus:outline-none focus:border-amber-500/50 transition-colors">
                    <option value="">همه دپارتمان‌ها</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->title['fa'] ?? $dept->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- دکمه‌های عملیات فیلتر -->
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold py-3 rounded-xl transition-all shadow-lg shadow-amber-500/10">
                    اعمال فیلتر
                </button>
                @if(request()->hasAny(['search', 'status', 'department_id']))
                    <a href="{{ route('admin.tickets.index') }}" class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-4 py-3 rounded-xl transition-colors flex items-center justify-center font-medium" title="پاکسازی فیلترها">
                        لغو
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- 📊 جدول نمایش تیکت‌ها -->
    <div class="glass-card rounded-2xl overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse text-sm">
                <thead>
                    <tr class="border-b border-slate-800 bg-slate-900/40 text-slate-400">
                        <th class="p-4 font-semibold">کد پیگیری / کاربر</th>
                        <th class="p-4 font-semibold">موضوع تیکت</th>
                        <th class="p-4 font-semibold">دپارتمان</th>
                        <th class="p-4 font-semibold">اولویت</th>
                        <th class="p-4 font-semibold">وضعیت سیستم</th>
                        <th class="p-4 font-semibold text-center">عملیات مدیریت</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/40">
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-slate-800/20 transition-colors">
                            
                            <td class="p-4">
                                <span class="block text-slate-200 font-medium text-xs font-mono mb-1 select-all">{{ substr($ticket->id, 0, 8) }}...</span>
                                <span class="text-[11px] text-slate-400 block">کاربر آی‌دی: {{ $ticket->user_id }}</span>
                            </td>

                            <td class="p-4 font-medium text-slate-200">
                                <span class="block max-w-xs truncate font-bold">{{ $ticket->title }}</span>
                            </td>
                            
                            <td class="p-4 text-slate-300">
                                {{ $ticket->department->title['fa'] ?? 'بخش نامشخص' }}
                            </td>
                            
                            <td class="p-4">
                                @if($ticket->priority === 'low')
                                    <span class="text-xs px-2 py-0.5 rounded bg-slate-500/10 text-slate-400">کم</span>
                                @elseif($ticket->priority === 'medium')
                                    <span class="text-xs px-2 py-0.5 rounded bg-blue-500/10 text-blue-400">متوسط</span>
                                @elseif($ticket->priority === 'high')
                                    <span class="text-xs px-2 py-0.5 rounded bg-amber-500/10 text-amber-400">بالا</span>
                                @elseif($ticket->priority === 'critical')
                                    <span class="text-xs px-2 py-0.5 rounded bg-rose-500/10 text-rose-400 font-bold animate-pulse">بحرانی</span>
                                @endif
                            </td>
                            
                            <td class="p-4">
                                @if($ticket->status === 'new')
                                    <span class="text-xs px-2 py-1 rounded-full bg-teal-500/10 text-teal-400 border border-teal-500/30 shadow-[0_0_10px_rgba(20,184,166,0.1)]">جدید (بررسی نشده)</span>
                                @elseif($ticket->status === 'pending')
                                    <span class="text-xs px-2 py-1 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/30 shadow-[0_0_10px_rgba(245,158,11,0.1)]">پاسخ کاربر / معلق</span>
                                @elseif($ticket->status === 'answered')
                                    <span class="text-xs px-2 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 shadow-[0_0_10px_rgba(16,185,129,0.1)]">پاسخ کارشناس</span>
                                @elseif($ticket->status === 'closed')
                                    <span class="text-xs px-2 py-1 rounded-full bg-slate-700/20 text-slate-500 border border-slate-700/30">بسته شده</span>
                                @endif
                            </td>
                            
                            <td class="p-4 text-center">
                                <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="inline-flex items-center justify-center text-xs bg-amber-500/10 hover:bg-amber-500/20 text-amber-400 font-bold px-4 py-2 rounded-xl border border-amber-500/20 hover:border-amber-500/40 transition-all">
                                    بررسی و پاسخ ادمین
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center text-slate-500">
                                <span class="text-lg">هیچ تیکتی با مشخصات جستجو شده یافت نشد.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- 📄 دکمه‌های صفحه‌بندی لاراول -->
        @if($tickets->hasPages())
            <div class="p-4 border-t border-slate-800/40 bg-slate-900/10 dir-ltr flex justify-center">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>

</div>
@endsection