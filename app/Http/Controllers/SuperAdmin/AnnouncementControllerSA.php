<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AnnouncementControllerSA extends Controller
{
    /**
     * Menampilkan daftar pengumuman yang dibuat.
     */
    public function index()
    {
        // Tampilkan pengumuman terbaru dulu
        $announcements = Announcement::with(['creator', 'program'])
                                     ->latest()
                                     ->paginate(10);

        return view('superadmin.announcements.index', compact('announcements'));
    }

    /**
     * Form buat pengumuman baru.
     */
    public function create()
    {
        // Ambil daftar program untuk dropdown
        $programs = Program::all();
        return view('superadmin.announcements.create', compact('programs'));
    }

    /**
     * Simpan pengumuman.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:global,program',
            // Program wajib dipilih JIKA tipe = program
            'program_id' => 'nullable|required_if:type,program|exists:programs,id',
            'priority' => 'required|in:normal,important,critical',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        $data = $request->except('attachment');
        $data['created_by'] = Auth::id();

        // Jika global, pastikan program_id null
        if ($request->type == 'global') {
            $data['program_id'] = null;
        }

        // Handle Upload Lampiran
        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')->store('announcements', 'public');
        }

        Announcement::create($data);

        return redirect()->route('superadmin.announcements.index')
                         ->with('success', 'Pengumuman berhasil diterbitkan.');
    }

    /**
     * Hapus pengumuman.
     */
    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);

        // Hapus file jika ada
        if ($announcement->attachment_path) {
            Storage::disk('public')->delete($announcement->attachment_path);
        }

        $announcement->delete();

        return redirect()->route('superadmin.announcements.index')
                         ->with('success', 'Pengumuman berhasil dihapus.');
    }
}
