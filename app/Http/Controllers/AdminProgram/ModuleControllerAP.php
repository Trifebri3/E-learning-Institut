<?php

namespace App\Http\Controllers\AdminProgram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kelas;
use App\Models\Module;

class ModuleControllerAP extends Controller
{
    /**
     * Form Tambah Modul Baru (Terikat ke Kelas).
     */
    public function create($kelasId)
    {
        $user = Auth::user();
        $kelas = Kelas::with('program')->findOrFail($kelasId);

        // Security Check
        if (!$user->administeredPrograms->contains($kelas->program_id)) {
            abort(403, 'Akses Ditolak.');
        }

        return view('adminprogram.modules.create', compact('kelas'));
    }

    /**
     * Simpan Modul Baru.
     */
    public function store(Request $request, $kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'order' => 'required|integer|min:1',
        ]);

        Module::create([
            'kelas_id' => $kelasId,
            'title' => $request->title,
            'content' => $request->content,
 'is_mandatory' => true,

            'order' => $request->order,
        ]);

        return redirect()->route('adminprogram.kelas.edit', $kelasId)
                         ->with('success', 'Modul berhasil ditambahkan.');
    }

    /**
     * Form Edit Modul.
     */
    public function edit($id)
    {
        $module = Module::with('kelas.program')->findOrFail($id);
        $user = Auth::user();

        // Security Check
        if (!$user->administeredPrograms->contains($module->kelas->program_id)) {
            abort(403, 'Akses Ditolak.');
        }

        return view('adminprogram.modules.edit', compact('module'));
    }

    /**
     * Update Modul.
     */
 public function update(Request $request, $id)
{
    $module = Module::findOrFail($id);

    $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'order' => 'required|integer|min:1',
    ]);

    $module->update([
        'title' => $request->title,
        'content' => $request->content,
        'order' => $request->order,
        'is_mandatory' => true, // Tetap wajib
    ]);

    return redirect()->route('instructor.kelas.edit', $module->kelas_id)
                     ->with('success', 'Modul berhasil diperbarui.');
}
    /**
     * Hapus Modul.
     */
    public function destroy($id)
    {
        $module = Module::findOrFail($id);
        $kelasId = $module->kelas_id;
        $module->delete();

        return redirect()->route('adminprogram.kelas.edit', $kelasId)
                         ->with('success', 'Modul berhasil dihapus.');
    }
}
