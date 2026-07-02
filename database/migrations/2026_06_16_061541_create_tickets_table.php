<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $blueprint) {
            $blueprint->uuid('id')->primary(); // کد منحصربه‌فرد هر تیکت
            $blueprint->foreignId('user_id')->constrained()->onDelete('cascade'); // کی این تیکت رو زده؟
            $blueprint->foreignUuid('department_id')->constrained('departments')->onDelete('cascade'); // مال کدوم دپارتمانه؟
            
            $blueprint->string('title');
            $blueprint->text('description');
            
            $blueprint->string('priority')->default('medium'); // اولویت: کم، متوسط، فوری، بحرانی
            $blueprint->string('status')->default('new'); // وضعیت: جدید، در انتظار بررسی، پاسخ داده شده، بسته شده
            
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};