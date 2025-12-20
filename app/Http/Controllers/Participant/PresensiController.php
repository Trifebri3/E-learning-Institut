<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kelas;
use App\Models\PresensiSetup;
use App\Models\PresensiHasil;
use App\Models\Program; // [TAMBAHAN] Diperlukan untuk cekProgramSelesai
use App\Models\User; // [TAMBAHAN] Diperlukan untuk cekProgramSelesai
use Carbon\Carbon;

class PresensiController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input form
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'tipe' => 'required|in:awal,akhir',
            'token' => 'required|string',
            'refleksi' => 'required|string|min:10',
        ]);

        $user = Auth::user();
        $kelas = Kelas::findOrFail($request->kelas_id);
        $setup = $kelas->presensiSetup;
        $now = Carbon::now();

        // 1. Dapatkan Nomor Induk yang dipakai user untuk program ini
        $nomorInduk = $user->nomorInduks()
                            ->where('program_id', $kelas->program_id)
                            ->first();

        if (!$nomorInduk) {
            return back()->with('error', 'Gagal! Data Nomor Induk Anda tidak ditemukan untuk program ini.');
        }

        // 2. Cari atau Buat data presensi user untuk kelas ini
        // [FIX] Memperbaiki typo '=>' menjadi '='
        $hasil = PresensiHasil::firstOrCreate(
            [
                'user_id' => $user->id,
                'kelas_id' => $kelas->id,
            ],
            [
                'nomor_induk_id' => $nomorInduk->id,
                'status_kehadiran' => 'alpha',
            ]
        );

        // 3. Proses berdasarkan Tipe (Awal atau Akhir)
       if ($request->tipe == 'awal') {

            // Cek jadwal presensi awal
            if (!$now->between($setup->buka_awal, $setup->tutup_awal)) {
                return back()->with('error', 'Presensi Awal belum dibuka atau sudah ditutup.');
            }

            // Cek token
            if ($request->token !== $setup->token_awal) {
                return back()->with('error', 'Token Presensi Awal salah.');
            }

            // Cek apakah sudah presensi awal
            if ($hasil->waktu_presensi_awal) {
                return back()->with('error', 'Anda sudah melakukan presensi awal.');
            }

            // Simpan presensi awal
            $hasil->refleksi_awal = $request->refleksi;
            $hasil->waktu_presensi_awal = $now;
            $hasil->status_kehadiran = 'hadir_awal';

        } elseif ($request->tipe == 'akhir') {

            // Cek jadwal presensi akhir
            if (!$now->between($setup->buka_akhir, $setup->tutup_akhir)) {
                return back()->with('error', 'Presensi Akhir belum dibuka atau sudah ditutup.');
            }

            // Cek token
            if ($request->token !== $setup->token_akhir) {
                return back()->with('error', 'Token Presensi Akhir salah.');
            }

            // Cek apakah sudah presensi akhir
            if ($hasil->waktu_presensi_akhir) {
                return back()->with('error', 'Anda sudah melakukan presensi akhir.');
            }

            // Simpan presensi akhir
            $hasil->refleksi_akhir = $request->refleksi;
            $hasil->waktu_presensi_akhir = $now;

            // Tentukan status kehadiran sementara
            $hasil->status_kehadiran = $hasil->waktu_presensi_awal ? 'hadir_full' : 'hadir_akhir';
        }

        // --- 5. Simpan data presensi ---
        $hasil->save();

        // --- 6. Update status full jika keduanya terisi ---
        if ($hasil->waktu_presensi_awal && $hasil->waktu_presensi_akhir) {
            $hasil->status_kehadiran = 'hadir_full';
            $hasil->save();
        }

        // --- 7. Cek program selesai jika presensi akhir sudah full ---
        if ($request->tipe == 'akhir' && $hasil->status_kehadiran == 'hadir_full') {
            $this->cekProgramSelesai($user, $kelas->program);
        }

        return back()->with('success', 'Presensi ' . $request->tipe . ' berhasil dicatat!');
    }

    /**
     * [FUNGSI BARU]
     * Cek apakah user sudah menyelesaikan semua kelas di program ini.
     * Jika ya, berikan badge.
     */
    private function cekProgramSelesai(User $user, Program $program)
    {
        // 1. Hitung total kelas yang publish di program ini
        $totalKelas = $program->kelas()->where('is_published', true)->count();

        // 2. Hitung total presensi 'hadir_full' user di program ini
        $totalHadirFull = PresensiHasil::where('user_id', $user->id)
                                    ->whereIn('kelas_id', $program->kelas->pluck('id'))
                                    ->where('status_kehadiran', 'hadir_full')
                                    ->count();

        // 3. Jika jumlahnya sama (SEMUA KELAS SELESAI)
        if ($totalKelas > 0 && $totalKelas == $totalHadirFull) {

            // 4. Cari template badge untuk program ini
            $badgeTemplate = $program->badgeTemplate;

            if ($badgeTemplate) {
                // 5. Berikan badge ke user (jika belum punya)
                // syncWithoutDetaching akan menambah jika belum ada, tanpa duplikat
                $user->badges()->syncWithoutDetaching([
                    $badgeTemplate->id => ['earned_at' => now()]
                ]);
            }
        }
    }
}
