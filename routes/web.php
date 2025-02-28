<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\UserController;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/', [AuthController::class, 'login']);
});

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Regular User Attendance Routes
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/daily', [AttendanceController::class, 'daily'])->name('daily');
        Route::post('/generate-qr', [AttendanceController::class, 'generateQr'])->name('generate-qr');
        Route::get('/scan/{code}', [AttendanceController::class, 'scan'])->name('scan');
        Route::post('/scan/{code}', [AttendanceController::class, 'processScan'])->name('process-scan');
        Route::post('/check-out', [AttendanceController::class, 'checkOut'])->name('check-out');
        Route::delete('/reset-today', [AttendanceController::class, 'resetToday'])->name('reset-today');
    });
    
    // Admin Routes
    Route::middleware('admin')->group(function () {
        // Admin Attendance Routes
        Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        // User Management Routes
        Route::resource('users', UserController::class);
    });
});
