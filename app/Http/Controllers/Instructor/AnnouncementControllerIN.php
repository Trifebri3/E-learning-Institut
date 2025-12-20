<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // Pastikan Str diimport jika digunakan
use App\Models\Announcement;
use App\Models\Program;

class AnnouncementControllerIN extends Controller
{
    /**
     * Menampilkan daftar pengumuman program.
     */
public function index()
{
    $user = Auth::user();

    // Ambil ID program dimana user jadi instruktur
    $programIds = Program::whereHas('instructors', function ($q) use ($user) {
        $q->where('users.id', $user->id);
    })->pluck('id');

    // Daftar program untuk dropdown
    $programs = Program::whereIn('id', $programIds)->get();

    // Ambil pengumuman
    $announcements = Announcement::whereIn('program_id', $programIds)
                                 ->with(['program', 'creator'])
                                 ->latest()
                                 ->paginate(10);

    return view('instructor.announcements.index', compact('announcements', 'programs'));
}

    /**
     * Form buat pengumuman baru.
     */
public function create()
{
    $user = Auth::user();

    // Ambil program dimana user ini menjadi instruktur
    $programs = Program::whereHas('instructors', function ($q) use ($user) {
        $q->where('users.id', $user->id);
    })->get();

    if ($programs->isEmpty()) {
        return redirect()->route('instructor.announcements.index')
                         ->with('error', 'Anda belum menjadi instruktur di program apapun.');
    }

    return view('instructor.announcements.create', compact('programs'));
}


    /**
     * Simpan pengumuman.
     */
public function store(Request $request)
{
    $user = Auth::user();

    // Program valid untuk instructor ini
    $managedIds = Program::whereHas('instructors', function ($q) use ($user) {
        $q->where('users.id', $user->id);
    })->pluck('id')->toArray();

    $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'program_id' => 'required|in:' . implode(',', $managedIds),
        'priority' => 'required|in:normal,important,critical',
        'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
    ]);

    $data = [
        'title' => $request->title,
        'content' => $request->content,
        'type' => 'program',
        'program_id' => $request->program_id,
        'priority' => $request->priority,
        'created_by' => $user->id,
    ];

    if ($request->hasFile('attachment')) {
        $data['attachment_path'] = $request->file('attachment')->store('announcements', 'public');
    }

    Announcement::create($data);

    return redirect()->route('instructor.announcements.index')
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

        return redirect()->route('instructor.announcements.index')
                         ->with('success', 'Pengumuman dihapus.');
    }
}
