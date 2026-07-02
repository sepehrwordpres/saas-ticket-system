<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // اضافه کردن ستون ارجاع به کارشناس بعد از دپارتمان به صورت nullable
            $table->foreignId('support_id')
                  ->nullable()
                  ->after('department_id')
                  ->constrained('users')
                  ->onDelete('set null'); // اگر کارشناسی پاک شد، تیکت حذف نشود و فقط این فیلد null شود
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // حذف کلید خارجی و خود ستون در صورت رول‌بک
            $table->dropForeign(['support_id']);
            $table->dropColumn('support_id');
        });
    }
};