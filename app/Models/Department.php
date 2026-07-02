<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Department extends Model
{
    // 🛡️ ترفند جادویی برای UUID
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['title', 'slug'];

    // 🌐 تنظیم خودکار لود دیتای دو زبانه (به عنوان کست)
    protected $casts = [
        'title' => 'array',
    ];

    // متد اتوماتیک برای تولید خودکار UUID هنگام ساخته شدن دپارتمان جدید
    protected static function booted()
    {
        static::creating(function ($department) {
            if (empty($department->id)) {
                $department->id = (string) Str::uuid();
            }
        });
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * 🔗 رابطه چندبه‌چند معکوس با کاربران (کارشناسان)
     * 💡 این متد مشخص می‌کند چه کارشناسانی به این دپارتمان دسترسی دارند.
     */
    public function users()
    {
        // نام جدول واسط: department_user
        return $this->belongsToMany(User::class, 'department_user');
    }
}