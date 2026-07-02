<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage; // 💡 وارد کردن کلاس استوریج

class Ticket extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    // 💡 اضافه شدن 'attachment' به فیلدهای مجاز برای ذخیره‌سازی در دیتابیس
    protected $fillable = ['user_id', 'department_id', 'support_id', 'title', 'description', 'priority', 'status', 'attachment'];

    protected static function booted()
    {
        static::creating(function ($ticket) {
            if (empty($ticket->id)) {
                $ticket->id = (string) Str::uuid();
            }
        });
    }

    // 🔗 رابطه با جدول کاربر (هر تیکت متعلق به یک کاربر عادی ایجادکننده است)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🔗 رابطه با جدول دپارتمان (هر تیکت متعلق به یک دپارتمان است)
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // 🔗 رابطه با کارشناس پشتیبان (هر تیکت می‌تواند به یک کارشناس ارجاع داده شود)
    public function support()
    {
        return $this->belongsTo(User::class, 'support_id');
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * 💡 یک متد کمکی (Accessor) برای گرفتن آدرس مستقیم و قابل دانلود فایل پیوست اولیه تیکت
     * اگر فایل وجود داشته باشد، آدرس کامل آن را برمی‌گرداند، در غیر این صورت null
     */
    public function getAttachmentUrlAttribute()
    {
        return $this->attachment ? Storage::url($this->attachment) : null;
    }
}