<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 💡 ساخت جدول واسط برای اتصال چندبه‌چند کاربران (کارشناسان) و دپارتمان‌ها
        Schema::create('department_user', function (Blueprint $table) {
            $table->id();
            
            // 🔗 اتصال به جدول کاربران (کارشناس)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // 🔗 اتصال به جدول دپارتمان‌ها (چون دپارتمان‌های شما UUID هستند از foreignUuid استفاده کردیم)
            $table->foreignUuid('department_id')->constrained('departments')->onDelete('cascade');
            
            $table->timestamps();
            
            // 🛠️ جلوگیری از ثبت دیتای تکراری (یک کارشناس نتواند دو بار به یک دپارتمان متصل شود)
            $table->unique(['user_id', 'department_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('department_user');
    }
};