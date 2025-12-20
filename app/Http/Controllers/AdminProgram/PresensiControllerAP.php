<?php

namespace App\Http\Controllers\AdminProgram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Kelas;
use App\Models\PresensiSetup;
use App\Models\PresensiHasil;

class PresensiControllerAP extends Controller
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

        return view('adminprogram.presensi.edit', compact('kelas', 'attendances'));
    }

    /**
     * Simpan Konfigurasi Presensi (Token & Waktu).
     */
    public function update(Request $request, $kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);

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
        $presensi = PresensiHasil::findOrFail($hasilId);
        $presensi->delete();

        return back()->with('success', 'Data presensi peserta dihapus.');
    }
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
    $program = \App\Models\Program::with(['kelas' => function($q) {
        $q->orderBy('tanggal', 'asc'); // Urutkan kelas berdasarkan tanggal
    }])->findOrFail($programId);

    // 2. Ambil Peserta yang terdaftar di program ini dengan role participant
    $participants = \App\Models\User::whereHas('programs', function($q) use ($programId) {
            $q->where('program_id', $programId);
        })
        ->where('role', 'participant') // filter hanya peserta
        ->with(['nomorInduks' => function($q) use ($programId) {
            $q->where('program_id', $programId); // Ambil NI khusus program ini
        }])
        ->orderBy('name', 'asc')
        ->get();

    // 3. Ambil Data Presensi (Bulk Fetching untuk performa)
    $kelasIds = $program->kelas->pluck('id');
    $presensiData = \App\Models\PresensiHasil::whereIn('kelas_id', $kelasIds)
                                             ->get()
                                             ->groupBy('user_id');
    // Hasil groupBy: [user_id => [Collection of PresensiHasil]]

    return view('adminprogram.presensi.print_program', compact('program', 'participants', 'presensiData'));
}
    /**
     * Cetak Laporan Presensi Satu Kelas Spesifik.
     */
public function exportKelas($kelasId)
{
    $user = Auth::user();
    $kelas = \App\Models\Kelas::with(['program', 'presensiSetup'])->findOrFail($kelasId);

    // Security Check
    if (!$user->administeredPrograms->contains($kelas->program_id)) {
        abort(403, 'Akses Ditolak.');
    }

    // Ambil semua peserta program ini dengan role participant
    $participants = \App\Models\User::whereHas('programs', function($q) use ($kelas) {
            $q->where('program_id', $kelas->program_id);
        })
        ->where('role', 'participant') // filter hanya peserta
        ->with(['nomorInduks' => function($q) use ($kelas) {
            $q->where('program_id', $kelas->program_id);
        }])
        ->orderBy('name', 'asc')
        ->get();

    // Ambil data presensi khusus kelas ini
    $presensi = \App\Models\PresensiHasil::where('kelas_id', $kelasId)
                                         ->get()
                                         ->keyBy('user_id'); // Key by ID agar mudah diakses di view

    return view('adminprogram.presensi.print_kelas', compact('kelas', 'participants', 'presensi'));
}

}
