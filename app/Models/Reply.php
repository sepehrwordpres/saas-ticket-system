<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage; // 💡 وارد کردن کلاس استوریج برای متد فایل

class Reply extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    // 💡 اضافه شدن 'attachment' به فیلدهای مجاز برای ذخیره‌سازی در دیتابیس
    protected $fillable = ['ticket_id', 'user_id', 'message', 'is_internal', 'attachment'];

    // تبدیل خودکار صفر و یک به True و False واقعی
    protected $casts = [
        'is_internal' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($reply) {
            if (empty($reply->id)) {
                $reply->id = (string) Str::uuid();
            }
        });
    }

    // 🔗 هر پاسخ متعلق به یک تیکت مشخص است
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    // 🔗 هر پاسخ را یک کاربر (یا ادمین) نوشته است
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 💡 متد جادویی (Accessor) برای ساختن آدرس مستقیم و قابل دانلود فایل ضمیمه در پیام‌های چت
     * این متد دقیقاً همان چیزی است که بلید برای نمایش دکمه فایل به آن نیاز دارد
     */
    public function getAttachmentUrlAttribute()
    {
        return $this->attachment ? Storage::url($this->attachment) : null;
    }
}