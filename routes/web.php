<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\TicketController as UserTicketController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\SupportManagementController;
use Illuminate\Support\Facades\Route;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

// ۱. صفحه اصلی پروژه
Route::get('/', function () {
    $stats = [
        'all' => \App\Models\Ticket::count(),
        'new' => \App\Models\Ticket::where('status', 'new')->count(),
        'pending' => \App\Models\Ticket::where('status', 'pending')->count(),
        'closed' => \App\Models\Ticket::where('status', 'closed')->count(),
        'tickets_resolved' => \App\Models\Ticket::whereIn('status', ['answered', 'closed'])->count(),
        'response_time' => '15 min',
        'satisfaction_rate' => '98%',
    ];
    return view('welcome', compact('stats'));
});

// ۲. صفحه داشبورد (هماهنگ شده با ۳ نقش: کاربر، کارشناس، مدیر کل)
Route::get('/dashboard', function () {
    $user = Auth::user();

    // 👑 حالت اول: اگر ادمین اصلی (سطح 2) باشد، آمار و تیکت‌های کل سیستم را بدون فیلتر ببیند
    if ($user->is_admin == 2) {
        $activeTickets = Ticket::whereIn('status', ['new', 'pending'])->count();
        $answeredTickets = Ticket::where('status', 'answered')->count();
        $totalTickets = Ticket::count();
        
        $recentTickets = Ticket::with('department')
            ->latest()
            ->take(5)
            ->get();

    // 👥 حالت دوم: اگر کارشناس پشتیبانی (سطح 1) باشد (اصلاح شده بر اساس انحصار ارجاع شخصی)
    } elseif ($user->isSupport() || $user->is_admin == 1) {
        // گرفتن آرایه تمیز از UUID دپارتمان‌های این کارشناس
        $departmentIds = $user->departments()->pluck('departments.id')->toArray();

        // 🛠️ اصلاح شمارش تیکت‌های فعال: (مال دپارتمانش و بدون صاحب) یا (ارجاع شده به خودش)
        $activeTickets = Ticket::whereIn('status', ['new', 'pending'])
            ->where(function($query) use ($departmentIds, $user) {
                $query->where(function($q) use ($departmentIds) {
                    $q->whereIn('department_id', $departmentIds)->whereNull('support_id');
                })->orWhere('support_id', $user->id);
            })->count();

        // 🛠️ اصلاح شمارش تیکت‌های پاسخ داده شده
        $answeredTickets = Ticket::where('status', 'answered')
            ->where(function($query) use ($departmentIds, $user) {
                $query->where(function($q) use ($departmentIds) {
                    $q->whereIn('department_id', $departmentIds)->whereNull('support_id');
                })->orWhere('support_id', $user->id);
            })->count();

        // 🛠️ اصلاح شمارش کل تیکت‌ها
        $totalTickets = Ticket::where(function($query) use ($departmentIds, $user) {
                $query->where(function($q) use ($departmentIds) {
                    $q->whereIn('department_id', $departmentIds)->whereNull('support_id');
                })->orWhere('support_id', $user->id);
            })->count();
        
        // 🛠️ اصلاح جدول ۵ تیکت اخیر: تفکیک دقیق دپارتمان بدون صاحب و ارجاع شخصی برای جلوگیری از تداخل دیتا تایپ
        $departmentRecent = Ticket::whereIn('department_id', $departmentIds)
            ->whereNull('support_id') // 💡 تیکت‌های رضا یا بقیه پشتیبان‌ها از اینجا فیلتر می‌شوند
            ->with('department')
            ->latest()
            ->take(5)
            ->get();

        $assignedRecent = Ticket::where('support_id', $user->id) // 💡 فقط تیکت‌های اختصاصی خودش
            ->with('department')
            ->latest()
            ->take(5)
            ->get();

        // ادغام رکوردهای اخیر، حذف هم‌پوشانی‌ها و گرفتن ۵ تیکت آخر واقعی
        $recentTickets = $departmentRecent->merge($assignedRecent)
            ->sortByDesc('created_at')
            ->take(5);

    // 👤 حالت سوم: آمار اختصاصی خود کاربر عادی (سطح 0)
    } else {
        $activeTickets = Ticket::where('user_id', $user->id)
            ->whereIn('status', ['new', 'pending'])
            ->count();
            
        $answeredTickets = Ticket::where('user_id', $user->id)
            ->where('status', 'answered')
            ->count();
            
        $totalTickets = Ticket::where('user_id', $user->id)->count();
        
        $recentTickets = Ticket::where('user_id', $user->id)
            ->with('department')
            ->latest()
            ->take(5)
            ->get();
    }

    return view('dashboard', compact('activeTickets', 'answeredTickets', 'totalTickets', 'recentTickets'));
})->middleware(['auth', 'verified'])->name('dashboard');

// ۳. روت‌های پروفایل کاربری (Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    $table_fix = Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ========================================================
// 🛡️ روت‌های سیستم تیکتینگ (تفکیک شده و کاملاً هوشمند)
// ========================================================

// ۴. روت‌های بخش کاربر عادی
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/tickets', [UserTicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [UserTicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [UserTicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [UserTicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket}/reply', [UserTicketController::class, 'reply'])->name('tickets.reply');
});

// ۵. روت‌های مشترک ادمین اصلی و کارشناسان پشتیبانی (برای پاسخگویی به تیکت‌ها)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/tickets', [AdminTicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{ticket}', [AdminTicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket}/reply', [AdminTicketController::class, 'reply'])->name('tickets.reply');
    Route::patch('/tickets/{ticket}/status', [AdminTicketController::class, 'updateStatus'])->name('tickets.status');

    // 👑 روت‌های اختصاصی مدیر کل (فقط ادمین اصلی با سطح ۲ دسترسی دارد)
    Route::middleware(['can:super-admin'])->group(function () {
        
        // 👥 مدیریت کامل کارشناسان (پشتیبان‌ها)
        Route::get('/supports', [SupportManagementController::class, 'index'])->name('supports.index');
        $supports_create = Route::get('/supports/create', [SupportManagementController::class, 'create'])->name('supports.create');
        Route::post('/supports', [SupportManagementController::class, 'store'])->name('supports.store');
        Route::get('/supports/{support}/edit', [SupportManagementController::class, 'edit'])->name('supports.edit');
        Route::put('/supports/{support}', [SupportManagementController::class, 'update'])->name('supports.update');
        Route::delete('/supports/{support}', [SupportManagementController::class, 'destroy'])->name('supports.destroy');

        // 🏢 مدیریت دپارتمان‌ها - روت‌های تکمیلی ویرایش و حذف اضافه شد
        Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
        Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
        Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
        Route::get('/departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
        Route::put('/departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');
        Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

        // 💡 ارجاع تیکت به کارشناس مشخص (مخصوص مدیر کل)
        Route::post('/tickets/{ticket}/assign', [AdminTicketController::class, 'assign'])->name('tickets.assign');
    });
});

require __DIR__.'/auth.php';