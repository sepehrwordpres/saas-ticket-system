@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
    
    {{-- اطلاعات جانبی تیکت (سایدبار) --}}
    <div class="space-y-6">
        <div class="glass-card rounded-2xl p-6 shadow-xl sticky top-24">
            <h3 class="text-lg font-bold text-slate-200 mb-4 pb-3 border-b border-slate-800/60">
                {{ __('tickets.ticket_details') }}
            </h3>
            
            <div class="space-y-4 text-sm">
                <div>
                    <span class="text-slate-400 block text-xs mb-1">{{ __('tickets.subject') }}:</span>
                    <strong class="text-slate-200">{{ $ticket->title }}</strong>
                </div>
                <div>
                    <span class="text-slate-400 block text-xs mb-1">{{ __('tickets.ticket_id') }}:</span>
                    <code class="text-xs bg-slate-950/60 px-2 py-1 rounded text-indigo-400 font-mono select-all inline-block">{{ $ticket->id }}</code>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-slate-400 block text-xs mb-1">{{ __('tickets.department') }}:</span>
                        <span class="text-slate-300">
                            {{ is_array($ticket->department?->title) ? ($ticket->department->title[app()->getLocale()] ?? reset($ticket->department->title)) : __($ticket->department?->title ?? 'tickets.general_support') }}
                        </span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-xs mb-1">{{ __('tickets.priority') }}:</span>
                        <span class="text-slate-300">
                            {{ __('tickets.priority_' . strtolower($ticket->priority)) }}
                        </span>
                    </div>
                </div>
                <div>
                    <span class="text-slate-400 block text-xs mb-1">{{ __('tickets.status') }}:</span>
                    <span class="text-xs px-2.5 py-1 rounded-full bg-slate-800 text-slate-300 border border-slate-700 font-medium">
                        {{ __('tickets.status_' . strtolower($ticket->status)) }}
                    </span>
                </div>
                <div>
                    <span class="text-slate-400 block text-xs mb-1">{{ __('tickets.created_at') }}:</span>
                    <span class="text-slate-300 text-xs">
                        {{ method_exists($ticket->created_at, 'toJalali') ? $ticket->created_at->toJalali()->format('Y/m/d H:i') : $ticket->created_at->diffForHumans() }}
                    </span>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-800/60">
                <a href="{{ route('user.tickets.index') }}" class="w-full inline-flex items-center justify-center text-xs bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold py-2.5 rounded-xl border border-slate-700 transition-all">
                    {{ __('tickets.back_to_list') }}
                </a>
            </div>
        </div>
    </div>

    {{-- محتوای گفتگو و فرم ارسال پاسخ --}}
    <div class="lg:col-span-2 space-y-6">
        
        {{-- پیام اولیه درخواست --}}
        <div class="glass-card rounded-2xl p-6 border-r-4 border-r-blue-500 shadow-xl">
            <div class="flex justify-between items-center mb-4 text-xs text-slate-400">
                <span class="font-bold text-slate-300">{{ __('tickets.initial_request') }}</span>
                <span>
                    {{ method_exists($ticket->created_at, 'toJalali') ? $ticket->created_at->toJalali()->format('Y/m/d H:i') : $ticket->created_at->format('Y/m/d H:i') }}
                </span>
            </div>
            <p class="text-sm text-slate-200 leading-relaxed whitespace-pre-line mb-3">{{ $ticket->description }}</p>

            @if(!empty($ticket->attachment))
                <div class="mt-4 pt-3 border-t border-slate-800/60 flex items-center">
                    <a href="{{ $ticket->attachment_url ?? asset('storage/' . $ticket->attachment) }}" target="_blank" 
                       class="inline-flex items-center gap-1.5 text-xs bg-slate-900/80 hover:bg-slate-950 px-3 py-1.5 rounded-lg text-blue-400 border border-slate-800 transition-all">
                        📁 {{ __('tickets.view_initial_attachment') }}
                    </a>
                </div>
            @endif
        </div>

        {{-- لیست پاسخ‌ها --}}
        <div class="space-y-4">
            @foreach($replies as $reply)
                @php $isUser = ($reply->user_id == auth()->id()); @endphp
                <div class="flex flex-col {{ $isUser ? 'items-end' : 'items-start' }}">
                    <div class="max-w-[85%] rounded-2xl p-4 shadow-md text-sm leading-relaxed whitespace-pre-line
                        {{ $isUser 
                            ? 'bg-blue-600/20 border border-blue-500/20 text-slate-200 rounded-tl-none' 
                            : 'bg-slate-800/60 border border-slate-700/40 text-slate-200 rounded-tr-none' }}">
                        
                        <div class="flex justify-between items-center gap-8 mb-2 text-[11px] text-slate-400">
                            <span class="font-bold {{ $isUser ? 'text-blue-400' : 'text-emerald-400' }}">
                                {{ $isUser ? __('tickets.you_user') : ($reply->user->name ?? __('tickets.support_team')) }}
                            </span>
                            <span>
                                {{ method_exists($reply->created_at, 'toJalali') ? $reply->created_at->toJalali()->format('H:i - Y/m/d') : $reply->created_at->diffForHumans() }}
                            </span>
                        </div>
                        
                        <p class="text-slate-200">{{ $reply->message }}</p>

                        @if(!empty($reply->attachment))
                            <div class="mt-3 pt-2 border-t border-slate-700/40 flex items-center">
                                <a href="{{ $reply->attachment_url ?? asset('storage/' . $reply->attachment) }}" target="_blank" 
                                   class="inline-flex items-center gap-1.5 text-xs bg-slate-900/60 hover:bg-slate-950 px-3 py-1.5 rounded-lg text-amber-400 border border-slate-700/50 transition-all">
                                    📁 {{ __('tickets.view_attachment') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- فرم ارسال پاسخ --}}
        @if(strtolower($ticket->status) !== 'closed')
            <div class="glass-card rounded-2xl p-4 shadow-xl">
                <form action="{{ route('user.tickets.reply', $ticket->id) }}" 
                      method="POST" 
                      enctype="multipart/form-data" 
                      class="space-y-4"
                      onsubmit="this.querySelector('button[type=submit]').disabled = true;">
                    @csrf
                    
                    <div class="bg-slate-950/20 border border-slate-800/60 rounded-xl p-2.5">
                        <label for="attachment" class="text-xs text-slate-400 block font-medium mb-1">
                            {{ __('tickets.attachment_optional') }}:
                        </label>
                        <input type="file" name="attachment" id="attachment"
                               class="block w-full text-xs text-slate-500 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-slate-300 hover:file:bg-slate-700 file:cursor-pointer cursor-pointer">
                        @error('attachment')
                            <p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-4 items-end">
                        <div class="flex-grow">
                            <textarea name="message" rows="2" required
                                      class="w-full bg-slate-950/50 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all text-sm resize-none"
                                      placeholder="{{ __('tickets.reply_placeholder') }}"></textarea>
                            @error('message')
                                <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <button type="submit" class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-bold text-sm px-6 py-3 rounded-xl shadow-lg hover:from-blue-600 hover:to-indigo-700 disabled:opacity-50 transition-all cursor-pointer whitespace-nowrap">
                                {{ __('tickets.send_reply') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        @else
            <div class="p-4 rounded-xl bg-slate-800/40 border border-slate-700/50 text-slate-400 text-center text-sm">
                🔒 {{ __('tickets.ticket_closed_notice') }}
            </div>
        @endif

    </div>
</div>
@endsection