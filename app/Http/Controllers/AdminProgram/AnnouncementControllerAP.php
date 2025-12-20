<?php

namespace App\Http\Controllers\AdminProgram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // Pastikan Str diimport jika digunakan
use App\Models\Announcement;
use App\Models\Program;

class AnnouncementControllerAP extends Controller
{
    /**
     * Menampilkan daftar pengumuman program.
     */
public function index()
{
    $user = Auth::user();

    // Ambil ID program yang dikelola admin ini
    $programIds = $user->administeredPrograms()->pluck('programs.id');

    // Daftar program untuk dropdown
    $programs = \App\Models\Program::whereIn('id', $programIds)->get();

    // Ambil pengumuman
    $announcements = Announcement::whereIn('program_id', $programIds)
                                 ->with(['program', 'creator'])
                                 ->latest()
                                 ->paginate(10);

    return view('adminprogram.announcements.index', compact('announcements', 'programs'));
}

    /**
     * Form buat pengumuman baru.
     */
    public function create()
    {
        $user = Auth::user();
        // Hanya program yang dikelola admin ini yang muncul di dropdown
        $programs = $user->administeredPrograms;

        if ($programs->isEmpty()) {
            return redirect()->route('adminprogram.announcements.index')
                             ->with('error', 'Anda belum mengelola program apapun.');
        }

        return view('adminprogram.announcements.create', compact('programs'));
    }

    /**
     * Simpan pengumuman.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        // Ambil daftar ID program yang valid untuk admin ini
        $managedIds = $user->administeredPrograms()->pluck('programs.id')->toArray();

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            // Validasi program_id harus milik admin ini
            'program_id' => 'required|in:' . implode(',', $managedIds),
            'priority' => 'required|in:normal,important,critical',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'type' => 'program', // Selalu 'program'
            'program_id' => $request->program_id,
            'priority' => $request->priority,
            'created_by' => $user->id,
        ];

        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')->store('announcements', 'public');
        }

        Announcement::create($data);

        return redirect()->route('adminprogram.announcements.index')
                         ->with('success', 'Pengumuman program berhasil diterbitkan.');
    }

    /**
     * Hapus pengumuman.
     */
    public function destroy($id)
    {
        // Pastikan admin hanya bisa hapus pengumuman buatannya sendiri atau di programnya
        $announcement = Announcement::where('created_by', Auth::id())->findOrFail($id);

        if ($announcement->attachment_path) {
            Storage::disk('public')->delete($announcement->attachment_path);
        }

        $announcement->delete();

        return redirect()->route('adminprogram.announcements.index')
                         ->with('success', 'Pengumuman dihapus.');
    }
}
