<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kelas;
use App\Models\PresensiSetup;
use App\Models\PresensiHasil;
use App\Models\Program;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

class PresensiControllerIN extends Controller
{
    /**
     * Menampilkan Halaman Kelola Presensi (Setup & Monitoring).
     */
    public function edit($kelasId)
    {
        $user = Auth::user();
        $kelas = Kelas::with(['presensiSetup', 'program'])->findOrFail($kelasId);

        // Security Check
        if (!$user->administeredPrograms->contains($kelas->program_id)) {
            abort(403, 'Akses Ditolak.');
        }

        // Ambil data peserta yang sudah presensi untuk monitoring
        $attendances = PresensiHasil::with(['user', 'nomorInduk'])
                                    ->where('kelas_id', $kelasId)
                                    ->get();

        return view('instructor.presensi.edit', compact('kelas', 'attendances'));
    }

    /**
     * Simpan Konfigurasi Presensi (Token & Waktu).
     */
    public function update(Request $request, $kelasId)
    {
        $kelas = Kelas::with('program')->findOrFail($kelasId);

        // Security Check
        $user = Auth::user();
        if (!$user->administeredPrograms->contains($kelas->program_id)) {
            abort(403, 'Akses Ditolak.');
        }

        // Validasi
        $request->validate([
            'token_awal' => 'required|string|max:10',
            'buka_awal' => 'required|date',
            'tutup_awal' => 'required|date|after:buka_awal',
            'token_akhir' => 'required|string|max:10',
            'buka_akhir' => 'required|date',
            'tutup_akhir' => 'required|date|after:buka_akhir',
        ]);

        // Update or Create
        PresensiSetup::updateOrCreate(
            ['kelas_id' => $kelasId],
            [
                'token_awal' => strtoupper($request->token_awal),
                'buka_awal' => $request->buka_awal,
                'tutup_awal' => $request->tutup_awal,
                'token_akhir' => strtoupper($request->token_akhir),
                'buka_akhir' => $request->buka_akhir,
                'tutup_akhir' => $request->tutup_akhir,
                'is_active' => $request->has('is_active'), // Checkbox handling
            ]
        );

        return back()->with('success', 'Konfigurasi presensi berhasil disimpan.');
    }

    /**
     * Reset/Hapus Presensi satu peserta (Jika ada kesalahan input).
     */
    public function destroy($hasilId)
    {
        $presensi = PresensiHasil::with('kelas.program')->findOrFail($hasilId);

        // Security Check
        $user = Auth::user();
        if (!$user->administeredPrograms->contains($presensi->kelas->program_id)) {
            abort(403, 'Akses Ditolak.');
        }

        $presensi->delete();

        return back()->with('success', 'Data presensi peserta dihapus.');
    }

    /**
     * Export Laporan Presensi untuk Seluruh Program.
     */
    public function exportProgram(Request $request)
    {
        $request->validate(['program_id' => 'required|exists:programs,id']);

        $user = Auth::user();
        $programId = $request->program_id;

        // Security Check: Pastikan admin mengelola program ini
        if (!$user->administeredPrograms->contains($programId)) {
            abort(403, 'Akses Ditolak.');
        }

        // 1. Ambil Data Program & Kelas
        $program = Program::with(['kelas' => function($q) {
            $q->orderBy('tanggal', 'asc'); // Urutkan kelas berdasarkan tanggal
        }])->findOrFail($programId);

        // 2. Ambil Peserta yang terdaftar di program ini
        $participants = User::whereHas('programs', function($q) use ($programId) {
            $q->where('program_id', $programId);
        })->with(['nomorInduk' => function($q) use ($programId) {
            $q->where('program_id', $programId); // PERBAIKAN: nomorInduk (singular)
        }])->orderBy('name', 'asc')->get();

        // 3. Ambil Data Presensi (Bulk Fetching untuk performa)
        $kelasIds = $program->kelas->pluck('id');
        $presensiData = PresensiHasil::whereIn('kelas_id', $kelasIds)
                                     ->get()
                                     ->groupBy('user_id');

        return view('instructor.presensi.print_program', compact('program', 'participants', 'presensiData'));
    }

    /**
     * Cetak Laporan Presensi Satu Kelas Spesifik.
     */
public function exportKelas($id)
{
    $kelas = Kelas::with(['narasumbers', 'presensiSetup', 'program'])->findOrFail($id);

    // Cek akses user (admin & instruktur)
    $user = auth()->user();

    $adminProgramIds = $user->administeredPrograms()->pluck('programs.id')->toArray();
    $instructedProgramIds = method_exists($user, 'instructedPrograms')
                            ? $user->instructedPrograms()->pluck('programs.id')->toArray()
                            : [];

    $accessibleProgramIds = array_unique(array_merge($adminProgramIds, $instructedProgramIds));

    if (!in_array($kelas->program_id, $accessibleProgramIds)) {
        abort(403, 'Akses Ditolak.');
    }

    // Ambil peserta dengan role 'participant' di program ini
    $participants = User::whereHas('programs', function($q) use ($kelas) {
        $q->where('program_id', $kelas->program_id);
    })
    ->where('role', 'participant') // filter hanya peserta
    ->with(['nomorInduks' => function($q) use ($kelas) {
        $q->where('program_id', $kelas->program_id);
    }])
    ->orderBy('name')
    ->get();

    // Ambil presensi peserta khusus kelas ini
    $presensi = PresensiHasil::where('kelas_id', $kelas->id)
                             ->get()
                             ->keyBy('user_id');

    // Generate PDF
    $pdf = Pdf::loadView('instructor.presensi.export', compact('kelas', 'participants', 'presensi'));

    return $pdf->stream("Presensi_{$kelas->title}.pdf");
}
}
