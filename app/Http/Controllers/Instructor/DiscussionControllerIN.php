<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Forum;
use App\Models\ForumPost;
use App\Models\DirectMessage;
use App\Models\User;
use App\Models\Program;

class DiscussionControllerIN extends Controller
{
    /**
     * Halaman Utama Diskusi.
     */
    public function index()
    {
        $user = Auth::user();

        // Ambil ID program dimana user jadi instruktur
        $programIds = Program::whereHas('instructors', function ($q) use ($user) {
            $q->where('users.id', $user->id);
        })->pluck('id');

        // Ambil Forum: Global + Forum Program yang user instruktur
        $forums = Forum::whereNull('program_id')
                       ->orWhereIn('program_id', $programIds)
                       ->get();

        // Ambil Kontak: Peserta, Instruktur, SuperAdmin (50 terbaru)
        $contacts = User::where('id', '!=', $user->id)
                        ->whereIn('role', ['participant', 'instructor', 'superadmin'])
                        ->orderBy('created_at', 'desc')
                        ->take(50)
                        ->get();

        return view('instructor.discussion.index', compact('forums', 'contacts'));
    }

    /**
     * Buka Forum
     */
    public function showForum($id)
    {
        $user = Auth::user();

        $activeForum = Forum::with(['posts.user'])->findOrFail($id);

        // Refresh sidebar: Forum global + forum program instruktur
        $programIds = Program::whereHas('instructors', function ($q) use ($user) {
            $q->where('users.id', $user->id);
        })->pluck('id');

        $forums = Forum::whereNull('program_id')
                       ->orWhereIn('program_id', $programIds)
                       ->get();

        $contacts = User::where('id', '!=', $user->id)
                        ->take(50)
                        ->get();

        return view('instructor.discussion.index', compact('forums', 'contacts', 'activeForum'));
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
        DirectMessage::where('sender_id', $userId)->where('receiver_id', $me)
                     ->update(['is_read' => true]);

        // Sidebar
        $user = Auth::user();
        $programIds = Program::whereHas('instructors', function ($q) use ($user) {
            $q->where('users.id', $user->id);
        })->pluck('id');

        $forums = Forum::whereNull('program_id')->orWhereIn('program_id', $programIds)->get();
        $contacts = User::where('id', '!=', $user->id)->take(50)->get();

        return view('instructor.discussion.index', compact('forums', 'contacts', 'messages', 'otherUser'));
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
