<?php

namespace App\Http\Controllers\AdminProgram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Kelas;
use App\Models\Program;
use App\Models\Narasumber;
use App\Models\PresensiSetup;

class KelasControllerAP extends Controller
{
    /**
     * Menampilkan daftar kelas dari program yang dikelola.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Ambil ID program yang dikelola admin
        $programIds = $user->administeredPrograms()->pluck('programs.id');

        $query = Kelas::whereIn('program_id', $programIds)
            ->with(['program', 'narasumbers'])
            ->orderBy('tanggal', 'desc');

        // Filter Program
        if ($request->has('program_id') && $request->program_id != '') {
            $query->where('program_id', $request->program_id);
        }

        $kelas = $query->paginate(10);
        $programs = $user->administeredPrograms; // Untuk filter dropdown

        return view('adminprogram.kelas.index', compact('kelas', 'programs'));
    }

    /**
     * Form tambah kelas baru.
     */
    public function create()
    {
        $user = Auth::user();
        $programs = $user->administeredPrograms; // Hanya program dia

        if ($programs->isEmpty()) {
            return redirect()->route('adminprogram.programs.index')
                ->with('error', 'Anda belum mengelola program apapun.');
        }

        return view('adminprogram.kelas.create', compact('programs'));
    }

    /**
     * Simpan kelas baru.
     */
    public function store(Request $request)
    {
        $rules = [
            'program_id' => 'required|exists:programs,id',
            'title' => 'required|string|max:255',
            'tipe' => 'required|in:materi,interaktif',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'nullable|after:jam_mulai',
            'tempat' => 'required|string',
            'deskripsi' => 'required|string',
            'banner' => 'nullable|image|max:2048',
        ];

        // Validasi Khusus: Jika Interaktif, Link Zoom WAJIB
        if ($request->tipe == 'interaktif') {
            $rules['link_zoom'] = 'required|url';
        }

        $request->validate($rules);

        // Cek Kepemilikan Program
        $user = Auth::user();
        if (!$user->administeredPrograms->contains($request->program_id)) {
            abort(403, 'Anda tidak berhak menambahkan kelas di program ini.');
        }

        $data = $request->except('banner');

        if ($request->hasFile('banner')) {
            $data['banner_path'] = $request->file('banner')->store('images/kelas', 'public');
        }

        $kelas = Kelas::create($data);

        return redirect()->route('adminprogram.kelas.edit', $kelas->id)
            ->with('success', 'Kelas berhasil dibuat. Silakan atur komponen lainnya.');
    }

    /**
     * Halaman Edit (DASHBOARD KELAS).
     * Di sini admin bisa atur Narasumber, Presensi, dan Komponen lain.
     */
    public function edit($id)
    {
        $kelas = Kelas::with([
            'program', 'narasumbers', 'presensiSetup',
            'modules', 'videoEmbeds', 'assignments',
            'quizzes', 'essayExams', 'learningPath'
        ])->findOrFail($id);

        $user = Auth::user();
        if (!$user->administeredPrograms->contains($kelas->program_id)) {
            abort(403, 'Akses Ditolak.');
        }

        // Ambil exams dengan pagination
        $exams = $kelas->essayExams()->with(['questions','submissions'])->paginate(5);

        // Ambil assignments
        $assignments = $kelas->assignments;

        // Ambil Narasumber yang tersedia di PROGRAM ini
        $availableNarasumbers = Narasumber::where('program_id', $kelas->program_id)->get();

        return view('adminprogram.kelas.edit', compact('kelas', 'availableNarasumbers', 'exams', 'assignments'));
    }

    /**
     * Update Info Dasar & Narasumber & Presensi.
     */
    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $rules = [
            'title' => 'required|string|max:255',
            'tipe' => 'required|in:materi,interaktif',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'nullable|after:jam_mulai',
            'tempat' => 'required|string',
            'deskripsi' => 'required|string',
            'banner' => 'nullable|image|max:2048',
            'narasumber_ids' => 'nullable|array',
            'narasumber_ids.*' => 'exists:narasumbers,id',
        ];

        if ($request->tipe == 'interaktif') {
            $rules['link_zoom'] = 'required|url';
        }

        $request->validate($rules);

        // Update Data Kelas
        $data = $request->except(['banner', 'narasumber_ids', 'presensi']);

        if ($request->hasFile('banner')) {
            if ($kelas->banner_path) {
                Storage::disk('public')->delete($kelas->banner_path);
            }
            $data['banner_path'] = $request->file('banner')->store('images/kelas', 'public');
        }

        $kelas->update($data);

        // Sync Narasumber
        $kelas->narasumbers()->sync($request->input('narasumber_ids', []));

        // Update/Create Presensi Setup
        if ($request->has('presensi')) {
            $pData = $request->presensi;
            PresensiSetup::updateOrCreate(
                ['kelas_id' => $kelas->id],
                [
                    'token_awal' => $pData['token_awal'],
                    'token_akhir' => $pData['token_akhir'],
                    'buka_awal' => $pData['buka_awal'],
                    'tutup_awal' => $pData['tutup_awal'],
                    'buka_akhir' => $pData['buka_akhir'],
                    'tutup_akhir' => $pData['tutup_akhir'],
                    'is_active' => isset($pData['is_active']), // Checkbox
                ]
            );
        }

        return redirect()->route('adminprogram.kelas.edit', $kelas->id)
            ->with('success', 'Data kelas diperbarui.');
    }

    /**
     * Hapus Kelas.
     */
    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);

        // Security Check
        $kelas->delete();

        return redirect()->route('adminprogram.kelas.index')
            ->with('success', 'Kelas dihapus.');
    }

    /**
     * Toggle Publish Kelas.
     */
    public function togglePublish($id)
    {
        $kelas = Kelas::findOrFail($id);
        $user = Auth::user();

        if (!$user->administeredPrograms->contains($kelas->program_id)) {
            abort(403, 'Akses Ditolak.');
        }

        $kelas->update(['is_published' => !$kelas->is_published]);
        $status = $kelas->is_published ? 'dipublikasikan' : 'tidak dipublikasikan';

        return back()->with('success', "Kelas berhasil $status.");
    }
}
