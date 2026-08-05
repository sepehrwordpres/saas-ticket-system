<x-app-layout>
    <div class="max-w-xl mx-auto py-12 px-6">
        <div class="mb-8">
            <h2 class="text-sm font-bold text-white"> {{ __('departments.edit_title') }}: {{ $department->title[app()->getLocale()] ?? ($department->title['fa'] ?? '') }}</h2>
            <p class="text-[11px] text-slate-400 mt-1">   {{ __('departments.edit_description') }}     </p>
        </div>

        <form action="{{ route('admin.departments.update', $department->id) }}" method="POST" class="space-y-5 bg-slate-900/50 border border-slate-800 rounded-xl p-6 shadow-xl">
            @csrf
            @method('PUT')

            <div>
                <label for="title_fa" class="block text-xs font-medium text-slate-400 mb-2">{{ __('departments.fa_title') }}</label>
                <input type="text" name="title_fa" id="title_fa" value="{{ old('title_fa', $department->title['fa'] ?? '') }}" required
                       class="w-full bg-slate-950/50 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 placeholder-slate-600 focus:outline-none focus:border-amber-500 text-xs transition-all">
                @error('title_fa')
                    <p class="text-rose-400 text-[11px] mt-1 px-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="title_en" class="block text-xs font-medium text-slate-400 mb-2"> {{ __('departments.en_title') }} </label>
                <input type="text" name="title_en" id="title_en" value="{{ old('title_en', $department->title['en'] ?? '') }}" required dir="ltr"
                       class="w-full bg-slate-950/50 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 placeholder-slate-600 focus:outline-none focus:border-amber-500 text-xs font-mono transition-all">
                @error('title_en')
                    <p class="text-rose-400 text-[11px] mt-1 px-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="slug" class="block text-xs font-medium text-slate-400 mb-2"> {{ __('departments.slug_url') }}  </label>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $department->slug) }}" required dir="ltr"
                       class="w-full bg-slate-950/50 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 placeholder-slate-600 focus:outline-none focus:border-amber-500 text-xs font-mono transition-all">
                <p class="text-[10px] text-slate-500 mt-1.5">  {{ __('departments.slug_help') }}  </p>
                @error('slug')
                    <p class="text-rose-400 text-[11px] mt-1 px-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800/60">
                <a href="{{ route('admin.departments.index') }}" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-xs rounded-xl border border-slate-700 transition-all">
                                        {{ __('common.cancel') }}                </a>
                <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-amber-500 to-orange-600 text-slate-950 font-bold text-xs rounded-xl shadow-lg hover:from-amber-600 hover:to-orange-700 transition-all cursor-pointer">
                     {{ __('departments.update') }}  
                </button>
            </div>
        </form>
    </div>
</x-app-layout>