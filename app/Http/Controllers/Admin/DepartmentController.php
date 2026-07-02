<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    /**
     * نمایش لیست دپارتمان‌ها
     */
    public function index()
    {
        $departments = Department::latest()->get();
        return view('admin.departments.index', compact('departments'));
    }

    /**
     * نمایش فرم ساخت دپارتمان جدید
     */
    public function create()
    {
        return view('admin.departments.create');
    }

    /**
     * ذخیره دپارتمان جدید در دیتابیس
     */
    public function store(Request $request)
    {
        // اعتبارسنجی ورودی‌ها
        $request->validate([
            'title_fa' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'slug' => 'required|string|unique:departments,slug|max:255',
        ]);

        // ساخت رکورد با ساختار آرایه‌ای (که در مدل کست JSON شده است)
        Department::create([
            'title' => [
                'fa' => $request->title_fa,
                'en' => $request->title_en,
            ],
            'slug' => Str::slug($request->slug),
        ]);

        return redirect()->route('admin.departments.index')->with('success', 'دپارتمان جدید با موفقیت ثبت شد.');
    }

    /**
     * 🛠️ متد جدید: نمایش فرم ویرایش دپارتمان
     */
    public function edit(Department $department)
    {
        return view('admin.departments.edit', compact('department'));
    }

    /**
     * 🛠️ متد جدید: به‌روزرسانی اطلاعات دپارتمان
     */
    public function update(Request $request, Department $department)
    {
        // اعتبارسنجی ورودی‌ها همراه با استثنا کردن دپارتمان فعلی برای اسلاگ
        $request->validate([
            'title_fa' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', Rule::unique('departments', 'slug')->ignore($department->id)],
        ]);

        // به‌روزرسانی اطلاعات آرایه جفت زبان و اسلاگ
        $department->update([
            'title' => [
                'fa' => $request->title_fa,
                'en' => $request->title_en,
            ],
            'slug' => Str::slug($request->slug),
        ]);

        return redirect()->route('admin.departments.index')->with('success', 'اطلاعات دپارتمان با موفقیت به‌روزرسانی شد.');
    }

    /**
     * 🛠️ متد جدید: حذف دپارتمان از سیستم
     */
    public function destroy(Department $department)
    {
        // حذف دپارتمان (روابط آن در جدول واسط کارشناسان یا تیکت‌ها بسته به تنظیمات کسکید دیتابیس مدیریت می‌شود)
        $department->delete();

        return redirect()->route('admin.departments.index')->with('success', 'دپارتمان مورد نظر با موفقیت از سیستم حذف شد.');
    }
}