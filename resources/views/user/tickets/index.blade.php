@extends('layouts.app')

@section('content')
<div class="space-y-8">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-indigo-400 bg-clip-text text-transparent">تیکت‌های پشتیبانی من</h2>
            <p class="text-xs text-slate-400 mt-1">لیست تمام درخواست‌های ارسال شده و وضعیت بررسی آن‌ها</p>
        </div>
        <a href="{{ route('user.tickets.create') }}" class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-bold text-sm px-6 py-3 rounded-xl shadow-lg transition-all text-center cursor-pointer">
            ارسال تیکت جدید
        </a>
    </div>

    <div class="glass-card rounded-2xl overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse text-sm">
                <thead>
                    <tr class="border-b border-slate-800 bg-slate-900/40 text-slate-400">
                        <th class="p-4 font-semibold">موضوع تیکت</th>
                        <th class="p-4 font-semibold">دپارتمان</th>
                        <th class="p-4 font-semibold">اولویت</th>
                        <th class="p-4 font-semibold">وضعیت</th>
                        <th class="p-4 font-semibold">آخرین بروزرسانی</th>
                        <th class="p-4 font-semibold text-center">عملیات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/40">
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-slate-800/20 transition-colors">
                            <td class="p-4 font-medium text-slate-200">
                                <span class="block max-w-xs truncate">{{ $ticket->title }}</span>
                            </td>
                            
                            <td class="p-4 text-slate-300">
                                {{ $ticket->department->title['fa'] ?? 'بخش نامشخص' }}
                            </td>
                            
                            <td class="p-4">
                                @if($ticket->priority === 'low')
                                    <span class="text-xs px-2 py-1 rounded-md bg-slate-500/10 text-slate-400 border border-slate-500/20">کم</span>
                                @elseif($ticket->priority === 'medium')
                                    <span class="text-xs px-2 py-1 rounded-md bg-blue-500/10 text-blue-400 border border-blue-500/20">متوسط</span>
                                @elseif($ticket->priority === 'high')
                                    <span class="text-xs px-2 py-1 rounded-md bg-amber-500/10 text-amber-400 border border-amber-500/20">بالا</span>
                                @elseif($ticket->priority === 'critical')
                                    <span class="text-xs px-2 py-1 rounded-md bg-rose-500/10 text-rose-400 border border-rose-500/20 animate-pulse">بحرانی</span>
                                @endif
                            </td>
                            
                            <td class="p-4">
                                @if($ticket->status === 'new')
                                    <span class="text-xs px-2 py-1 rounded-full bg-teal-500/10 text-teal-400 border border-teal-500/30 shadow-[0_0_10px_rgba(20,184,166,0.1)]">جدید</span>
                                @elseif($ticket->status === 'pending')
                                    <span class="text-xs px-2 py-1 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/30 shadow-[0_0_10px_rgba(245,158,11,0.1)]">در انتظار بررسی</span>
                                @elseif($ticket->status === 'answered')
                                    <span class="text-xs px-2 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 shadow-[0_0_10px_rgba(16,185,129,0.1)]">پاسخ داده شده</span>
                                @elseif($ticket->status === 'closed')
                                    <span class="text-xs px-2 py-1 rounded-full bg-slate-700/20 text-slate-450 border border-slate-700/30">بسته شده</span>
                                @endif
                            </td>
                            
                            <td class="p-4 text-slate-400 text-xs dir-ltr text-right">
                                {{ $ticket->updated_at->diffForHumans() }}
                            </td>
                            
                            <td class="p-4 text-center">
                                <a href="{{ route('user.tickets.show', $ticket->id) }}" class="inline-flex items-center justify-center text-xs bg-slate-800 hover:bg-slate-700 text-blue-400 font-semibold px-4 py-2 rounded-xl border border-slate-700 hover:border-slate-600 transition-all">
                                    مشاهده و گفتگو
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center text-slate-500">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <span class="text-lg">هیچ تیکتی یافت نشد.</span>
                                    <p class="text-xs text-slate-600">در صورت بروز هرگونه مشکل، می‌توانید یک تیکت جدید ثبت کنید.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection