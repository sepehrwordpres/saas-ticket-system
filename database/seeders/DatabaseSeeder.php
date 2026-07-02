<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Department;
use App\Models\Ticket;
use App\Models\Reply;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 🛡️ غیرفعال کردن موقت قفل کلید خارجی برای تزریق بدون ارور
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // پاک کردن دیتای قبلی جدول کاربران برای جلوگیری از ارور دیتای تکراری
        DB::table('users')->truncate();

        // ۱. تزریق مستقیم کاربران تستی به جدول کاربران (بدون دخالت فیلترهای مدل)
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'رضا',
                'email' => 'reza@example.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'احمد',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // ۲. ساخت دپارتمان‌های نمونه
        // اول دپارتمان‌های قبلی رو پاک میکنیم تا تداخل ایجاد نشه
        Department::truncate();
        Ticket::truncate();
        Reply::truncate();

        $techDept = Department::create([
            'title' => ['fa' => 'پشتیبانی فنی', 'en' => 'Technical Support'],
            'slug' => 'technical-support'
        ]);

        $financeDept = Department::create([
            'title' => ['fa' => 'بخش مالی و فروش', 'en' => 'Finance & Sales'],
            'slug' => 'finance-sales'
        ]);

        // ۳. ایجاد تیکت نمونه برای کاربر با آی‌دی ۱
        $ticket = Ticket::create([
            'user_id' => 1,
            'department_id' => $techDept->id,
            'title' => 'خطای ۵۰۰ هنگام اتصال به درگاه پرداخت',
            'description' => "سلام، من وقتی می‌خوام فاکتور رو پرداخت کنم، دکمه رو که می‌زنم مستقیم با خطای لاراولی ۵۰۰ مواجه میشم. لطفاً بررسی کنید پروژه‌ام خوابیده.",
            'priority' => 'critical',
            'status' => 'pending'
        ]);

        // ۴. ثبت چت‌ها و پاسخ‌های اولیه
        Reply::create([
            'ticket_id' => $ticket->id,
            'user_id' => 2,
            'message' => "سلام وقت بخیر، لاگ‌های سرور رو بررسی کردم. مشکل از سمت ست نشدن پورت CURL روی هاست شماست. لطفاً دسترسی هاست رو بفرستید.",
            'is_internal' => false
        ]);

        Reply::create([
            'ticket_id' => $ticket->id,
            'user_id' => 2,
            'message' => "بچه‌ها من بررسی کردم، این کاربر افزونه‌اش هم قدیمیه. اگر دسترسی داد، اول افزونه درگاهش رو دستی آپدیت کنید بعد پورت رو چک کنید.",
            'is_internal' => true
        ]);

        // 🛡️ فعال‌سازی مجدد قفل کلید خارجی دیتابیس پس از اتمام کار
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}