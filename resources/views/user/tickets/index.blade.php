@extends('layouts.app')

@section('content')
<div class="space-y-8">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-indigo-400 bg-clip-text text-transparent">
                {{ __('tickets.my_tickets') }}
            </h2>
            <p class="text-xs text-slate-400 mt-1">
                {{ __('tickets.my_tickets_subtitle') }}
            </p>
        </div>
        <a href="{{ route('user.tickets.create') }}" class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-bold text-sm px-6 py-3 rounded-xl shadow-lg transition-all text-center cursor-pointer">
            {{ __('tickets.new_ticket') }}
        </a>
    </div>

    <!-- 🔍 فیلترهای پیشرفته تیکت‌های کاربر -->
    <div class="glass-card rounded-2xl p-5 shadow-xl border border-slate-800/60 bg-slate-900/20">
        <form action="{{ route('user.tickets.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end text-xs">

            <!-- جستجو -->
            <div class="space-y-2">
                <label class="text-slate-400 font-medium block">
                    {{ __('tickets.search_tickets') }}
                </label>
                <input 
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="{{ __('tickets.search_placeholder') }}"
                    class="w-full bg-slate-950/60 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 placeholder-slate-600 focus:outline-none focus:border-blue-500/50 transition-colors"
                >
            </div>

            <!-- وضعیت -->
            <div class="space-y-2">
                <label class="text-slate-400 font-medium block">
                    {{ __('tickets.status') }}
                </label>
                <select 
                    name="status"
                    class="w-full bg-slate-950/60 border border-slate-800 rounded-xl px-4 py-3 text-slate-300 focus:outline-none focus:border-blue-500/50">
                    <option value="">{{ __('tickets.all_statuses') }}</option>
                    <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>
                        {{ __('tickets.status_open') }}
                    </option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                        {{ __('tickets.status_pending') }}
                    </option>
                    <option value="answered" {{ request('status') == 'answered' ? 'selected' : '' }}>
                        {{ __('tickets.status_answered') }}
                    </option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>
                        {{ __('tickets.status_closed') }}
                    </option>
                </select>
            </div>

            <!-- اولویت -->
            <div class="space-y-2">
                <label class="text-slate-400 font-medium block">
                    {{ __('tickets.priority') }}
                </label>
                <select 
                    name="priority"
                    class="w-full bg-slate-950/60 border border-slate-800 rounded-xl px-4 py-3 text-slate-300 focus:outline-none focus:border-blue-500/50">
                    <option value="">{{ __('tickets.all_priorities') }}</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>
                        {{ __('tickets.priority_low') }}
                    </option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>
                        {{ __('tickets.priority_medium') }}
                    </option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>
                        {{ __('tickets.priority_high') }}
                    </option>
                    <option value="critical" {{ request('priority') == 'critical' ? 'selected' : '' }}>
                        {{ __('tickets.priority_urgent') }}
                    </option>
                </select>
            </div>

            <!-- دپارتمان -->
            <div class="space-y-2">
                <label class="text-slate-400 font-medium block">
                    {{ __('tickets.department') }}
                </label>
                <select 
                    name="department_id"
                    class="w-full bg-slate-950/60 border border-slate-800 rounded-xl px-4 py-3 text-slate-300 focus:outline-none focus:border-blue-500/50">
                    <option value="">{{ __('tickets.all_departments') }}</option>
                    @foreach($departments as $dept)
                        <option 
                            value="{{ $dept->id }}"
                            {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->title[app()->getLocale()] ?? $dept->title['fa'] ?? __('tickets.unknown_department') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- دکمه‌ها -->
            <div class="flex gap-2">
                <button 
                    type="submit"
                    class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded-xl transition-all shadow-lg shadow-blue-500/10">
                    {{ __('tickets.apply_filter') }}
                </button>

                @if(request()->hasAny(['search','status','priority','department_id']))
                    <a 
                        href="{{ route('user.tickets.index') }}"
                        class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-4 py-3 rounded-xl transition-colors flex items-center">
                        {{ __('tickets.cancel') }}
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- جدول تیکت‌ها -->
    <div class="glass-card rounded-2xl overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse text-sm">
                <thead>
                    <tr class="border-b border-slate-800 bg-slate-900/40 text-slate-400">
                        <th class="p-4 font-semibold">{{ __('tickets.subject') }}</th>
                        <th class="p-4 font-semibold">{{ __('tickets.department') }}</th>
                        <th class="p-4 font-semibold">{{ __('tickets.priority') }}</th>
                        <th class="p-4 font-semibold">{{ __('tickets.status') }}</th>
                        <th class="p-4 font-semibold">{{ __('tickets.last_updated') }}</th>
                        <th class="p-4 font-semibold text-center">{{ __('tickets.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/40">
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-slate-800/20 transition-colors">
                            <td class="p-4 font-medium text-slate-200">
                                <span class="block max-w-xs truncate">{{ $ticket->title }}</span>
                            </td>
                            
                            <td class="p-4 text-slate-300">
                                {{ $ticket->department->title[app()->getLocale()] ?? $ticket->department->title['fa'] ?? __('tickets.unknown_department') }}
                            </td>
                            
                            <td class="p-4">
                                @if($ticket->priority === 'low')
                                    <span class="text-xs px-2 py-1 rounded-md bg-slate-500/10 text-slate-400 border border-slate-500/20">{{ __('tickets.priority_low') }}</span>
                                @elseif($ticket->priority === 'medium')
                                    <span class="text-xs px-2 py-1 rounded-md bg-blue-500/10 text-blue-400 border border-blue-500/20">{{ __('tickets.priority_medium') }}</span>
                                @elseif($ticket->priority === 'high')
                                    <span class="text-xs px-2 py-1 rounded-md bg-amber-500/10 text-amber-400 border border-amber-500/20">{{ __('tickets.priority_high') }}</span>
                                @elseif($ticket->priority === 'critical')
                                    <span class="text-xs px-2 py-1 rounded-md bg-rose-500/10 text-rose-400 border border-rose-500/20 animate-pulse">{{ __('tickets.priority_urgent') }}</span>
                                @endif
                            </td>
                            
                            <td class="p-4">
                                @if($ticket->status === 'new')
                                    <span class="text-xs px-2 py-1 rounded-full bg-teal-500/10 text-teal-400 border border-teal-500/30 shadow-[0_0_10px_rgba(20,184,166,0.1)]">{{ __('tickets.status_open') }}</span>
                                @elseif($ticket->status === 'pending')
                                    <span class="text-xs px-2 py-1 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/30 shadow-[0_0_10px_rgba(245,158,11,0.1)]">{{ __('tickets.status_pending') }}</span>
                                @elseif($ticket->status === 'answered')
                                    <span class="text-xs px-2 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 shadow-[0_0_10px_rgba(16,185,129,0.1)]">{{ __('tickets.status_answered') }}</span>
                                @elseif($ticket->status === 'closed')
                                    <span class="text-xs px-2 py-1 rounded-full bg-slate-700/20 text-slate-450 border border-slate-700/30">{{ __('tickets.status_closed') }}</span>
                                @endif
                            </td>
                            
                            <td class="p-4 text-slate-400 text-xs dir-ltr text-right">
                                {{ $ticket->updated_at->diffForHumans() }}
                            </td>
                            
                            <td class="p-4 text-center">
                                <a href="{{ route('user.tickets.show', $ticket->id) }}" class="inline-flex items-center justify-center text-xs bg-slate-800 hover:bg-slate-700 text-blue-400 font-semibold px-4 py-2 rounded-xl border border-slate-700 hover:border-slate-600 transition-all">
                                    {{ __('tickets.view_and_chat') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center text-slate-500">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <span class="text-lg">{{ __('tickets.no_tickets_found') }}</span>
                                    <p class="text-xs text-slate-600">{{ __('tickets.no_tickets_desc') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@if($tickets->hasPages())
<div class="p-4 border-t border-slate-800/40 bg-slate-900/10 dir-ltr flex justify-center">
    {{ $tickets->links() }}
</div>
@endif
@endsection