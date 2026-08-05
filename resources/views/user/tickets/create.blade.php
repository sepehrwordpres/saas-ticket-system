@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-indigo-400 bg-clip-text text-transparent">{{ __('tickets.create_title') }}</h2>
            <p class="text-xs text-slate-400 mt-1">{{ __('tickets.create_subtitle') }}</p>
        </div>
        <a href="{{ route('user.tickets.index') }}" class="text-sm text-slate-400 hover:text-slate-200 border border-slate-700 hover:border-slate-500 rounded-xl px-4 py-2 transition-all">
            {{ __('tickets.back_to_list') }}
        </a>
    </div>

    <div class="glass-card rounded-2xl p-6 md:p-8 shadow-2xl">
        <form action="{{ route('user.tickets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label for="title" class="block text-sm font-medium text-slate-300 mb-2">{{ __('tickets.title') }}</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" 
                       class="w-full bg-slate-950/50 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all text-sm"
                       placeholder="{{ __('tickets.title_placeholder') }}">
                @error('title')
                    <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div>
                    <label for="department_id" class="block text-sm font-medium text-slate-300 mb-2">{{ __('tickets.department') }}</label>
                    <select name="department_id" id="department_id" 
                            class="w-full bg-slate-950/50 border border-slate-800 rounded-xl px-4 py-3 text-slate-300 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all text-sm">
                        <option value="">{{ __('tickets.select_department') }}</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                {{ is_array($department->title) ? ($department->title[app()->getLocale()] ?? current($department->title)) : ($department->title ?? __('tickets.unknown_department')) }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="priority" class="block text-sm font-medium text-slate-300 mb-2">{{ __('tickets.priority') }}</label>
                    <select name="priority" id="priority" 
                            class="w-full bg-slate-950/50 border border-slate-800 rounded-xl px-4 py-3 text-slate-300 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all text-sm">
                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>{{ __('tickets.priorities.low') }}</option>
                        <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>{{ __('tickets.priorities.medium') }}</option>
                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>{{ __('tickets.priorities.high') }}</option>
                        <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>{{ __('tickets.priorities.critical') }}</option>
                    </select>
                    @error('priority')
                        <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-slate-300 mb-2">{{ __('tickets.description') }}</label>
                <textarea name="description" id="description" rows="6" 
                          class="w-full bg-slate-950/50 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all text-sm resize-none"
                          placeholder="{{ __('tickets.description_placeholder') }}">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-slate-950/30 border border-slate-800/80 rounded-xl p-4 flex flex-col gap-1">
                <label for="attachment" class="text-sm font-medium text-slate-300 block mb-1">{{ __('tickets.attachment') }}</label>
                <input type="file" name="attachment" id="attachment"
                       class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-slate-200 hover:file:bg-slate-700 file:cursor-pointer cursor-pointer">
                <p class="text-[11px] text-slate-500 mt-1">{{ __('tickets.attachment_help') }}</p>
                @error('attachment')
                    <p class="text-rose-400 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="w-full md:w-auto bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl px-8 py-3 text-sm font-bold shadow-lg hover:from-blue-600 hover:to-indigo-700 transition-all cursor-pointer">
                    {{ __('tickets.submit') }}
                </button>
            </div>

        </form>
    </div>

</div>
@endsection