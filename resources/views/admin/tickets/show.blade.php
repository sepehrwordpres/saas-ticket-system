@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
    
    <div class="space-y-6">
        <div class="glass-card rounded-2xl p-6 shadow-xl sticky top-24">
            <h3 class="text-lg font-bold text-slate-200 mb-4 pb-3 border-b border-slate-800/60">{{ __('admin_ticket_show.sidebar_title') }}</h3>
            
            <div class="space-y-3 text-xs mb-6">
                <div>
                    <span class="text-slate-400 block mb-1">{{ __('admin_ticket_show.label_subject') }}:</span>
                    <strong class="text-slate-200 text-sm">{{ $ticket->title }}</strong>
                </div>
                <div>
                    <span class="text-slate-400 block mb-1">{{ __('admin_ticket_show.label_user') }}:</span>
                    <strong class="text-slate-300 text-sm">{{ $ticket->user?->name ?? __('admin_ticket_show.unknown_user') }}</strong>
                </div>
                <div>
                    <span class="text-slate-400 block mb-1">{{ __('admin_ticket_show.label_uuid') }}:</span>
                    <code class="bg-slate-950/60 px-2 py-1 rounded text-amber-400 font-mono select-all">{{ $ticket->id }}</code>
                </div>
                <div>
                    <span class="text-slate-400 block mb-1">{{ __('admin_ticket_show.label_status') }}:</span>
                    <span class="px-2 py-0.5 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20 font-bold">
                        {{ __('admin_tickets.status_' . $ticket->status) }}
                    </span>
                </div>
                <div>
                    <span class="text-slate-400 block mb-1">{{ __('admin_ticket_show.label_agent') }}:</span>
                    @if($ticket->support)
                        <span class="px-2 py-0.5 rounded-full bg-blue-500/10 text-blue-400 border border-blue-500/20 font-bold">
                            {{ $ticket->support->name }}
                        </span>
                    @else
                        <span class="px-2 py-0.5 rounded-full bg-rose-500/10 text-rose-400 border border-rose-500/20 font-bold">
                            {{ __('admin_ticket_show.unassigned') }}
                        </span>
                    @endif
                </div>
            </div>

            @can('super-admin')
                <form action="{{ route('admin.tickets.assign', $ticket->id) }}" method="POST" class="space-y-4 pt-4 border-t border-slate-800/60 mb-4">
                    @csrf
                    
                    <div>
                        <label for="department_id" class="block text-xs font-medium text-purple-400 mb-2">{{ __('admin_ticket_show.field_department') }}</label>
                        <select name="department_id" id="department_id" required
                                class="w-full bg-slate-950/50 border border-slate-800 rounded-xl px-3 py-2.5 text-slate-300 focus:outline-none focus:border-purple-500 text-xs">
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ $ticket->department_id == $dept->id ? 'selected' : '' }}>
                                    {{ is_array($dept->title) ? ($dept->title[app()->getLocale()] ?? $dept->title['fa'] ?? current($dept->title)) : $dept->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="support_id" class="block text-xs font-medium text-purple-400 mb-2">{{ __('admin_ticket_show.field_assigned_agent') }}</label>
                        <select name="support_id" id="support_id" 
                                class="w-full bg-slate-950/50 border border-slate-800 rounded-xl px-3 py-2.5 text-slate-300 focus:outline-none focus:border-purple-500 text-xs">
                            <option value="" {{ !$ticket->support_id ? 'selected' : '' }}>{{ __('admin_ticket_show.no_agent_option') }}</option>
                            @foreach($supports as $support)
                                <option value="{{ $support->id }}" {{ $ticket->support_id == $support->id ? 'selected' : '' }}>
                                    {{ $support->name }} ({{ $support->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold py-2.5 rounded-xl transition-all cursor-pointer shadow-lg shadow-purple-600/10">
                        {{ __('admin_ticket_show.btn_submit_assignment') }}
                    </button>
                </form>
            @endcan

            <form action="{{ route('admin.tickets.status', $ticket->id) }}" method="POST" class="space-y-4 pt-4 border-t border-slate-800/60">
                @csrf
                @method('PATCH')
                <div>
                    <label for="status" class="block text-xs font-medium text-slate-400 mb-2">{{ __('admin_ticket_show.field_change_status') }}</label>
                    <select name="status" id="status" 
                            class="w-full bg-slate-950/50 border border-slate-800 rounded-xl px-3 py-2.5 text-slate-300 focus:outline-none focus:border-amber-500 text-xs">
                        <option value="new" {{ $ticket->status == 'new' ? 'selected' : '' }}>{{ __('admin_tickets.status_new') }}</option>
                        <option value="pending" {{ $ticket->status == 'pending' ? 'selected' : '' }}>{{ __('admin_tickets.status_pending') }}</option>
                        <option value="answered" {{ $ticket->status == 'answered' ? 'selected' : '' }}>{{ __('admin_tickets.status_answered') }}</option>
                        <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>{{ __('admin_tickets.status_closed') }}</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-bold py-2.5 rounded-xl transition-all cursor-pointer shadow-lg shadow-amber-500/10">
                    {{ __('admin_ticket_show.btn_update_status') }}
                </button>
            </form>

            <div class="mt-4">
                <a href="{{ route('admin.tickets.index') }}" class="w-full inline-flex items-center justify-center text-xs bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold py-2.5 rounded-xl border border-slate-700 transition-all">
                    {{ __('admin_ticket_show.btn_back_to_desk') }}
                </a>
            </div>
        </div>
    </div>

    <div class="lg:col-span-2 space-y-6">
        
        <!-- درخواست اولیه -->
        <div class="glass-card rounded-2xl p-6 border-r-4 border-r-amber-500 shadow-xl bg-slate-900/20 border border-slate-800/50">
            <div class="flex justify-between items-center mb-4 text-xs text-slate-400">
                <span class="font-bold text-slate-300">{{ __('admin_ticket_show.initial_request_title', ['name' => $ticket->user?->name ?? __('admin_ticket_show.default_user_name')]) }}</span>
                <span dir="ltr">{{ $ticket->created_at->format('Y-m-d H:i') }}</span>
            </div>
            <p class="text-sm text-slate-200 leading-relaxed whitespace-pre-line">{{ $ticket->description }}</p>

            @if($ticket->attachment) 
                <div class="mt-4 pt-3 border-t border-slate-800/60 flex items-center">
                    <a href="{{ $ticket->attachment_url }}" target="_blank" 
                       class="inline-flex items-center gap-1.5 text-xs bg-amber-500 hover:bg-amber-600 px-4 py-2 rounded-xl text-slate-950 font-bold transition-all shadow-lg shadow-amber-500/10">
                        📁 {{ __('admin_ticket_show.view_initial_attachment') }}
                    </a>
                </div>
            @endif
        </div>

        <!-- لیست گفتگوها -->
        <div class="space-y-4">
            @foreach($replies as $reply)
                <div class="flex flex-col {{ $reply->user_id == $ticket->user_id ? 'items-start' : 'items-end' }} w-full">
                    
                    <div class="max-w-[85%] rounded-2xl p-4 shadow-md text-sm leading-relaxed whitespace-pre-line
                        @if($reply->is_internal)
                            bg-purple-600/20 border border-purple-500/40 text-slate-200 rounded-tl-none shadow-[0_0_15px_rgba(147,51,234,0.05)]
                        @else
                            {{ $reply->user_id == $ticket->user_id 
                                ? 'bg-slate-800/60 border border-slate-700/40 text-slate-200 rounded-tr-none' 
                                : 'bg-amber-500/10 border border-amber-500/20 text-slate-200 rounded-tl-none' }}
                        @endif">
                        
                        <div class="flex justify-between items-center gap-8 mb-2 text-[11px] text-slate-400">
                            <span class="font-bold 
                                @if($reply->is_internal) text-purple-400
                                @else {{ $reply->user_id == $ticket->user_id ? 'text-blue-400' : 'text-amber-400' }} @endif">
                                
                                @if($reply->is_internal)
                                    🔒 {{ __('admin_ticket_show.internal_note_badge') }}
                                @else
                                    {{ $reply->user_id == $ticket->user_id 
                                        ? __('admin_ticket_show.reply_author_user', ['name' => $ticket->user?->name ?? __('admin_ticket_show.default_user_name')]) 
                                        : __('admin_ticket_show.reply_author_support', ['name' => $reply->user?->name ?? __('admin_ticket_show.default_agent_name')]) }}
                                @endif
                            </span>
                            <span class="dir-ltr">{{ $reply->created_at->diffForHumans() }}</span>
                        </div>
                        
                        <p class="text-slate-200">{{ $reply->message }}</p>

                        @if($reply->attachment)
                            <div class="mt-3 pt-2 border-t border-slate-700/40 flex items-center">
                                <a href="{{ $reply->attachment_url }}" target="_blank" 
                                   class="inline-flex items-center gap-1.5 text-xs bg-slate-900/60 hover:bg-slate-950 px-3 py-1.5 rounded-lg text-amber-400 border border-slate-700/50 transition-all">
                                    📁 {{ __('admin_ticket_show.view_attachment') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- فرم ارسال پاسخ -->
        <div class="glass-card rounded-2xl p-4 shadow-xl">
            <form action="{{ route('admin.tickets.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <textarea name="message" rows="3" required
                              class="w-full bg-slate-950/50 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 placeholder-slate-600 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all text-sm resize-none"
                              placeholder="{{ __('admin_ticket_show.reply_placeholder') }}"></textarea>
                </div>
                
                <div class="bg-slate-950/30 border border-slate-800/80 rounded-xl p-3 flex flex-col gap-1">
                    <label for="attachment" class="text-xs text-slate-400 block font-medium mb-1">{{ __('admin_ticket_show.attachment_label') }}</label>
                    <input type="file" name="attachment" id="attachment"
                           class="block w-full text-xs text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-slate-200 hover:file:bg-slate-700 file:cursor-pointer cursor-pointer">
                    @error('attachment')
                        <p class="text-rose-400 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                        <input type="hidden" name="is_internal" value="0">
                        <input type="checkbox" name="is_internal" value="1" class="w-4 h-4 rounded border-slate-800 bg-slate-950 text-amber-500 focus:ring-amber-500">
                        <span class="text-xs text-slate-400">{!! __('admin_ticket_show.internal_note_checkbox_html') !!}</span>
                    </label>

                    <button type="submit" class="bg-gradient-to-r from-amber-500 to-orange-600 text-slate-950 font-bold text-sm px-6 py-2.5 rounded-xl shadow-lg hover:from-amber-600 hover:to-orange-700 transition-all cursor-pointer whitespace-nowrap">
                        {{ __('admin_ticket_show.btn_submit_reply') }}
                    </button>
                </div>
            </form>
            @error('message')
                <p class="text-rose-400 text-xs mt-1 px-2">{{ $message }}</p>
            @enderror
        </div>

    </div>
</div>
@endsection