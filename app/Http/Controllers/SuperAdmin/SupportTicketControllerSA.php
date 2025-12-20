<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SupportTicketControllerSA extends Controller
{
    /**
     * Menampilkan daftar tiket.
     */
    public function index(Request $request)
    {
        $query = SupportTicket::with(['user', 'program']);

        // Filter Status (Default: Open & In Progress)
        if ($request->has('status')) {
            $query->where('status', $request->status);
        } else {
            // Jika tidak ada filter, tampilkan yang belum selesai dulu
            $query->orderByRaw("FIELD(status, 'open', 'in_progress', 'resolved', 'closed')");
        }

        // Filter Kategori
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        $tickets = $query->latest()->paginate(10);

        // Hitung total untuk badge di tab
        $counts = [
            'open' => SupportTicket::where('status', 'open')->count(),
            'process' => SupportTicket::where('status', 'in_progress')->count(),
            'closed' => SupportTicket::whereIn('status', ['resolved', 'closed'])->count(),
        ];

        return view('superadmin.support.index', compact('tickets', 'counts'));
    }

    /**
     * Menampilkan detail tiket dan form balasan.
     */
    public function show($id)
    {
        $ticket = SupportTicket::with(['user.profile', 'program'])->findOrFail($id);
        return view('superadmin.support.show', compact('ticket'));
    }

    /**
     * Mengupdate status dan memberikan balasan.
     */
    public function update(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);

        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
            'admin_reply' => 'nullable|string',
        ]);

        // Update data
        $ticket->update([
            'status' => $request->status,
            'admin_reply' => $request->admin_reply,
        ]);

        // (Opsional) Di sini Anda bisa menambahkan Notifikasi Email ke user
        // Mail::to($ticket->user->email)->send(new TicketReplied($ticket));

        return redirect()->route('superadmin.support.show', $id)
                         ->with('success', 'Tiket berhasil diperbarui.');
    }

    /**
     * Hapus tiket (Hanya jika perlu).
     */
    public function destroy($id)
    {
        $ticket = SupportTicket::findOrFail($id);

        if ($ticket->attachment_path) {
            Storage::disk('public')->delete($ticket->attachment_path);
        }

        $ticket->delete();

        return redirect()->route('superadmin.support.index')->with('success', 'Tiket dihapus.');
    }
}
