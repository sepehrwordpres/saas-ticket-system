<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SupportManagementController extends Controller
{
    /**
     * نمایش لیست کارشناسان پشتیبانی
     */
    public function index()
    {
        $supports = User::with('departments')->where('is_admin', 1)->latest()->get();
        return view('admin.supports.index', compact('supports'));
    }

    /**
     * نمایش فرم ساخت کارشناس جدید
     */
    public function create()
    {
        $departments = Department::all();
        return view('admin.supports.create', compact('departments'));
    }

    /**
     * ذخیره کارشناس جدید در دیتابیس
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'departments' => ['required', 'array'],
            'departments.*' => ['exists:departments,id'],
        ]);

        $support = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => 1, 
        ]);

        $support->departments()->attach($request->departments);

        return redirect()->route('admin.supports.index')->with('success', 'حساب کارشناس پشتیبانی و دپارتمان‌های مجاز با موفقیت ثبت شدند.');
    }

    /**
     * 🛠️ متد جدید: نمایش فرم ویرایش کارشناس
     */
    public function edit(User $support)
    {
        // امنیت: مطمئن می‌شویم که این کاربر حتماً کارشناس (is_admin == 1) باشد
        if ($support->is_admin != 1) {
            abort(404);
        }

        $departments = Department::all();
        
        // گرفتن آی‌دی دپارتمان‌هایی که کارشناس در حال حاضر عضو آن‌هاست
        $assignedDepartments = $support->departments()->pluck('departments.id')->toArray();

        return view('admin.supports.edit', compact('support', 'departments', 'assignedDepartments'));
    }

    /**
     * 🛠️ متد جدید: به‌روزرسانی اطلاعات کارشناس
     */
    public function update(Request $request, User $support)
    {
        if ($support->is_admin != 1) {
            abort(404);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($support->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'], // پسورد اختیاری است
            'departments' => ['required', 'array'],
            'departments.*' => ['exists:departments,id'],
        ]);

        // آپدیت اطلاعات پایه
        $support->name = $request->name;
        $support->email = $request->email;

        // اگر ادمین پسورد جدید وارد کرده بود، آن را تغییر بده
        if ($request->filled('password')) {
            $support->password = Hash::make($request->password);
        }

        $support->save();

        // 🔄 همگام‌سازی دپارتمان‌ها در جدول واسط (حذف قبلی‌ها و ثبت جدیدها)
        $support->departments()->sync($request->departments);

        return redirect()->route('admin.supports.index')->with('success', 'اطلاعات کارشناس و دپارتمان‌های دسترسی با موفقیت به‌روزرسانی شد.');
    }

    /**
     * 🛠️ متد جدید: حذف کامل کارشناس و سلب دسترسی‌ها
     */
    public function destroy(User $support)
    {
        if ($support->is_admin != 1) {
            abort(404);
        }

        // 🔗 قطع ارتباط کارشناس با دپارتمان‌ها در جدول واسط
        $support->departments()->detach();

        // حذف خود کاربر از جدول کاربران
        $support->delete();

        return redirect()->route('admin.supports.index')->with('success', 'حساب کارشناس پشتیبانی با موفقیت از سیستم حذف شد.');
    }
}