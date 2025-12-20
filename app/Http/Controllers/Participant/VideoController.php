<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VideoEmbed;

class VideoController extends Controller
{
    /**
     * Tampilkan halaman video
     */
    public function show($id)
    {
        $user = Auth::user();
        $video = VideoEmbed::with('kelas.program')->findOrFail($id);

        // Cek apakah user punya akses ke program video ini
        $userProgramIds = $user->programs()->pluck('programs.id')->toArray();
        if (!in_array($video->kelas->program_id, $userProgramIds)) {
            abort(403, 'Akses ditolak. Video bukan milik program Anda.');
        }

        // Cek apakah video sudah ditonton
        $isWatched = $user->watchedVideos()
            ->where('video_embed_id', $video->id)
            ->exists();

        return view('participant.video.show', compact('video', 'isWatched'));
    }

    /**
     * Tandai video sebagai sudah ditonton
     */
    public function complete($id)
    {
        $user = Auth::user();
        $video = VideoEmbed::findOrFail($id);

        // Pastikan user punya akses ke program
        $userProgramIds = $user->programs()->pluck('programs.id')->toArray();
        if (!in_array($video->kelas->program_id, $userProgramIds)) {
            return response()->json([
                'success' => false,
                'error' => 'Akses ditolak. Video bukan milik program Anda.'
            ], 403);
        }

        try {
            // Cek apakah sudah ditandai watched
            $alreadyWatched = $user->watchedVideos()
                ->where('video_embed_id', $id)
                ->exists();

            if (!$alreadyWatched) {
                // Tandai video sebagai watched
                $user->watchedVideos()->attach($id, [
                    'watched_at' => now(),
                ]);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
