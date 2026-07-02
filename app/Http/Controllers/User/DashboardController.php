<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        
        $stats = [
            'total'  => Ticket::where('user_id', $userId)->count(),
            'open'   => Ticket::where('user_id', $userId)->where('status', 'open')->count(),
            'closed' => Ticket::where('user_id', $userId)->where('status', 'closed')->count(),
        ];

        return view('user.dashboard', compact('stats'));
    }
}