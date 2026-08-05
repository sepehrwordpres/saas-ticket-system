<x-app-layout>
    <div class="max-w-xl mx-auto py-12 px-6">
        <div class="mb-6">
            <h2 class="text-sm font-bold text-white">{{ __('supports.create_title') }}</h2>
            <p class="text-[11px] text-slate-400 mt-1">{{ __('supports.create_subtitle') }}</p>
        </div>

        <form method="POST" action="{{ route('admin.supports.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">{{ __('supports.name_label') }}</label>
                <input type="text" name="name" placeholder="{{ __('supports.name_placeholder') }}" required value="{{ old('name') }}" class="w-full bg-slate-900 border border-slate-800 text-slate-200 text-xs rounded-lg p-2.5 focus:border-indigo-500 focus:ring-0 transition-colors">
                @error('name')
                    <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">{{ __('supports.email_label') }}</label>
                <input type="email" name="email" placeholder="ali@example.com" required value="{{ old('email') }}" class="w-full bg-slate-900 border border-slate-800 text-slate-200 text-xs rounded-lg p-2.5 focus:border-indigo-500 focus:ring-0 transition-colors text-left" dir="ltr">
                @error('email')
                    <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-slate-950/40 border border-slate-800/80 rounded-xl p-4 space-y-3">
                <label class="block text-xs font-bold text-purple-400 mb-1">{{ __('supports.allowed_departments') }}</label>
                <p class="text-[10px] text-slate-500 mb-2">{{ __('supports.allowed_departments_help') }}</p>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($departments as $department)
                        <label class="flex items-center gap-3 bg-slate-900/60 border border-slate-800 p-2.5 rounded-lg cursor-pointer hover:border-purple-500/50 transition-all select-none">
                            <input type="checkbox" name="departments[]" value="{{ $department->id }}" 
                                   {{ is_array(old('departments')) && in_array($department->id, old('departments')) ? 'checked' : '' }}
                                   class="w-4 h-4 rounded border-slate-700 bg-slate-950 text-purple-600 focus:ring-purple-500/30">
                            <span class="text-xs text-slate-300">
                                {{ is_array($department->title) ? ($department->title[app()->getLocale()] ?? current($department->title)) : $department->title }}
                            </span>
                        </label>
                    @endforeach
                </div>
                
                @error('departments')
                    <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">{{ __('supports.password_label') }}</label>
                <input type="password" name="password" placeholder="••••••••" required class="w-full bg-slate-900 border border-slate-800 text-slate-200 text-xs rounded-lg p-2.5 focus:border-indigo-500 focus:ring-0 transition-colors text-left" dir="ltr">
                @error('password')
                    <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">{{ __('supports.password_confirmation_label') }}</label>
                <input type="password" name="password_confirmation" placeholder="••••••••" required class="w-full bg-slate-900 border border-slate-800 text-slate-200 text-xs rounded-lg p-2.5 focus:border-indigo-500 focus:ring-0 transition-colors text-left" dir="ltr">
            </div>

            <div class="pt-2 flex justify-end">
                <button type="submit" class="px-5 py-2 bg-white text-slate-950 font-semibold text-xs rounded-lg hover:bg-slate-200 transition-all">
                    {{ __('supports.submit_create') }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>