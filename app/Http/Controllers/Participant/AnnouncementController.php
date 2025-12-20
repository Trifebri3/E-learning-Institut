<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Ambil ID Program yang diikuti user
        $enrolledProgramIds = $user->programs()->pluck('programs.id');

        // 2. Ambil Pengumuman:
        //    - Tipe Global
        //    - ATAU Tipe Program (dimana program_id ada di daftar enrolled user)
        $announcements = Announcement::where(function($query) use ($enrolledProgramIds) {
                                $query->where('type', 'global')
                                      ->orWhereIn('program_id', $enrolledProgramIds);
                            })
                            ->orderBy('created_at', 'desc')
                            ->paginate(10); // Paginasi agar rapi

        return view('participant.announcements.index', compact('announcements'));
    }

    public function markAsRead($id)
    {
        $user = Auth::user();
        $announcement = Announcement::findOrFail($id);

        // Catat bahwa user sudah membaca (gunakan syncWithoutDetaching agar tidak error jika klik 2x)
        $user->readAnnouncements()->syncWithoutDetaching([
            $announcement->id => ['read_at' => now()]
        ]);

        return back()->with('success', 'Terima kasih, informasi telah ditandai sebagai diterima.');
    }
}
