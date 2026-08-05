<x-app-layout>
    <div class="max-w-xl mx-auto py-12 px-6">
        <div class="mb-8">
            <h2 class="text-sm font-bold text-white">{{ __('supports.edit_title', ['name' => $support->name]) }}</h2>
            <p class="text-[11px] text-slate-400 mt-1">{{ __('supports.edit_subtitle') }}</p>
        </div>

        <form action="{{ route('admin.supports.update', $support->id) }}" method="POST" class="space-y-5 bg-slate-900/50 border border-slate-800 rounded-xl p-6 shadow-xl">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-xs font-medium text-slate-400 mb-2">{{ __('supports.name_label') }}</label>
                <input type="text" name="name" id="name" value="{{ old('name', $support->name) }}" required
                       class="w-full bg-slate-950/50 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 placeholder-slate-600 focus:outline-none focus:border-amber-500 text-xs transition-all">
                @error('name')
                    <p class="text-rose-400 text-[11px] mt-1 px-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-xs font-medium text-slate-400 mb-2">{{ __('supports.email_label') }}</label>
                <input type="email" name="email" id="email" value="{{ old('email', $support->email) }}" required dir="ltr"
                       class="w-full bg-slate-950/50 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 placeholder-slate-600 focus:outline-none focus:border-amber-500 text-xs font-mono transition-all">
                @error('email')
                    <p class="text-rose-400 text-[11px] mt-1 px-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-2 border-t border-slate-800/60">
                <label for="password" class="block text-xs font-medium text-amber-400 mb-1">{{ __('supports.new_password_label') }}</label>
                <p class="text-[10px] text-slate-500 mb-2">{{ __('supports.new_password_help') }}</p>
                <input type="password" name="password" id="password" dir="ltr"
                       class="w-full bg-slate-950/50 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 placeholder-slate-600 focus:outline-none focus:border-amber-500 text-xs font-mono transition-all">
                @error('password')
                    <p class="text-rose-400 text-[11px] mt-1 px-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-medium text-slate-400 mb-2">{{ __('supports.new_password_confirmation_label') }}</label>
                <input type="password" name="password_confirmation" id="password_confirmation" dir="ltr"
                       class="w-full bg-slate-950/50 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 placeholder-slate-600 focus:outline-none focus:border-amber-500 text-xs font-mono transition-all">
            </div>

            <div class="pt-4 border-t border-slate-800/60">
                <label class="block text-xs font-medium text-purple-400 mb-3">🏢 {{ __('supports.allowed_departments') }}</label>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($departments as $dept)
                        <label class="flex items-center gap-3 p-3 bg-slate-950/40 border border-slate-800/80 rounded-xl cursor-pointer select-none hover:border-slate-700 transition-all">
                            <input type="checkbox" name="departments[]" value="{{ $dept->id }}"
                                   {{ in_array($dept->id, old('departments', $assignedDepartments)) ? 'checked' : '' }}
                                   class="w-4 h-4 rounded border-slate-800 bg-slate-950 text-amber-500 focus:ring-amber-500">
                            <span class="text-xs text-slate-300">
                                {{ is_array($dept->title) ? ($dept->title[app()->getLocale()] ?? current($dept->title)) : $dept->title }}
                            </span>
                        </label>
                    @endforeach
                </div>
                @error('departments')
                    <p class="text-rose-400 text-[11px] mt-2 px-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800/60">
                <a href="{{ route('admin.supports.index') }}" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-xs rounded-xl border border-slate-700 transition-all">
                    {{ __('supports.cancel') }}
                </a>
                <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-amber-500 to-orange-600 text-slate-950 font-bold text-xs rounded-xl shadow-lg hover:from-amber-600 hover:to-orange-700 transition-all cursor-pointer">
                    {{ __('supports.submit_update') }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>