<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin', // مقادیر: 0 (کاربر)، 1 (کارشناس)، 2 (مدیر کل)
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'integer', 
        ];
    }

    // متد تشخیص ادمین اصلی (مدیر کل)
    public function isAdmin(): bool
    {
        return $this->is_admin === 2;
    }

    // متد تشخیص کارشناس پشتیبانی
    public function isSupport(): bool
    {
        return $this->is_admin === 1;
    }

    // متد تشخیص کاربر عادی
    public function isRegularUser(): bool
    {
        return $this->is_admin === 0;
    }

    /**
     * 🔗 رابطه چندبه‌چند با دپارتمان‌ها
     * 💡 این رابطه مشخص می‌کند که یک کارشناس به کدام دپارتمان‌ها دسترسی دارد.
     */
    public function departments()
    {
        // نام جدول واسط: department_user
        return $this->belongsToMany(Department::class, 'department_user');
    }
}