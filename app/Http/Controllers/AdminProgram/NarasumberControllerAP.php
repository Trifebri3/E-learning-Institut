<?php

namespace App\Http\Controllers\AdminProgram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Narasumber;
use App\Models\Program;
use App\Models\Kelas; // TAMBAHKAN INI

class NarasumberControllerAP extends Controller
{
    /**
     * Daftar Narasumber (dengan filter/search)
     */
    public function index(Request $request, $programId)
    {
        $user = Auth::user();
        $program = $user->administeredPrograms()->findOrFail($programId);

        $query = Narasumber::where('program_id', $program->id)->with('kelas');

        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }

        if ($request->filled('kelas_id')) {
            $query->whereHas('kelas', function ($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }

        $narasumbers = $query->paginate(10);
        $kelas = $program->kelas;

        return view('adminprogram.narasumber.index', compact('narasumbers', 'program', 'kelas'));
    }

    /**
     * Form tambah Narasumber
     */
    public function create($programId)
    {
        $user = Auth::user();
        $program = $user->administeredPrograms()->findOrFail($programId);
        $kelas = $program->kelas;

        return view('adminprogram.narasumber.create', compact('program', 'kelas'));
    }

    /**
     * Simpan Narasumber baru
     */
    public function store(Request $request, $programId)
    {
        $user = Auth::user();
        $program = $user->administeredPrograms()->findOrFail($programId);
        $kelasIds = $program->kelas()->pluck('id')->toArray();

        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'kontak' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'kelas' => 'nullable|array',
            'kelas.*' => 'in:' . implode(',', $kelasIds),
        ]);

        $data = $request->only(['nama', 'jabatan', 'kontak', 'deskripsi']);
        $data['program_id'] = $program->id;

        if ($request->hasFile('foto')) {
            $data['foto_path'] = $request->file('foto')->store('narasumber', 'public');
        }

        $narasumber = Narasumber::create($data);

        if ($request->filled('kelas')) {
            $narasumber->kelas()->attach($request->kelas);
        }

        return redirect()->route('adminprogram.narasumber.index', $program->id)
                         ->with('success', 'Narasumber berhasil ditambahkan.');
    }

    /**
     * Form edit Narasumber
     */
public function edit($programId, $narasumberId)
{
    $program = Program::findOrFail($programId);
    $narasumber = Narasumber::where('program_id', $programId)
                           ->findOrFail($narasumberId);
    $kelas = Kelas::where('program_id', $programId)->get();

    return view('adminprogram.narasumber.edit', compact(
        'program',
        'narasumber',
        'kelas'
    ));
}





    /**
     * Update Narasumber
     */
    public function update(Request $request, $programId, $id)
    {
        $user = Auth::user();
        $program = $user->administeredPrograms()->findOrFail($programId);
        $kelasIds = $program->kelas()->pluck('id')->toArray();

        $narasumber = Narasumber::with('kelas')->findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'kontak' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'kelas' => 'nullable|array',
            'kelas.*' => 'in:' . implode(',', $kelasIds),
        ]);

        $narasumber->update($request->only(['nama', 'jabatan', 'kontak', 'deskripsi']));

        if ($request->hasFile('foto')) {
            if ($narasumber->foto_path) Storage::disk('public')->delete($narasumber->foto_path);
            $narasumber->foto_path = $request->file('foto')->store('narasumber', 'public');
            $narasumber->save();
        }

        $narasumber->kelas()->sync($request->kelas ?? []);

        return redirect()->route('adminprogram.narasumber.index', $program->id)
                         ->with('success', 'Narasumber berhasil diperbarui.');
    }

    /**
     * Hapus Narasumber
     */
    public function destroy($programId, $id)
    {
        $user = Auth::user();
        $program = $user->administeredPrograms()->findOrFail($programId);
        $narasumber = Narasumber::findOrFail($id);

        if ($narasumber->foto_path) {
            Storage::disk('public')->delete($narasumber->foto_path);
        }

        $narasumber->kelas()->detach();
        $narasumber->delete();

        return redirect()->route('adminprogram.narasumber.index', $program->id)
                         ->with('success', 'Narasumber berhasil dihapus.');
    }



public function show($programId, $narasumberId)
{
    $program = Program::findOrFail($programId);

    // Biarkan Eloquent handle column selection
    $narasumber = Narasumber::where('program_id', $programId)
                           ->with('kelas') // Tanpa custom select
                           ->findOrFail($narasumberId);

    return view('adminprogram.narasumber.show', compact(
        'program',
        'narasumber'
    ));
}
}
