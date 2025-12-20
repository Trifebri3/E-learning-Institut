<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
// DIHAPUS: EmailVerificationPromptController
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetOtpController;

// DIHAPUS: VerifyEmailController
use Illuminate\Support\Facades\Route;

// DITAMBAHKAN:
use App\Http\Controllers\Auth\OtpVerificationController;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');
// --- TAMBAHKAN DUA RUTE INI ---
    Route::get('verify-reset-otp', [PasswordResetOtpController::class, 'create'])
                ->name('password.verify-otp');

    Route::post('verify-reset-otp', [PasswordResetOtpController::class, 'store'])
                ->name('password.verify-otp.store');

                
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
                ->name('password.store');
});

// INI ADALAH BAGIAN YANG ANDA KIRIM DAN SUDAH DIPERBAIKI
Route::middleware('auth')->group(function () {

    // 1. DIUBAH: Mengarah ke Controller OTP kita
    Route::get('verify-email', [OtpVerificationController::class, 'create'])
                ->name('verification.notice');

    // 2. DIHAPUS: Rute verifikasi link {id}/{hash}
    /*
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');
    */

    // 3. DITAMBAHKAN: Rute untuk memproses/validasi OTP
    Route::post('verify-otp', [OtpVerificationController::class, 'store'])
                ->middleware(['throttle:6,1'])
                ->name('otp.verify');

    // 4. DIBIARKAN: Rute 'kirim ulang' (controllernya sudah kita ubah jadi kirim OTP)
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    // Sisanya biarkan sama
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});
