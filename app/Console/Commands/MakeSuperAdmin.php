<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use function Laravel\Prompts\text;
use function Laravel\Prompts\password;

class MakeSuperAdmin extends Command
{
    /**
     * نام دستوری که در ترمینال تایپ می‌شود
     */
    protected $signature = 'make:super-admin';

    /**
     * توضیح مختصر دستور
     */
    protected $description = 'Create a new Super Admin account interactively';

    public function handle()
    {
        $this->info('--- 👑 Chaman Ticket: Super Admin Generator ---');

        // دریافت تعاملی اطلاعات از کاربر
        $name = text('Enter Super Admin Name:', required: true);
        
        $email = text(
            label: 'Enter Super Admin Email:',
            required: true,
            validate: fn (string $value) => match (true) {
                !filter_var($value, FILTER_VALIDATE_EMAIL) => 'The email address is invalid.',
                User::where('email', $value)->exists() => 'This email is already registered.',
                default => null
            }
        );

  $pass = text(
            label: 'Enter Password (min 8 chars):',
            placeholder: 'e.g. password123',
            required: true,
            validate: fn (string $value) => strlen($value) < 8 ? 'Password must be at least 8 characters.' : null
        );

        // ساخت کاربر با سطح دسترسی مدیر کل (is_admin = 2)
        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($pass),
            'is_admin' => 2,
        ]);

        $this->newLine();
        $this->info("✅ Super Admin [{$email}] created successfully!");
        return Command::SUCCESS;
    }
}