<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Forum;
use App\Models\ForumPost;
use App\Models\DirectMessage;
use App\Models\User;

class DiscussionControllerSA extends Controller
{
    /**
     * Halaman Utama Diskusi Admin.
     */
    public function index()
    {
        $user = Auth::user();

        // 1. Admin bisa melihat SEMUA Forum (Global & Program)
        $forums = Forum::all();

        // 2. Kontak: Admin bisa chat dengan Siapa Saja
        // Kita ambil user terbaru atau bisa ditambahkan fitur search nanti
        $contacts = User::where('id', '!=', $user->id)
                        ->orderBy('created_at', 'desc')
                        ->take(30)
                        ->get();

        return view('superadmin.discussion.index', compact('forums', 'contacts'));
    }

    /**
     * Buka Forum
     */
    public function showForum($id)
    {
        $user = Auth::user();

        $activeForum = Forum::with(['posts.user'])->findOrFail($id);

        $forums = Forum::all();
        $contacts = User::where('id', '!=', $user->id)->take(30)->get();

        return view('superadmin.discussion.index', compact('forums', 'contacts', 'activeForum'));
    }

    /**
     * Kirim Pesan di Forum
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
     * Buka DM dengan User
     */
public function showDm($userId)
{
    $me = Auth::id();
    $user = Auth::user(); // <-- INI WAJIB ADA
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

    $forums = Forum::all();
    $contacts = User::where('id', '!=', $user->id)->take(30)->get();

    return view('superadmin.discussion.index', compact('forums', 'contacts', 'messages', 'otherUser'));
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
