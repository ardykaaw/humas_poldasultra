<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = now()->format('Y-m-d');
        
        if ($user->role === 'admin') {
            $todayAttendances = Attendance::with('user')
                ->whereDate('date', $today)
                ->get();
                
            return view('dashboard.admin', compact('todayAttendances'));
        }
        
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();
            
        return view('dashboard.user', compact('attendance'));
    }
} 