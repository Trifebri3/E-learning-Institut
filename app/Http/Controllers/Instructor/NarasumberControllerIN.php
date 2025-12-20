<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Narasumber;
use App\Models\Program;

class NarasumberControllerIN extends Controller
{
    /**
     * Pastikan instructor hanya akses program yang dia ajar
     */
    private function getInstructorProgram($programId)
    {
        $user = Auth::user();

        $program = $user->instructedPrograms()->find($programId);

        if (!$program) {
            abort(403, 'Anda tidak memiliki akses ke program ini.');
        }

        return $program;
    }

    /**
     * LIST
     */
    public function index(Request $request, $programId)
    {
        $program = $this->getInstructorProgram($programId);

        $query = Narasumber::where('program_id', $programId)
            ->with('kelas');

        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }

        if ($request->filled('kelas_id')) {
            $query->whereHas('kelas', function ($q) use ($request) {
                $q->where('kelas.id', $request->kelas_id);
            });
        }

        return view('instructor.narasumber.index', [
            'program' => $program,
            'kelas' => $program->kelas,
            'narasumbers' => $query->paginate(10)
        ]);
    }

    /**
     * CREATE FORM
     */
    public function create($programId)
    {
        $program = $this->getInstructorProgram($programId);

        return view('instructor.narasumber.create', [
            'program' => $program,
            'kelas' => $program->kelas
        ]);
    }

    /**
     * STORE
     */
    public function store(Request $request, $programId)
    {
        $program = $this->getInstructorProgram($programId);

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
        $data['program_id'] = $programId;

        if ($request->hasFile('foto')) {
            $data['foto_path'] = $request->file('foto')->store('narasumber', 'public');
        }

        $narasumber = Narasumber::create($data);

        if ($request->filled('kelas')) {
            $narasumber->kelas()->attach($request->kelas);
        }

        return redirect()->route('instructor.narasumber.index', $programId)
            ->with('success', 'Narasumber berhasil ditambahkan.');
    }

    /**
     * EDIT FORM
     */
    public function edit($programId, $narasumberId)
    {
        $program = $this->getInstructorProgram($programId);

        $narasumber = Narasumber::where('program_id', $programId)
            ->findOrFail($narasumberId);

        return view('instructor.narasumber.edit', [
            'program' => $program,
            'narasumber' => $narasumber,
            'kelas' => $program->kelas
        ]);
    }

    /**
     * UPDATE
     */
    public function update(Request $request, $programId, $id)
    {
        $program = $this->getInstructorProgram($programId);

        $kelasIds = $program->kelas()->pluck('id')->toArray();

        $narasumber = Narasumber::where('program_id', $programId)
            ->findOrFail($id);

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

        if ($request->hasFile('foto')) {
            if ($narasumber->foto_path) {
                Storage::disk('public')->delete($narasumber->foto_path);
            }
            $data['foto_path'] = $request->file('foto')->store('narasumber', 'public');
        }

        $narasumber->update($data);
        $narasumber->kelas()->sync($request->kelas ?? []);

        return redirect()->route('instructor.narasumber.index', $programId)
            ->with('success', 'Narasumber berhasil diperbarui.');
    }

    /**
     * DELETE
     */
    public function destroy($programId, $id)
    {
        $this->getInstructorProgram($programId);

        $narasumber = Narasumber::where('program_id', $programId)
            ->findOrFail($id);

        if ($narasumber->foto_path) {
            Storage::disk('public')->delete($narasumber->foto_path);
        }

        $narasumber->kelas()->detach();
        $narasumber->delete();

        return redirect()->route('instructor.narasumber.index', $programId)
            ->with('success', 'Narasumber berhasil dihapus.');
    }

    /**
     * SHOW DETAIL
     */
    public function show($programId, $narasumberId)
    {
        $program = $this->getInstructorProgram($programId);

        $narasumber = Narasumber::where('program_id', $programId)
            ->with('kelas')
            ->findOrFail($narasumberId);

        return view('instructor.narasumber.show', compact('program', 'narasumber'));
    }
}
