<?php

namespace App\Http\Controllers\AdminProgram;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportTicketControllerAP extends Controller
{
    /**
     * Menampilkan daftar tiket pada program yang dikelola.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $programIds = $user->administeredPrograms()->pluck('programs.id');

        // Ambil tiket yang program_id-nya termasuk dalam program yang dikelola admin ini
        $query = SupportTicket::whereIn('program_id', $programIds)
                              ->with(['user', 'program']);

        // Filter Status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        } else {
            $query->orderByRaw("FIELD(status, 'open', 'in_progress', 'resolved', 'closed')");
        }

        $tickets = $query->latest()->paginate(10);

        // Hitung total untuk badge (hanya scope program ini)
        $baseCount = SupportTicket::whereIn('program_id', $programIds);
        $counts = [
            'open' => (clone $baseCount)->where('status', 'open')->count(),
            'process' => (clone $baseCount)->where('status', 'in_progress')->count(),
            'closed' => (clone $baseCount)->whereIn('status', ['resolved', 'closed'])->count(),
        ];

        return view('adminprogram.support.index', compact('tickets', 'counts'));
    }

    /**
     * Detail & Balas Tiket.
     */
    public function show($id)
    {
        $user = Auth::user();
        $programIds = $user->administeredPrograms()->pluck('programs.id');

        // Security Check: Pastikan tiket ini milik program yang dikelola
        $ticket = SupportTicket::whereIn('program_id', $programIds)
                               ->with(['user.profile', 'program'])
                               ->findOrFail($id);

        return view('adminprogram.support.show', compact('ticket'));
    }

    /**
     * Update Status & Feedback.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $programIds = $user->administeredPrograms()->pluck('programs.id');

        $ticket = SupportTicket::whereIn('program_id', $programIds)->findOrFail($id);

        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
            'admin_reply' => 'nullable|string',
        ]);

        $ticket->update([
            'status' => $request->status,
            'admin_reply' => $request->admin_reply,
        ]);

        return redirect()->route('adminprogram.support.show', $id)
                         ->with('success', 'Tiket berhasil diperbarui.');
    }
}
