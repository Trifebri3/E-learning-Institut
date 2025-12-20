<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\NomorInduk;
use Illuminate\Support\Facades\Auth;

class ProgramRedeemController extends Controller
{
    /**
     * Tampilkan halaman form redeem.
     */
    public function create()
    {
        // Path view sudah benar
        return view('participant.redeem.form');
    }

    /**
     * Proses redeem (Logika Final yang Disusun Ulang)
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'kode' => 'required|string',
            'nomor_induk' => 'required|string',
        ]);

        $user = Auth::user();
        $kode_program = $request->kode;
        $nomor_induk_input = $request->nomor_induk;

        // 2. Validasi Kode Program (Redeem Code)
        $program = Program::where('redeem_code', $kode_program)->first();
        if (!$program) {
            return back()->withInput()->withErrors(['kode' => 'Kode redeem program tidak valid.']);
        }

        // 3. Validasi Nomor Induk
        // Kita cari nomor induk berdasarkan string DAN user_id pemiliknya
        $nomorInduk = NomorInduk::where('nomor_induk', $nomor_induk_input)
                                ->where('user_id', $user->id) // Langsung cek kepemilikan
                                ->first();

        if (!$nomorInduk) {
            return back()->withInput()->withErrors(['nomor_induk' => 'Nomor Induk tidak ditemukan atau bukan milik Anda.']);
        }

        // 4. VALIDASI BARU: Cek Status Aktif
        // Pengecekan ini dilakukan setelah nomor induk ditemukan
        if (!$nomorInduk->is_active) {
            return back()->withInput()->withErrors(['nomor_induk' => 'Nomor Induk yang Anda masukkan sudah tidak aktif.']);
        }

        // 5. Validasi Penggunaan (Cek "kolom kosong")
        // Cek apakah kolom program_id sudah terisi (artinya sudah dipakai)
        if ($nomorInduk->program_id !== null) {
            return back()->withInput()->withErrors(['nomor_induk' => 'Nomor Induk ini sudah digunakan untuk program lain.']);
        }

        // 6. Cek Kuota
        $kuota_terisi = $program->participants()->count();
        if ($program->kuota > 0 && $kuota_terisi >= $program->kuota) { // Cek jika kuota > 0
            return back()->withInput()->withErrors(['kode' => 'Maaf, kuota untuk program ini sudah penuh.']);
        }

        // 7. Cek Double Enroll
        $isEnrolled = $user->programs()->where('program_id', $program->id)->exists();
        if ($isEnrolled) {
            return back()->withInput()->withErrors(['kode' => 'Anda sudah terdaftar di program ini.']);
        }

        // --- SEMUA LOLOS: DAFTARKAN USER ---

        // 1. Masukkan ke tabel pivot program_user
        $user->programs()->attach($program->id);

        // 2. "Kunci" Nomor Induk ini ke Program yang baru saja didaftar
        $nomorInduk->update(['program_id' => $program->id]);

        return redirect(route('dashboard')) // Akan diarahkan ke participant.dashboard
               ->with('success', 'Berhasil! Anda telah terdaftar di program: ' . $program->title);
    }
}
