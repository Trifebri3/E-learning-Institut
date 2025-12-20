<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Forum;
use App\Models\ForumPost;
use App\Models\DirectMessage;
use App\Models\User;

class DiscussionController extends Controller
{
    /**
     * Halaman Utama Diskusi.
     * Menampilkan daftar Forum & Daftar Kontak.
     */
    public function index()
    {
        $user = Auth::user();
        $programIds = $user->programs()->pluck('programs.id');

        // 1. Ambil Forum (Global ATAU Program yang diikuti user)
        $forums = Forum::whereNull('program_id')
                        ->orWhereIn('program_id', $programIds)
                        ->get();

        // 2. Ambil Kontak (User lain yang satu program / Admin / Instruktur)
        // Sederhananya: Kita ambil semua user kecuali diri sendiri (bisa difilter lagi nanti)
        $contacts = User::where('id', '!=', $user->id)
                        ->whereIn('role', ['superadmin', 'adminprogram', 'instructor', 'participant'])
                        ->take(20) // Limit agar tidak berat
                        ->get();

        return view('participant.discussion.index', compact('forums', 'contacts'));
    }

    /**
     * Buka Chat Room Forum
     */
    public function showForum($id)
    {
        $user = Auth::user();
        $programIds = $user->programs()->pluck('programs.id');

        // Load Forum & Pesan
        $activeForum = Forum::with(['posts.user'])
                            ->where(function($q) use ($programIds) {
                                $q->whereNull('program_id')->orWhereIn('program_id', $programIds);
                            })
                            ->findOrFail($id);

        // Load list lagi untuk sidebar
        $forums = Forum::whereNull('program_id')->orWhereIn('program_id', $programIds)->get();
        $contacts = User::where('id', '!=', $user->id)->take(20)->get();

        return view('participant.discussion.index', compact('forums', 'contacts', 'activeForum'));
    }

    /**
     * Kirim Pesan Forum
     */
    public function storeForumMessage(Request $request, $id)
    {
        $request->validate(['message' => 'required|string']);

        ForumPost::create([
            'forum_id' => $id,
            'user_id' => Auth::id(),
            'message' => $request->message
        ]);

        return back(); // Refresh halaman untuk melihat pesan baru
    }

    /**
     * Buka Chat Room Direct Message (DM)
     */
    public function showDm($userId)
    {
        $me = Auth::id();
        $otherUser = User::findOrFail($userId);

        // Ambil percakapan (Saya ke Dia ATAU Dia ke Saya)
        $messages = DirectMessage::where(function($q) use ($me, $userId) {
                        $q->where('sender_id', $me)->where('receiver_id', $userId);
                    })
                    ->orWhere(function($q) use ($me, $userId) {
                        $q->where('sender_id', $userId)->where('receiver_id', $me);
                    })
                    ->orderBy('created_at', 'asc')
                    ->get();

        // Tandai sudah dibaca
        DirectMessage::where('sender_id', $userId)->where('receiver_id', $me)->update(['is_read' => true]);

        // Load list sidebar
        $user = Auth::user();
        $programIds = $user->programs()->pluck('programs.id');
        $forums = Forum::whereNull('program_id')->orWhereIn('program_id', $programIds)->get();
        $contacts = User::where('id', '!=', $user->id)->take(20)->get();

        return view('participant.discussion.index', compact('forums', 'contacts', 'messages', 'otherUser'));
    }

    /**
     * Kirim Pesan DM
     */
    public function storeDm(Request $request, $userId)
    {
        $request->validate(['message' => 'required|string']);

        DirectMessage::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $userId,
            'message' => $request->message
        ]);

        return back();
    }
}
