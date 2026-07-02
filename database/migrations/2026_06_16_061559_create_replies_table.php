<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('replies', function (Blueprint $blueprint) {
            $blueprint->uuid('id')->primary(); // کد منحصربه‌فرد هر پیام چت
            $blueprint->foreignUuid('ticket_id')->constrained('tickets')->onDelete('cascade'); // مربوط به کدوم تیکته؟
            $blueprint->foreignId('user_id')->constrained()->onDelete('cascade'); // کی این پیام رو نوشته؟ (ادمین یا کاربر)
            
            $blueprint->text('message'); // متن پیام چت
            $blueprint->boolean('is_internal')->default(false); // 🕵️‍♂️ یادداشت مخفی ادمین‌ها
            
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('replies');
    }
};