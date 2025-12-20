<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Narasumber;
use App\Models\Program;

class NarasumberController extends Controller
{
    /**
     * Menampilkan daftar Narasumber (Bisa difilter per program jika ada request)
     */
    public function index()
    {
        $user = Auth::user();

        // Ambil ID semua program yang diikuti user
        $programIds = $user->programs()->pluck('programs.id');

        // Ambil semua narasumber yang ada di program-program tersebut
        $narasumbers = Narasumber::whereIn('program_id', $programIds)
                                 ->with('program')
                                 ->get()
                                 ->groupBy('program.title'); // Kelompokkan per program

        return view('participant.narasumber.index', compact('narasumbers'));
    }

    /**
     * Menampilkan Detail Narasumber
     */
    public function show($id)
    {
        $user = Auth::user();
        $programIds = $user->programs()->pluck('programs.id');

        // Security: Pastikan user hanya melihat narasumber dari program yang dia ikuti
        $narasumber = Narasumber::where('id', $id)
                                ->whereIn('program_id', $programIds)
                                ->firstOrFail();

        // Ambil kelas apa saja yang diajar narasumber ini (yang sudah publish)
        $kelasDiajar = $narasumber->kelas()
                                  ->where('is_published', true)
                                  ->get();

        return view('participant.narasumber.show', compact('narasumber', 'kelasDiajar'));
    }
}
