<?php

namespace App\Http\Controllers\AdminProgram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kelas;
use App\Models\VideoEmbed;

class VideoControllerAP extends Controller
{
    /**
     * Form Tambah Video Baru.
     */
    public function create($kelasId)
    {
        $user = Auth::user();
        $kelas = Kelas::with('program')->findOrFail($kelasId);

        // Security Check
        if (!$user->administeredPrograms->contains($kelas->program_id)) {
            abort(403, 'Akses Ditolak.');
        }

        return view('adminprogram.videos.create', compact('kelas'));
    }

    /**
     * Simpan Video Baru.
     */
    public function store(Request $request, $kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);

        $request->validate([
            'title' => 'required|string|max:255',
            'youtube_url' => 'required|string', // Kita minta URL/ID
            'description' => 'nullable|string',
        ]);

        // Ekstrak ID Youtube
        $youtubeId = $this->extractYoutubeId($request->youtube_url);

        VideoEmbed::create([
            'kelas_id' => $kelasId,
            'title' => $request->title,
            'youtube_id' => $youtubeId,
            'description' => $request->description,
            'is_published' => $request->has('is_published'),
        ]);

        return redirect()->route('adminprogram.kelas.edit', $kelasId)
                         ->with('success', 'Video pembelajaran berhasil ditambahkan.');
    }

    /**
     * Form Edit Video.
     */
    public function edit($id)
    {
        $video = VideoEmbed::with('kelas.program')->findOrFail($id);
        $user = Auth::user();

        if (!$user->administeredPrograms->contains($video->kelas->program_id)) {
            abort(403, 'Akses Ditolak.');
        }

        return view('adminprogram.videos.edit', compact('video'));
    }

    /**
     * Update Video.
     */
    public function update(Request $request, $id)
    {
        $video = VideoEmbed::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'youtube_url' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $youtubeId = $this->extractYoutubeId($request->youtube_url);

        $video->update([
            'title' => $request->title,
            'youtube_id' => $youtubeId,
            'description' => $request->description,
            'is_published' => $request->has('is_published'),
        ]);

        return redirect()->route('adminprogram.kelas.edit', $video->kelas_id)
                         ->with('success', 'Video berhasil diperbarui.');
    }

    /**
     * Hapus Video.
     */
    public function destroy($id)
    {
        $video = VideoEmbed::findOrFail($id);
        $kelasId = $video->kelas_id;
        $video->delete();

        return redirect()->route('adminprogram.kelas.edit', $kelasId)
                         ->with('success', 'Video berhasil dihapus.');
    }

    /**
     * Helper: Ambil ID dari URL Youtube
     */
    private function extractYoutubeId($url)
    {
        // Jika input sudah pendek (kemungkinan ID), kembalikan saja
        if (strlen($url) == 11) return $url;

        // Pola regex untuk URL Youtube standar, short, dan embed
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);

        return $match[1] ?? $url; // Kembalikan ID jika ketemu, atau string aslinya
    }
}
