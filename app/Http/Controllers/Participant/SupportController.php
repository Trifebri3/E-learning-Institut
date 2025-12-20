<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\SupportTicket;
use App\Models\Program;

class SupportController extends Controller
{
    /**
     * Kategori tiket yang memerlukan program
     */
    const CATEGORIES_REQUIRING_PROGRAM = ['academic', 'permission'];

    /**
     * Kategori tiket yang tidak memerlukan program
     */
    const CATEGORIES_WITHOUT_PROGRAM = ['system', 'general'];

    /**
     * Tampilkan daftar tiket user
     */
    public function index()
    {
        $tickets = SupportTicket::where('user_id', Auth::id())
                                ->with('program')
                                ->latest()
                                ->paginate(10);

        return view('participant.support.index', compact('tickets'));
    }

    /**
     * Form buat tiket baru
     */
    public function create()
    {
        $user = Auth::user();
        $myPrograms = $user->programs;

        return view('participant.support.create', compact('myPrograms'));
    }

    /**
     * Simpan tiket baru
     */
    public function store(Request $request)
    {
        $validated = $this->validateTicketRequest($request);

        $ticketData = $this->prepareTicketData($validated, $request);

        // Debug: cek data sebelum disimpan
        // logger()->info('Ticket Data:', $ticketData);

        SupportTicket::create($ticketData);

        return redirect()->route('participant.support.index')
                         ->with('success', 'Tiket bantuan berhasil dibuat. Tim terkait akan segera merespon.');
    }

    /**
     * Tampilkan detail tiket
     */
    public function show($id)
    {
        $ticket = SupportTicket::where('id', $id)
                               ->where('user_id', Auth::id())
                               ->with(['program', 'user'])
                               ->firstOrFail();

        return view('participant.support.show', compact('ticket'));
    }

    /**
     * Validasi request tiket
     */
    private function validateTicketRequest(Request $request): array
    {
        $rules = [
            'category' => 'required|in:general,academic,permission,system',
            'program_id' => 'nullable|required_if:category,academic,permission|exists:programs,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'priority' => 'required|in:low,medium,high'
        ];

        $messages = [
            'program_id.required_if' => 'Mohon pilih Program terkait untuk kategori ini.',
            'subject.required' => 'Subjek tiket harus diisi.',
            'description.required' => 'Deskripsi masalah harus diisi.',
            'attachment.mimes' => 'File lampiran harus berupa gambar (JPG, JPEG, PNG) atau PDF.',
            'attachment.max' => 'Ukuran file lampiran maksimal 2MB.'
        ];

        return $request->validate($rules, $messages);
    }

    /**
     * Persiapkan data tiket untuk disimpan
     */
    private function prepareTicketData(array $validated, Request $request): array
    {
        $data = [
            'user_id' => Auth::id(),
            'category' => $validated['category'],
            'subject' => $validated['subject'],
            'description' => $validated['description'], // Pastikan ini ada
            'priority' => $validated['priority'],
            'program_id' => $this->getProgramIdForCategory($validated)
        ];

        // Upload lampiran jika ada
        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $this->storeAttachment($request->file('attachment'));
        }

        return $data;
    }

    /**
     * Tentukan program_id berdasarkan kategori
     */
    private function getProgramIdForCategory(array $validated): ?int
    {
        if (in_array($validated['category'], self::CATEGORIES_WITHOUT_PROGRAM)) {
            return null;
        }

        return $validated['program_id'] ?? null;
    }

    /**
     * Simpan file lampiran
     */
    private function storeAttachment($file): string
    {
        return $file->store('support-attachments', 'public');
    }
}
