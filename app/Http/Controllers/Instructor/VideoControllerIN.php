<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kelas;
use App\Models\VideoEmbed;

class VideoControllerIN extends Controller
{
    /* =============================
     * 1. AUTHORIZATION CHECK
     * ============================= */

    /**
     * Check if user has access to the class
     */
    private function checkAuthorization($programId)
    {
        $user = Auth::user();
        $accessibleProgramIds = array_unique(array_merge(
            $user->administeredPrograms()->pluck('programs.id')->toArray(),
            method_exists($user, 'instructedPrograms') ? $user->instructedPrograms()->pluck('programs.id')->toArray() : []
        ));

        if (!in_array($programId, $accessibleProgramIds)) {
            abort(403, 'Akses Ditolak.');
        }
    }


    /* =============================
     * 2. MANAJEMEN VIDEO - CRUD
     * ============================= */

    /**
     * Form Tambah Video Baru.
     */
    public function create($kelasId)
    {
        $kelas = Kelas::with('program')->findOrFail($kelasId);

        // Authorization Check
        $this->checkAuthorization($kelas->program_id);

        return view('instructor.videos.create', compact('kelas'));
    }


public function store(Request $request, $kelasId)
{
    $kelas = Kelas::findOrFail($kelasId);

    $request->validate([
        'title' => 'required|string|max:255',
        'youtube_url' => 'required|string',
        'description' => 'nullable|string',
    ]);

$youtubeId = $this->extractYoutubeId($request->youtube_url);

if (!$youtubeId) {
    return back()->withInput()->withErrors(['youtube_url' => 'URL YouTube tidak valid.']);
}

VideoEmbed::create([
    'kelas_id' => $kelasId,
    'title' => $request->title,
    'youtube_id' => $youtubeId,
    'description' => $request->description,
    'is_published' => true,
]);


    return redirect()->route('instructor.kelas.edit', $kelasId)
                     ->with('success', 'Video pembelajaran berhasil ditambahkan.');
}


    /**
     * Form Edit Video.
     */
public function edit($id)
{
    $video = VideoEmbed::with('kelas.program')->findOrFail($id);
    $this->checkAuthorization($video->kelas->program_id); // pastikan user admin program yang sesuai

    return view('instructor.videos.edit', compact('video'));
}

public function update(Request $request, $id)
{
    $video = VideoEmbed::with('kelas')->findOrFail($id);
    $this->checkAuthorization($video->kelas->program_id);

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'youtube_url' => 'required|string',
        'description' => 'nullable|string',
        'is_published' => 'sometimes|boolean',
    ]);

    // Ambil ID YouTube dari URL
    $youtubeId = $this->extractYoutubeId($validated['youtube_url']);
    if (!$youtubeId) {
        return back()->withInput()->withErrors(['youtube_url' => 'URL YouTube tidak valid.']);
    }

    $video->update([
        'title' => $validated['title'],
        'youtube_id' => $youtubeId,
        'description' => $validated['description'] ?? null,
        'is_published' => $validated['is_published'] ?? true,
    ]);

    return redirect()->route('instructor.kelas.edit', $video->kelas_id)
                     ->with('success', 'Video berhasil diperbarui.');
}


    /**
     * Hapus Video.
     */
    public function destroy($id)
    {
        $video = VideoEmbed::with('kelas')->findOrFail($id);
        $kelasId = $video->kelas_id;

        // Authorization Check
        $this->checkAuthorization($video->kelas->program_id);

        $video->delete();

        return redirect()->route('instructor.kelas.edit', $kelasId)
                         ->with('success', 'Video berhasil dihapus.');
    }

    /* =============================
     * 3. METHOD TAMBAHAN
     * ============================= */

    /**
     * Toggle Status Publikasi Video
     */
    public function togglePublish($id)
    {
        $video = VideoEmbed::with('kelas')->findOrFail($id);

        // Authorization Check
        $this->checkAuthorization($video->kelas->program_id);

        $video->update([
            'is_published' => !$video->is_published
        ]);

        $status = $video->is_published ? 'dipublikasikan' : 'disembunyikan';
        return back()->with('success', "Video berhasil $status.");
    }

    /**
     * Preview Video
     */
    public function preview($id)
    {
        $video = VideoEmbed::with('kelas.program')->findOrFail($id);

        // Authorization Check
        $this->checkAuthorization($video->kelas->program_id);

        return view('instructor.videos.preview', compact('video'));
    }

    /* =============================
     * 4. HELPER METHODS
     * ============================= */

    /**
     * Helper: Ambil ID dari URL Youtube
     */
    private function extractYoutubeId($url)
    {
        // Jika input sudah pendek (kemungkinan ID), kembalikan saja
        if (strlen($url) <= 11 && preg_match('/^[a-zA-Z0-9_-]{11}$/', $url)) {
            return $url;
        }

        // Pola regex untuk berbagai format URL YouTube
        $patterns = [
            '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\\s]{11})%i',
            '%youtube\.com/embed/([^"&?/\\s]{11})%i',
            '%youtube\.com/watch\?v=([^"&?/\\s]{11})%i',
            '%youtu\.be/([^"&?/\\s]{11})%i',
                    '%youtube\.com/live/([^"&?/\\s]{11})%i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $match)) {
                return $match[1];
            }
        }

        return null; // Return null jika tidak valid
    }

    /**
     * Validasi YouTube URL
     */
    private function validateYouTubeUrl($url)
    {
        $youtubeId = $this->extractYoutubeId($url);
        return $youtubeId !== null;
    }
}
