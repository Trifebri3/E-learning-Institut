<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardRedirectController extends Controller
{
    public function __invoke(Request $request)
    {
        $role = Auth::user()->role;

        if ($role === 'superadmin') {
            return redirect()->route('superadmin.dashboard');
        } elseif ($role === 'adminprogram') {
            return redirect()->route('adminprogram.dashboard');
        } elseif ($role === 'instructor') {
            return redirect()->route('instructor.dashboard');
        } elseif ($role === 'participant') {
            return redirect()->route('participant.dashboard');
        } else {
            // Default fallback jika role tidak dikenal
            return redirect()->route('participant.dashboard');
        }
    }
}
