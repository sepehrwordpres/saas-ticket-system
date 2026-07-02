<x-app-layout>
    <div class="max-w-xl mx-auto py-12 px-6">
        <div class="mb-6">
            <h2 class="text-sm font-bold text-white">ایجاد دپارتمان سازمانی جدید</h2>
            <p class="text-[11px] text-slate-400 mt-1">عنوان دپارتمان را برای تفکیک تیکت‌ها وارد کنید.</p>
        </div>

        <form method="POST" action="{{ route('admin.departments.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">عنوان دپارتمان (فارسی)</label>
                <input type="text" name="title_fa" placeholder="مثلا: پشتیبانی فنی" required class="w-full bg-slate-900 border border-slate-800 text-slate-200 text-xs rounded-lg p-2.5 focus:border-indigo-500 focus:ring-0 transition-colors">
                @error('title_fa')
                    <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">عنوان دپارتمان (English)</label>
                <input type="text" name="title_en" placeholder="e.g. Technical Support" required class="w-full bg-slate-900 border border-slate-800 text-slate-200 text-xs rounded-lg p-2.5 focus:border-indigo-500 focus:ring-0 transition-colors text-left" dir="ltr">
                @error('title_en')
                    <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1.5">شناسه یکتا (Slug)</label>
                <input type="text" name="slug" placeholder="tech-support" required class="w-full bg-slate-900 border border-slate-800 text-slate-200 text-xs rounded-lg p-2.5 focus:border-indigo-500 focus:ring-0 transition-colors text-left" dir="ltr">
                @error('slug')
                    <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-2 flex justify-end">
                <button type="submit" class="px-5 py-2 bg-white text-slate-950 font-semibold text-xs rounded-lg hover:bg-slate-200 transition-all">
                    ثبت و ذخیره دپارتمان
                </button>
            </div>
        </form>
    </div>
</x-app-layout>