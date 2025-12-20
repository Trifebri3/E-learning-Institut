<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile; // [FIX 1] Ganti ke model Profile yang benar
use App\Models\Provinsi; // Asumsi Anda punya model ini

class ProfileDataController extends Controller
{
    /**
     * Menampilkan halaman profil (sudah benar)
     */
public function index()
{
    $user = Auth::user();
    $profile = $user->profile;

    // Daftar field wajib (required)
    $requiredFields = [
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat_domisili',
        'asal_provinsi',
        'nomor_hp',
        'golongan_darah',
        'rt_rw',
        'kode_pos',
        'agama',
        'kewarganegaraan',
        'minat_program'
    ];

    // Hitung jumlah field yang sudah terisi
    $filledFields = 0;
    foreach ($requiredFields as $field) {
        if (!empty($profile?->$field)) {
            $filledFields++;
        }
    }

    // Hitung persentase
    $completionPercentage = count($requiredFields) > 0
        ? ($filledFields / count($requiredFields)) * 100
        : 0;

    return view('participant.profil.index', compact(
        'user',
        'profile',
        'requiredFields',
        'filledFields',
        'completionPercentage'
    ));
}



    /**
     * Tampilkan form edit data diri (sudah benar)
     */
    public function edit(Request $request)
    {
        $user = $request->user();
        $profile = $user->profile()->firstOrCreate([]);
        $provinsiList = \App\Models\Provinsi::orderBy('nama')->get(); // Pastikan model Provinsi ada
        return view('profile-data.edit', compact('user', 'profile', 'provinsiList'));
    }

    /**
     * Update data diri (INI YANG DIPERBAIKI TOTAL)
     */
/**
     * Update data diri (INI YANG DIPERBAIKI TOTAL)
     */
public function update(Request $request)
{
    $user = Auth::user();
    $profile = $user->profile()->firstOrCreate([]);

    // Aturan validasi
    $rules = [
        'name' => 'required|string|max:255',
        'nama_panggilan' => 'required|string|max:50',
        'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        'tempat_lahir' => 'required|string|max:100',
        'tanggal_lahir' => 'required|date',
        'kewarganegaraan' => 'required|string|max:50',
        'agama' => 'required|string|max:50',
        'golongan_darah' => 'required|string|max:20',
        'deskripsi_singkat' => 'required|string|min:10|max:500',
        'jenis_identitas' => 'required|in:KTP,Paspor,SIM,Kartu Pelajar',
        'nomor_identitas' => 'required|string|max:50|unique:profiles,nomor_identitas,' . $profile->id,
        'nomor_hp' => 'required|string|max:20',
        'email_cadangan' => 'required|email|max:255',
        'kontak_darurat_nama' => 'required|string|max:255',
        'kontak_darurat_hubungan' => 'required|string|max:255',
        'kontak_darurat_nomor' => 'required|string|max:20',
        'provinsi_id' => 'required|exists:provinsi,id',
        'kabupaten_kota' => 'required|string|max:255',
        'kecamatan' => 'required|string|max:255',
        'kelurahan_desa' => 'required|string|max:255',
        'rt_rw' => 'required|string|max:20',
        'kode_pos' => 'required|string|max:10',
        'alamat_lengkap' => 'required|string|min:10|max:500',
        'status_peserta' => 'required|in:Pelajar/Mahasiswa,Profesional,Lainnya',
        'pendidikan_terakhir' => 'required|string|max:255',
        'nama_sekolah_kampus' => 'required|string|max:255',
        'jurusan' => 'required|string|max:255',
        'nisn_nim' => 'required|string|max:255',
        'pekerjaan' => 'required|string|max:255',
        'instansi_perusahaan' => 'required|string|max:255',
        'jabatan' => 'required|string|max:255',
        'minat_program' => 'required|string|max:255',

        'pas_foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'scan_ktp' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
    ];

    // Jika belum ada file, jadikan wajib
    if (!$profile->pas_foto_path || $request->hasFile('pas_foto')) {
        $rules['pas_foto'] = 'required|image|mimes:jpeg,png,jpg|max:2048';
    }
    if (!$profile->scan_ktp_path || $request->hasFile('scan_ktp')) {
        $rules['scan_ktp'] = 'required|file|mimes:jpeg,png,jpg,pdf|max:5120';
    }

    $validated = $request->validate($rules);

    try {
        // Upload file pas_foto
        if ($request->hasFile('pas_foto')) {
            if ($profile->pas_foto_path) {
                Storage::disk('public')->delete($profile->pas_foto_path);
            }
            $validated['pas_foto_path'] = $request->file('pas_foto')->store('pas_foto', 'public');
        }

        // Upload file scan_ktp
        if ($request->hasFile('scan_ktp')) {
            if ($profile->scan_ktp_path) {
                Storage::disk('public')->delete($profile->scan_ktp_path);
            }
            $validated['scan_ktp_path'] = $request->file('scan_ktp')->store('scan_ktp', 'public');
        }

        // Hapus key-file yang tidak ada di DB
        unset($validated['pas_foto'], $validated['scan_ktp']);

        // Update user name
        $user->update(['name' => $validated['name']]);
        unset($validated['name']);

        // Tandai profile sebagai lengkap
        $validated['is_complete'] = true;

        // Update profile
        $profile->update($validated);

        return redirect()->route('dashboard')->with('success', 'Data Diri berhasil disimpan!');

    } catch (\Exception $e) {
        \Log::error('Profile update error: ' . $e->getMessage());
        \Log::error('Profile data: ', $validated);

        return redirect()->back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
    }
}


/**
 * Update Minat Program (separate form)
 */
public function updateMinatProgram(Request $request)
{
    $user = Auth::user();
    $profile = $user->profile()->firstOrCreate([]);

    $validated = $request->validate([
        'minat_program' => 'required|string|max:255',
    ]);

    try {
        $profile->update($validated);

        return redirect()->back()->with('success', 'Minat Program berhasil diperbarui!');
    } catch (\Exception $e) {
        \Log::error('Update minat program error: ' . $e->getMessage());
        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui minat program.');
    }
}

}
