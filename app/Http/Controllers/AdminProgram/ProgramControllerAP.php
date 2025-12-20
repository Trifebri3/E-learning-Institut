<?php

namespace App\Http\Controllers\AdminProgram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Program;

class ProgramControllerAP extends Controller
{
    /**
     * Menampilkan daftar program yang DIKELOLA oleh admin ini.
     */
    public function index()
    {
        $user = Auth::user();

        // Ambil program via relasi 'administeredPrograms'
        $programs = $user->administeredPrograms()
                         ->withCount('participants') // Hitung peserta
                         ->latest()
                         ->paginate(10);

        return view('adminprogram.programs.index', compact('programs'));
    }

    /**
     * Form edit program.
     */
    public function edit($id)
    {
        $user = Auth::user();
        $program = Program::findOrFail($id);

        // [SECURITY CHECK]
        // Pastikan admin ini benar-benar pengelola program ini
        if (!$user->administeredPrograms->contains($id)) {
            abort(403, 'Akses Ditolak. Anda bukan admin dari program ini.');
        }

        return view('adminprogram.programs.edit', compact('program'));
    }

    /**
     * Update program.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $program = Program::findOrFail($id);

        // [SECURITY CHECK]
        if (!$user->administeredPrograms->contains($id)) {
            abort(403, 'Akses Ditolak.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            // Redeem code biasanya tidak boleh diubah sembarangan oleh Admin Program,
            // tapi jika Anda izinkan, silakan tambahkan validasi unique.
            'kuota' => 'required|integer|min:1',
            'lokasi' => 'required|string',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date|after_or_equal:waktu_mulai',
            'logo' => 'nullable|image|max:2048',
            'banner' => 'nullable|image|max:4096',
            'deskripsi_singkat' => 'nullable|string',
            'deskripsi_lengkap' => 'nullable|string',
        ]);

        $data = $request->except(['logo', 'banner']);

        // Handle Upload
        if ($request->hasFile('logo')) {
            if ($program->logo_path) Storage::disk('public')->delete($program->logo_path);
            $data['logo_path'] = $request->file('logo')->store('images/logos', 'public');
        }
        if ($request->hasFile('banner')) {
            if ($program->banner_path) Storage::disk('public')->delete($program->banner_path);
            $data['banner_path'] = $request->file('banner')->store('images/banners', 'public');
        }

        $program->update($data);

        return redirect()->route('adminprogram.programs.index')->with('success', 'Konten program berhasil diperbarui.');
    }
    public function create()
{
    $instructors = User::where('role', 'instructor')->get();
    return view('superadmin.programs.create', compact('instructors'));
}

}
