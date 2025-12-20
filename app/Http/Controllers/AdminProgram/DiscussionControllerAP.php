<?php

namespace App\Http\Controllers\AdminProgram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Forum;
use App\Models\ForumPost;
use App\Models\DirectMessage;
use App\Models\User;

class DiscussionControllerAP extends Controller
{
    /**
     * Halaman Utama Diskusi.
     */
    public function index()
    {
        $user = Auth::user();
        $managedProgramIds = $user->administeredPrograms()->pluck('programs.id');

        // 1. Ambil Forum: Global + Forum Program yang dikelola
        $forums = Forum::whereNull('program_id')
                       ->orWhereIn('program_id', $managedProgramIds)
                       ->get();

        // 2. Ambil Kontak: Prioritaskan Peserta di program admin & Instruktur & SuperAdmin
        // Untuk performa, kita ambil 50 user terbaru saja atau bisa pakai search nanti
        $contacts = User::where('id', '!=', $user->id)
                        ->whereIn('role', ['participant', 'instructor', 'superadmin'])
                        ->orderBy('created_at', 'desc')
                        ->take(50)
                        ->get();

        return view('adminprogram.discussion.index', compact('forums', 'contacts'));
    }

    /**
     * Buka Forum
     */
    public function showForum($id)
    {
        $user = Auth::user();
        $activeForum = Forum::with(['posts.user'])->findOrFail($id);

        // Refresh list sidebar
        $managedProgramIds = $user->administeredPrograms()->pluck('programs.id');
        $forums = Forum::whereNull('program_id')->orWhereIn('program_id', $managedProgramIds)->get();
        $contacts = User::where('id', '!=', $user->id)->take(50)->get();

        return view('adminprogram.discussion.index', compact('forums', 'contacts', 'activeForum'));
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

        return back();
    }

    /**
     * Buka DM
     */
    public function showDm($userId)
    {
        $me = Auth::id();
        $otherUser = User::findOrFail($userId);

        $messages = DirectMessage::where(function($q) use ($me, $userId) {
                        $q->where('sender_id', $me)->where('receiver_id', $userId);
                    })
                    ->orWhere(function($q) use ($me, $userId) {
                        $q->where('sender_id', $userId)->where('receiver_id', $me);
                    })
                    ->orderBy('created_at', 'asc')
                    ->get();

        // Tandai read
        DirectMessage::where('sender_id', $userId)->where('receiver_id', $me)->update(['is_read' => true]);

        // Sidebar data
        $user = Auth::user();
        $managedProgramIds = $user->administeredPrograms()->pluck('programs.id');
        $forums = Forum::whereNull('program_id')->orWhereIn('program_id', $managedProgramIds)->get();
        $contacts = User::where('id', '!=', $user->id)->take(50)->get();

        return view('adminprogram.discussion.index', compact('forums', 'contacts', 'messages', 'otherUser'));
    }

    /**
     * Kirim DM
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
