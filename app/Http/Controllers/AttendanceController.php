<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendance::with('user')
            ->orderBy('date', 'desc')
            ->paginate(10);
            
        return view('attendance.index', compact('attendances'));
    }

    public function daily()
    {
        $user = Auth::user();
        $today = now()->format('Y-m-d');
        
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();
            
        return view('attendance.daily', compact('attendance'));
    }

    public function generateQr()
    {
        $user = Auth::user();
        $today = now()->format('Y-m-d');
        
        // Check if user already has attendance for today
        $existingAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();
            
        if ($existingAttendance) {
            if ($existingAttendance->status !== 'pending') {
                return redirect()->route('attendance.daily')
                    ->with('error', 'Anda sudah melakukan absensi hari ini');
            }
            return redirect()->route('attendance.daily');
        }
        
        // Create new attendance record
        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => $today,
            'status' => 'pending',
            'qr_code' => Str::random(40)
        ]);
        
        Log::info('New attendance created:', [
                'user_id' => $user->id,
            'date' => $today,
            'qr_code' => $attendance->qr_code
        ]);

        return redirect()->route('attendance.daily')
            ->with('success', 'QR Code berhasil di-generate');
    }

    public function scan($code)
    {
        $attendance = Attendance::where('qr_code', $code)->firstOrFail();
        
        // Check if attendance belongs to current user
        if ($attendance->user_id !== Auth::id()) {
            return redirect()->route('attendance.daily')
                ->with('error', 'QR Code ini bukan milik Anda');
        }
        
        // Check if attendance is still pending
        if ($attendance->status !== 'pending') {
            return redirect()->route('attendance.daily')
                ->with('error', 'QR Code ini sudah digunakan');
        }

        // Check if attendance is for today
        if ($attendance->date->format('Y-m-d') !== now()->format('Y-m-d')) {
            return redirect()->route('attendance.daily')
                ->with('error', 'QR Code ini sudah kadaluarsa');
        }
        
        return view('attendance.scan', compact('code'));
    }

    public function processScan(Request $request, $code)
    {
        try {
            $attendance = Attendance::where('qr_code', $code)->firstOrFail();
            
            // Check if attendance belongs to current user
            if ($attendance->user_id !== Auth::id()) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'QR Code ini bukan milik Anda'
                    ], 403);
                }
                return redirect()->route('attendance.daily')
                    ->with('error', 'QR Code ini bukan milik Anda');
            }
            
            // Check if attendance is still pending
            if ($attendance->status !== 'pending') {
                if ($request->wantsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'QR Code ini sudah digunakan'
                    ], 400);
                }
                return redirect()->route('attendance.daily')
                    ->with('error', 'QR Code ini sudah digunakan');
            }

            // Check if attendance is for today
            if ($attendance->date->format('Y-m-d') !== now()->format('Y-m-d')) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'QR Code ini sudah kadaluarsa'
                    ], 400);
                }
                return redirect()->route('attendance.daily')
                    ->with('error', 'QR Code ini sudah kadaluarsa');
            }

            // Validate required fields
            if (!$request->filled('location') || !$request->filled('signature')) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Lokasi dan tanda tangan harus diisi'
                    ], 422);
                }
                return redirect()->back()
                    ->with('error', 'Lokasi dan tanda tangan harus diisi');
            }
            
            $attendance->update([
                'check_in' => now()->format('H:i:s'),
                'status' => 'hadir',
                'signature' => $request->signature,
                'location' => $request->location
            ]);

            // Logout user after successful attendance
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Absensi berhasil disubmit'
                ]);
            }
            
            return redirect()->route('login')
                ->with('success', 'Absensi berhasil disubmit');
            
        } catch (\Exception $e) {
            \Log::error('Error processing attendance scan: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan saat memproses absensi'
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memproses absensi');
        }
    }

    public function checkOut()
    {
        $user = Auth::user();
        $today = now()->format('Y-m-d');
        
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();
            
        if (!$attendance) {
            return redirect()->route('attendance.daily')
                ->with('error', 'Anda belum melakukan check in hari ini');
        }
        
        if ($attendance->check_out) {
            return redirect()->route('attendance.daily')
                ->with('error', 'Anda sudah melakukan check out hari ini');
        }
        
            $attendance->update([
                'check_out' => now()->format('H:i:s')
            ]);

        return redirect()->route('attendance.daily')
            ->with('success', 'Berhasil melakukan check out');
    }

    public function resetToday()
    {
        $user = Auth::user();
        $today = now()->format('Y-m-d');
        
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();
            
        if ($attendance) {
            $attendance->delete();
            return redirect()->route('attendance.daily')
                ->with('success', 'Berhasil mereset absensi hari ini');
        }
        
        return redirect()->route('attendance.daily')
            ->with('error', 'Tidak ada absensi untuk hari ini');
    }
} 