<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Ticket;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        // دریافت دپارتمان‌ها برای نمایش در لندینگ
        $departments = Department::all();
        
        // چند آمار فیک اما جذاب برای رقابتی نشان دادن صفحه
        $stats = [
            'tickets_resolved' => Ticket::where('status', 'closed')->count() + 1420,
            'response_time' => ' کمتر از ۱۵ دقیقه',
            'satisfaction_rate' => '۹۹.۳٪'
        ];

        return view('welcome', compact('departments', 'stats'));
    }
}