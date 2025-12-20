<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail; // <-- Tambahkan
use App\Mail\SendOtpMail;             // <-- Tambahkan

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect(route('dashboard'));
        }

        // Buat dan simpan OTP baru
        $user = $request->user();
        $otp = rand(100000, 999999);
        $user->otp = $otp;
        $user->otp_expires_at = now()->addMinutes(10);
        $user->save();

        // Kirim email OTP
        try {
            Mail::to($user->email)->send(new SendOtpMail($otp));
        } catch (\Exception $e) { /* tangani error */ }

        return back()->with('status', 'otp-sent'); // Status 'otp-sent'
    }
}
