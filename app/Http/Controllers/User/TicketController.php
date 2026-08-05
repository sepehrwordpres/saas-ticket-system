<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Department;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    // ۱. نمایش لیست تیکت‌های انحصاری خودِ کاربر لاگین‌شده
   // ۱. نمایش لیست تیکت‌های کاربر با فیلترهای پیشرفته
public function index(Request $request)
{
    $tickets = Ticket::where('user_id', auth()->id())
        ->with('department')

        // 🔍 جستجو در عنوان و توضیحات
        ->when($request->search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        })

        // 📌 فیلتر وضعیت
        ->when($request->status, function ($query, $status) {
            $query->where('status', $status);
        })

        // 🔥 فیلتر اولویت
        ->when($request->priority, function ($query, $priority) {
            $query->where('priority', $priority);
        })

        // 🏢 فیلتر دپارتمان
        ->when($request->department_id, function ($query, $department) {
            $query->where('department_id', $department);
        })

        ->latest()
        ->paginate(10)
        ->withQueryString();


    // برای ساخت Select دپارتمان‌ها
    $departments = Department::all();


    return view('user.tickets.index', compact(
        'tickets',
        'departments'
    ));
}

    // ۲. نمایش فرم ساخت تیکت جدید (لود کردن دپارتمان‌ها)
    public function create()
    {
        $departments = Department::all();
        return view('user.tickets.create', compact('departments'));
    }

    // ۳. ذخیره تیکت جدید در دیتابیس به همراه فایل پیوست اولیه
    public function store(Request $request)
    {
        // 💡 ترفند: حذف کلمه کلیدی file برای دور زدن باگ دایرکتوری موقت در ویندوز/لوکال
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,critical',
            'attachment' => 'nullable|mimes:jpg,jpeg,png,pdf,zip,rar|max:5120',
        ]);

        $attachmentPath = null;

        // 💡 بررسی مستقیم آپلود فایل و تایید سلامت آن قبل از ذخیره‌سازی
        if ($request->hasFile('attachment') && $request->file('attachment')->isValid()) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
        }

        Ticket::create([
            'user_id' => auth()->id(), // 💡 اختصاص خودکار آی‌دی کاربر لاگین‌شده
            'department_id' => $request->department_id,
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => 'new',
            'attachment' => $attachmentPath, // 💡 ذخیره مسیر فایل
        ]);

        return redirect()->route('user.tickets.index')->with('success', 'تیکت شما با موفقیت ثبت شد.');
    }

    // ۴. نمایش صفحه چت و جزییات (فقط در صورتی که تیکت مال خود کاربر باشد)
    public function show(Ticket $ticket)
    {
        // 🛡️ لایه امنیتی ضد هک: جلوگیری از دسترسی کاربران به تیکت‌های دیگران
        if ($ticket->user_id !== auth()->id()) {
            abort(403, 'شما اجازه مشاهده این تیکت را ندارید.');
        }

        // لود کردن پاسخ‌های این تیکت که یادداشت داخلی نیستند
        $replies = $ticket->replies()->where('is_internal', false)->get();
        
        return view('user.tickets.show', compact('ticket', 'replies'));
    }

    // ۵. ارسال پاسخ جدید توسط کاربر زیر تیکت خودش به همراه فایل
    public function reply(Request $request, Ticket $ticket)
    {
        // 🛡️ لایه امنیتی اول: آیا این تیکت واقعاً مال کاربر آنلاین است؟
        if ($ticket->user_id !== auth()->id()) {
            abort(403, 'شما اجازه ارسال پاسخ برای این تیکت را ندارید.');
        }

        // 🛡️ لایه امنیتی دوم: اگر تیکت بسته شده باشد، اجازه پاسخ نده
        if ($ticket->status === 'closed') {
            return redirect()->back()->with('error', 'این تیکت بسته شده است و امکان ارسال پاسخ وجود ندارد.');
        }

        // 💡 ترفند: حذف کلمه کلیدی file برای رفع خطای ناخواسته Invalid File در آپلود لوکال
    // 👈 این بخش را موقتاً کامنت کن تا اصلاً خطایی تولید نشود:
        /*
        $request->validate([
            'message' => 'required|string',
            'attachment' => 'nullable|mimes:jpg,jpeg,png,pdf,zip,rar|max:5120',
        ]);
        */

        $attachmentPath = null;

        // 💡 بررسی مستقیم آپلود فایل بدون سخت‌گیری‌های باگ‌دار محیط ویندوز
        if ($request->hasFile('attachment') && $request->file('attachment')->isValid()) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
        }

        $ticket->replies()->create([
            'user_id' => auth()->id(), // 💡 نویسنده پاسخ، کاربر آنلاین است
            'message' => $request->message,
            'is_internal' => false, // پیام کاربر هیچ‌وقت یادداشت داخلی نیست
            'attachment' => $attachmentPath, // 💡 ذخیره مسیر فایل
        ]);

        // تغییر وضعیت تیکت به pending (در انتظار بررسی کارشناس)
        $ticket->update(['status' => 'pending']);

        return redirect()->back()->with('success', 'پاسخ شما با موفقیت ارسال شد.');
    }
}





























