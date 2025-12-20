<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupportTicketControllerIN extends Controller
{
    /**
     * Daftar tiket program yang dikelola instruktur.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Ambil program dimana user jadi instruktur
        $programs = Program::whereIn('id', function($query) use ($user) {
            $query->select('program_id')
                  ->from('program_instructor')
                  ->where('user_id', $user->id);
        })->get();

        $programIds = $programs->pluck('id');

        // Jika tidak ada program, return empty
        if ($programIds->isEmpty()) {
            $tickets = collect([]);
            $counts = [
                'open' => 0,
                'process' => 0,
                'closed' => 0,
                'total' => 0
            ];

            return view('instructor.support.index', compact('tickets', 'counts', 'programs'));
        }

        $query = SupportTicket::whereIn('program_id', $programIds)
                              ->with(['user', 'program']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by program
        if ($request->filled('program_id') && in_array($request->program_id, $programIds->toArray())) {
            $query->where('program_id', $request->program_id);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Urutkan berdasarkan status dan created_at
        $query->orderByRaw("FIELD(status, 'open', 'in_progress', 'resolved', 'closed')")
              ->latest();

        $tickets = $query->paginate(10);

        // Hitung total untuk badge
        $counts = $this->getTicketCounts($programIds, $request);

        return view('instructor.support.index', compact('tickets', 'counts', 'programs'));
    }

    /**
     * Detail & balas tiket.
     */
    public function show($id)
    {
        $user = Auth::user();

        // Ambil program IDs dimana user jadi instruktur
        $programIds = Program::whereIn('id', function($query) use ($user) {
            $query->select('program_id')
                  ->from('program_instructor')
                  ->where('user_id', $user->id);
        })->pluck('id');

        $ticket = SupportTicket::whereIn('program_id', $programIds)
                               ->with([
                                   'user' => function($query) {
                                       $query->select('id', 'name', 'email');
                                   },
                                   'program'
                               ])
                               ->findOrFail($id);

        return view('instructor.support.show', compact('ticket'));
    }

    /**
     * Update status & balasan admin.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        // Ambil program IDs dimana user jadi instruktur
        $programIds = Program::whereIn('id', function($query) use ($user) {
            $query->select('program_id')
                  ->from('program_instructor')
                  ->where('user_id', $user->id);
        })->pluck('id');

        $ticket = SupportTicket::whereIn('program_id', $programIds)->findOrFail($id);

        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
            'admin_reply' => 'nullable|string|max:1000',
        ]);

        $updateData = [
            'status' => $request->status,
        ];

        if ($request->filled('admin_reply')) {
            $updateData['admin_reply'] = $request->admin_reply;
        }

        $ticket->update($updateData);

        return redirect()->route('instructor.support.show', $id)
                         ->with('success', 'Tiket berhasil diperbarui.');
    }

    /**
     * Hitung jumlah tiket berdasarkan status
     */
    private function getTicketCounts($programIds, $request = null)
    {
        if ($programIds->isEmpty()) {
            return [
                'open' => 0,
                'process' => 0,
                'closed' => 0,
                'total' => 0
            ];
        }

        $query = SupportTicket::whereIn('program_id', $programIds);

        // Apply same filters as main query for accurate counts
        if ($request) {
            if ($request->filled('program_id') && in_array($request->program_id, $programIds->toArray())) {
                $query->where('program_id', $request->program_id);
            }
            if ($request->filled('priority')) {
                $query->where('priority', $request->priority);
            }
        }

        $statusCounts = $query->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return [
            'open' => $statusCounts['open'] ?? 0,
            'process' => $statusCounts['in_progress'] ?? 0,
            'closed' => ($statusCounts['resolved'] ?? 0) + ($statusCounts['closed'] ?? 0),
            'total' => array_sum($statusCounts)
        ];
    }
}
