<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BadgeController extends Controller
{
    /**
     * Menampilkan halaman "Lencana Saya".
     */
    public function index()
    {
        $user = Auth::user();

        // Ambil semua badge (BadgeTemplate) yang dimiliki user,
        // dan load juga relasi 'program' untuk setiap badge
        // agar kita tahu badge itu dari program apa.
        $userBadges = $user->badges()->with('program')->get();

        // Kirim data ke view
        return view('participant.badges.index', compact('userBadges'));
    }
}
