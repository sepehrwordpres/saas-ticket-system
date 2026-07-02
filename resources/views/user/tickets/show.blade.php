@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
    
    <div class="space-y-6">
        <div class="glass-card rounded-2xl p-6 shadow-xl sticky top-24">
            <h3 class="text-lg font-bold text-slate-200 mb-4 pb-3 border-b border-slate-800/60">اطلاعات تیکت</h3>
            
            <div class="space-y-4 text-sm">
                <div>
                    <span class="text-slate-400 block text-xs mb-1">موضوع تیکت:</span>
                    <strong class="text-slate-200">{{ $ticket->title }}</strong>
                </div>
                <div>
                    <span class="text-slate-400 block text-xs mb-1">کد پیگیری (UUID):</span>
                    <code class="text-xs bg-slate-950/60 px-2 py-1 rounded text-indigo-400 font-mono select-all">{{ $ticket->id }}</code>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-slate-400 block text-xs mb-1">دپارتمان:</span>
                        <span class="text-slate-300">{{ $ticket->department->title['fa'] ?? 'نامشخص' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-xs mb-1">اولویت:</span>
                        <span class="text-slate-300">{{ $ticket->priority }}</span>
                    </div>
                </div>
                <div>
                    <span class="text-slate-400 block text-xs mb-1">وضعیت فعلی:</span>
                    <span class="text-xs px-2 py-1 rounded-full bg-slate-800 text-slate-300 border border-slate-700">{{ $ticket->status }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-xs mb-1">تاریخ ثبت:</span>
                    <span class="text-slate-300 text-xs">{{ $ticket->created_at->diffForHumans() }}</span>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-800/60">
                <a href="{{ route('user.tickets.index') }}" class="w-full inline-flex items-center justify-center text-xs bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold py-2.5 rounded-xl border border-slate-700 transition-all">
                    بازگشت به لیست تیکت‌ها
                </a>
            </div>
        </div>
    </div>

    <div class="lg:col-span-2 space-y-6">
        
        <div class="glass-card rounded-2xl p-6 border-r-4 border-r-blue-500 shadow-xl">
            <div class="flex justify-between items-center mb-4 text-xs text-slate-400">
                <span class="font-bold text-slate-300">متن اولیه درخواست</span>
                <span>{{ $ticket->created_at->format('Y-m-d H:i') }}</span>
            </div>
            <p class="text-sm text-slate-200 leading-relaxed whitespace-pre-line mb-3">{{ $ticket->description }}</p>

            {{-- 💡 نمایش فایل ضمیمه اولیه تیکت (در صورت وجود) --}}
            @if($ticket->attachment)
                <div class="mt-4 pt-3 border-t border-slate-800/60 flex items-center">
                    <a href="{{ $ticket->attachment_url }}" target="_blank" 
                       class="inline-flex items-center gap-1.5 text-xs bg-slate-900/80 hover:bg-slate-950 px-3 py-1.5 rounded-lg text-blue-400 border border-slate-800 transition-all">
                        📁 مشاهده ضمیمه اولیه درخواست
                    </a>
                </div>
            @endif
        </div>

        <div class="space-y-4">
            @foreach($replies as $reply)
                {{-- 💡 جایگزینی تک عددی هاردکد شده قبلی با ساختار پویای auth()->id() --}}
                <div class="flex flex-col {{ $reply->user_id == auth()->id() ? 'items-end' : 'items-start' }}">
                    <div class="max-w-[85%] rounded-2xl p-4 shadow-md text-sm leading-relaxed whitespace-pre-line
                        {{ $reply->user_id == auth()->id() 
                            ? 'bg-blue-600/20 border border-blue-500/20 text-slate-200 rounded-tl-none' 
                            : 'bg-slate-800/60 border border-slate-700/40 text-slate-200 rounded-tr-none' }}">
                        
                        <div class="flex justify-between items-center gap-8 mb-2 text-[11px] text-slate-400">
                            <span class="font-bold {{ $reply->user_id == auth()->id() ? 'text-blue-400' : 'text-emerald-400' }}">
                                {{ $reply->user_id == auth()->id() ? 'شما (کاربر)' : 'پشتیبانی سایت' }}
                            </span>
                            <span class="dir-ltr">{{ $reply->created_at->diffForHumans() }}</span>
                        </div>
                        
                        <p class="text-slate-200">{{ $reply->message }}</p>

                        {{-- 💡 نمایش فایل پیوست هر پیام در چت --}}
                        @if($reply->attachment)
                            <div class="mt-3 pt-2 border-t border-slate-700/40 flex items-center">
                                <a href="{{ $reply->attachment_url }}" target="_blank" 
                                   class="inline-flex items-center gap-1.5 text-xs bg-slate-900/60 hover:bg-slate-950 px-3 py-1.5 rounded-lg text-amber-400 border border-slate-700/50 transition-all">
                                    📁 مشاهده فایل ضمیمه
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if($ticket->status !== 'closed')
            <div class="glass-card rounded-2xl p-4 shadow-xl">
                {{-- 💡 اضافه شدن enctype برای پشتیبانی فرم از آپلود فایل پیوست --}}
                <form action="{{ route('user.tickets.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    
                    {{-- 💡 فیلد انتخاب فایل فشرده و کوچک برای باکس چت کلاینت --}}
                    <div class="bg-slate-950/20 border border-slate-800/60 rounded-xl p-2.5">
                        <label for="attachment" class="text-xs text-slate-400 block font-medium mb-1">ارسال فایل ضمیمه (اختیاری):</label>
                        <input type="file" name="attachment" id="attachment"
                               class="block w-full text-xs text-slate-500 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-slate-300 hover:file:bg-slate-700 file:cursor-pointer cursor-pointer">
     
                    </div>

                    <div class="flex gap-4 items-end">
                        <div class="flex-grow">
                            <textarea name="message" rows="2" required
                                      class="w-full bg-slate-950/50 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all text-sm resize-none"
                                      placeholder="پاسخ خود را بنویسید..."></textarea>
                        </div>
                        <div>
                            <button type="submit" class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-bold text-sm px-6 py-3 rounded-xl shadow-lg hover:from-blue-600 hover:to-indigo-700 transition-all cursor-pointer whitespace-nowrap">
                                ارسال پیام
                            </button>
                        </div>
                    </div>
                </form>
                @error('message')
                    <p class="text-rose-400 text-xs mt-1 px-2">{{ $message }}</p>
                @enderror
            </div>
        @else
            <div class="p-4 rounded-xl bg-slate-800/40 border border-slate-700/50 text-slate-400 text-center text-sm">
                🔒 این تیکت بسته شده است و امکان ارسال پاسخ جدید وجود ندارد.
            </div>
        @endif

    </div>
</div>
@endsection