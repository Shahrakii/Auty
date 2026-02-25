<?php

use Auty\Http\Controllers\Auth\ForgotPasswordController;
use Auty\Http\Controllers\Auth\LoginController;
use Auty\Http\Controllers\Auth\LogoutController;
use Auty\Http\Controllers\Auth\OtpController;
use Auty\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;

// ── Guest only ─────────────────────────────────────────────
Route::middleware('guest:admin')->group(function () {
    Route::get('/login',                  [LoginController::class, 'showForm'])->name('login');
    Route::post('/login',                 [LoginController::class, 'login'])->name('login.post');
    Route::get('/forgot-password',        [ForgotPasswordController::class, 'showForm'])->name('password.request');
    Route::post('/forgot-password',       [ForgotPasswordController::class, 'send'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showForm'])->name('password.reset');
    Route::post('/reset-password',        [ResetPasswordController::class, 'reset'])->name('password.update');
});

// ── Authenticated (requires login, but OTP not yet verified) ──
Route::middleware('auty.auth')->group(function () {
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

    // OTP routes — only when auty.otp.enabled = true
    Route::prefix('otp')->name('otp.')->group(function () {
        // Show OTP verification page
        Route::get('/', [OtpController::class, 'index'])->name('index');

        // Verify OTP code (POST)
        Route::post('/verify', [OtpController::class, 'verify'])->name('verify');

        // Resend OTP (POST) – this is the correct action for the button
        Route::post('/resend', [OtpController::class, 'resend'])->name('resend');
    });

    // Protected dashboard (only after OTP verified)
    Route::get('/dashboard', function () {
        $admin = auth('admin')->user();
        return view('auty::dashboard', compact('admin'));
    })->name('dashboard');
});