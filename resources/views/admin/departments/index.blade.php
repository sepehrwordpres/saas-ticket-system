<x-app-layout>
    <div class="max-w-4xl mx-auto py-12 px-6">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-sm font-bold text-white">مدیریت دپارتمان‌ها</h2>
                <p class="text-[11px] text-slate-400 mt-1">لیست بخش‌های فعال سازمان برای دسته‌بندی تیکت‌ها.</p>
            </div>
            <a href="{{ route('admin.departments.create') }}" class="px-4 py-2 bg-white text-slate-950 font-semibold text-xs rounded-lg hover:bg-slate-200 transition-all">
                + دپارتمان جدید
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 bg-emerald-950/50 border border-emerald-500/30 text-emerald-400 text-xs rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-slate-900/50 border border-slate-800 rounded-xl overflow-hidden">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="border-b border-slate-800 bg-slate-900/80 text-[11px] font-medium text-slate-400">
                        <th class="p-4">عنوان (فارسی)</th>
                        <th class="p-4">عنوان (English)</th>
                        <th class="p-4">شناسه یکتا (Slug)</th>
                        <th class="p-4 text-left">شناسه سیستم (UUID)</th>
                        <th class="p-4 text-center">عملیات</th> </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-xs text-slate-300">
                    @forelse($departments as $department)
                        <tr class="hover:bg-slate-900/30 transition-colors">
                            <td class="p-4 font-medium text-slate-200">{{ $department->title['fa'] ?? '-' }}</td>
                            <td class="p-4 text-left font-mono text-[11px]" dir="ltr">{{ $department->title['en'] ?? '-' }}</td>
                            <td class="p-4 text-left font-mono text-[11px]" dir="ltr">{{ $department->slug }}</td>
                            <td class="p-4 text-left font-mono text-[10px] text-slate-500" dir="ltr">{{ $department->id }}</td>
                            
                            <td class="p-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.departments.edit', $department->id) }}" 
                                       class="px-2.5 py-1 bg-amber-500/10 border border-amber-500/20 text-amber-400 rounded-md hover:bg-amber-500 hover:text-slate-950 transition-all text-[11px] font-medium">
                                        ویرایش
                                    </a>
                                    
                                    <form action="{{ route('admin.departments.destroy', $department->id) }}" method="POST" 
                                          onsubmit="return confirm('آیا از حذف این دپارتمان مطمئن هستید؟ با حذف دپارتمان، دسترسی کارشناسان متصل به آن نیز لغو خواهد شد.');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="px-2.5 py-1 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-md hover:bg-rose-500 hover:text-white transition-all text-[11px] font-medium cursor-pointer">
                                            حذف
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-500 text-xs"> هیچ دپارتمانی تا این لحظه تعریف نشده است.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>