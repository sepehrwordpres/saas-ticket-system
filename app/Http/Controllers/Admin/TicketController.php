<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Department; 
use App\Services\Notification\NotificationProviderInterface; // 💡 تزریق مستقیم اینترفیس به جای سرویس واسط
use Illuminate\Http\Request;

class TicketController extends Controller
{
    protected $notification;

    // لاراول به طور خودکار بر اساس تنظیمات AppServiceProvider درایور فعال را اینجا تزریق می‌کند
    public function __construct(NotificationProviderInterface $notification)
    {
        $this->notification = $notification;
    }

    // ۱. نمایش لیست تیکت‌های سیستم بر اساس دپارتمان‌های کارشناس یا ارجاع مستقیم شخصی
  // ۱. نمایش لیست تیکت‌های سیستم همراه با فیلتر پیشرفته و صفحه‌بندی
    public function index(Request $request)
    {
        $user = auth()->user();

        // شروع یک کوئری پایه به همراه روابط (Eager Loading)
        $query = Ticket::with(['user', 'department', 'support'])->latest();

        // الف) اعمال محدودیت دسترسی بر اساس سطح کاربر
        if ($user->isSupport()) {
            // گرفتن لیست آی‌دی دپارتمان‌های این کارشناس
            $departmentIds = $user->departments()->pluck('departments.id')->toArray();

            // ادغام هوشمند دو شرط در یک کوئری واحد دیتابیس
            $query->where(function ($q) use ($departmentIds, $user) {
                $q->whereIn('department_id', $departmentIds)
                  ->whereNull('support_id')
                  ->orWhere('support_id', $user->id);
            });
        }

        // ب) اعمال فیلترهای پویا بر اساس درخواست ورودی (Request)
        
        // ۱. فیلتر بر اساس وضعیت تیکت
        $query->when($request->filled('status'), function ($q) use ($request) {
            $q->where('status', $request->status);
        });

        // ۲. فیلتر بر اساس دپارتمان (مخصوصاً کاربردی برای مدیر کل)
        $query->when($request->filled('department_id'), function ($q) use ($request) {
            $q->where('department_id', $request->department_id);
        });

        // ۳. فیلتر جستجوی متنی (جستجو در عنوان تیکت یا شناسه تیکت)
        $query->when($request->filled('search'), function ($q) use ($request) {
            $search = $request->search;
            $q->where(function ($innerQuery) use ($search) {
                $innerQuery->where('title', 'like', "%{$search}%")
                           ->orWhere('id', 'like', "%{$search}%");
            });
        });

        // ج) صفحه‌بندی نهایی خروجی دیتابیس (مثلاً هر صفحه ۱۵ تیکت)
        // متد appends باعث می‌شود فیلترهای سرچ شده در هنگام تعویض صفحات حفظ شوند
        $tickets = $query->paginate(15)->appends($request->all());

        // برای اینکه بتوانیم لیست دپارتمان‌ها را هم در منوی فیلتر فرانت‌اند نشان دهیم
        $departments = Department::all();

        return view('admin.tickets.index', compact('tickets', 'departments'));
    }

    // ۲. نمایش صفحه چت تیکت
    public function show(Ticket $ticket)
    {
        $user = auth()->user();

        // 🛠️ لایه امنیتی: کارشناس حق ورود دارد اگر عضو دپارتمان تیکت باشد «یا» تیکت مستقیماً به او ارجاع شده باشد
        if ($user->isSupport()) {
            $hasDepartmentAccess = $user->departments()->where('departments.id', $ticket->department_id)->exists();
            $isAssignedToHim = ($ticket->support_id == $user->id);

            if (!$hasDepartmentAccess && !$isAssignedToHim) {
                abort(403, 'شما به این تیکت دسترسی ندارید.');
            }
        }

        $replies = $ticket->replies()->with('user')->get();
        $departments = Department::all();
        $supports = User::where('is_admin', 1)->get();
        
        return view('admin.tickets.show', compact('ticket', 'replies', 'supports', 'departments'));
    }

    // ۳. ارسال پاسخ یا ثبت یادداشت داخلی توسط ادمین/کارشناس
    public function reply(Request $request, Ticket $ticket)
    {
        // 💡 اضافه کردن ولیدیشن فایل پیوست (حداکثر ۵ مگابایت و پسوندهای امن)
        $request->validate([
            'message' => 'required|string',
            'is_internal' => 'nullable|boolean', 
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,zip,rar|max:5120',
        ]);

        // تعریف متغیر اولیه برای فایل پیوست
        $attachmentPath = null;

        // 💡 بررسی اینکه آیا فایلی آپلود شده است یا خیر
        if ($request->hasFile('attachment')) {
            // ذخیره فایل در دیسک public درون پوشه attachments و گرفتن آدرس نسبی آن
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
        }

        $ticket->replies()->create([
            'user_id' => auth()->id(), 
            'message' => $request->message,
            'is_internal' => $request->has('is_internal') ? (bool)$request->is_internal : false,
            'attachment' => $attachmentPath, // 💡 ذخیره آدرس فایل در دیتابیس
        ]);

        // بررسی اینکه آیا پاسخ عمومی است یا یادداشت داخلی ادمین‌ها
        if (!$request->has('is_internal') || !$request->is_internal) {
            $ticket->update(['status' => 'answered']);

            // 🔔 ارسال نوتیفیکیشن بدون نیاز به تعیین کانال در کنترلر
            $user = $ticket->user; 
            
            // شخصی‌سازی متن پیام
            $shortId = strtoupper(substr($ticket->id, 0, 6));
            $senderName = auth()->user()->name;
            
            $message = "کاربر گرامی، پاسخ جدیدی توسط آقای/خانم {$senderName} برای تیکت شماره #{$shortId} شما ثبت شد.";
            
            try {
                // 💡 کنترلر فقط متد لایه کلی را صدا می‌زند
                $this->notification->send($user, $message);
            } catch (\Exception $e) {
                logger("Notification Error: " . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'پاسخ یا یادداشت شما با موفقیت ثبت شد.');
    }

    // ۴. تغییر وضعیت تیکت
    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|in:new,pending,answered,closed',
        ]);

        $ticket->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'وضعیت تیکت با موفقیت به‌روزرسانی شد.');
    }

    // ۵. ارجاع تیکت و تغییر دپارتمان (مخصوص مدیر کل)
    public function assign(Request $request, Ticket $ticket)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'support_id'    => 'nullable|exists:users,id', 
        ]);

        $ticket->update([
            'department_id' => $request->department_id,
            'support_id'    => $request->support_id 
        ]);

        // 🔔 ارسال نوتیفیکیشن هوشمند به کارشناس جدید مسئول تیکت
        if ($request->filled('support_id')) {
            $assignedSupport = User::find($request->support_id);
            
            // شخصی‌سازی متن پیام
            $shortId = strtoupper(substr($ticket->id, 0, 6));
            $supportName = $assignedSupport->name;
            
            $message = "همکار گرامی جناب آقای/خانم {$supportName}، تیکت شماره #{$shortId} مستقیماً به کارتابل شما ارجاع داده شد.";

            try {
                // 💡 کنترلر بدون وابستگی ارسال می‌کند
                $this->notification->send($assignedSupport, $message);
            } catch (\Exception $e) {
                logger("Notification Support Error: " . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'دپارتمان و کارشناس مسئول تیکت با موفقیت به‌روزرسانی شد.');
    }
}