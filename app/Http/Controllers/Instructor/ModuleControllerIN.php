<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kelas;
use App\Models\Module;
use Illuminate\Support\Facades\Storage;

class ModuleControllerIN extends Controller
{
    /* =============================
     * 1. AUTHORIZATION CHECK
     * ============================= */
    private function checkAuthorization($programId)
    {
        $user = Auth::user();
        $accessibleProgramIds = array_unique(array_merge(
            $user->administeredPrograms()->pluck('programs.id')->toArray(),
            method_exists($user, 'instructedPrograms') ? $user->instructedPrograms()->pluck('programs.id')->toArray() : []
        ));

        if (!in_array($programId, $accessibleProgramIds)) {
            abort(403, 'Akses Ditolak.');
        }
    }

    /* =============================
     * 2. CRUD MODULE
     * ============================= */

    public function create($kelasId)
    {
        $kelas = Kelas::with('program')->findOrFail($kelasId);
        $this->checkAuthorization($kelas->program_id);
        return view('instructor.modules.create', compact('kelas'));
    }

public function store(Request $request, $kelasId)
{
    $kelas = Kelas::with('program')->findOrFail($kelasId);

    // Authorization check
    $this->checkAuthorization($kelas->program_id);

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'order' => 'required|integer|min:1',
        'is_mandatory' => 'sometimes|boolean',


    ]);

    Module::create([
        'kelas_id' => $kelasId,
        'title' => $validated['title'],
        'content' => $validated['content'],
        'is_mandatory' => $validated['is_mandatory'] ?? false,
        'order' => $validated['order'],

    ]);

    return redirect()->route('instructor.kelas.edit', $kelasId)
                     ->with('success', 'Modul berhasil ditambahkan.');
}



    public function edit($id)
    {
        $module = Module::with('kelas.program')->findOrFail($id);
        $this->checkAuthorization($module->kelas->program_id);
        return view('instructor.modules.edit', compact('module'));
    }

    public function update(Request $request, $id)
    {
        $module = Module::with('kelas')->findOrFail($id);
        $this->checkAuthorization($module->kelas->program_id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'order' => 'required|integer|min:1',
            'is_mandatory' => 'sometimes|boolean',
            'estimated_duration' => 'nullable|integer|min:1',
            'description' => 'nullable|string|max:500',

        ]);

        $module->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'description' => $validated['description'] ?? null,
            'is_mandatory' => $validated['is_mandatory'] ?? false,
            'order' => $validated['order'],
            'estimated_duration' => $validated['estimated_duration'] ?? null,

        ]);

        return redirect()->route('instructor.kelas.edit', $module->kelas_id)
                         ->with('success', 'Modul berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $module = Module::with('kelas')->findOrFail($id);
        $kelasId = $module->kelas_id;
        $this->checkAuthorization($module->kelas->program_id);
        $module->delete();

        return redirect()->route('instructor.kelas.edit', $kelasId)
                         ->with('success', 'Modul berhasil dihapus.');
    }

    /* =============================
     * 3. METHOD TAMBAHAN
     * ============================= */
    public function togglePublish($id)
    {
        $module = Module::with('kelas')->findOrFail($id);
        $this->checkAuthorization($module->kelas->program_id);

        $module->update(['is_published' => !$module->is_published]);
        $status = $module->is_published ? 'dipublikasikan' : 'disembunyikan';
        return back()->with('success', "Modul berhasil $status.");
    }

    public function reorder(Request $request, $kelasId)
    {
        $kelas = Kelas::with('program')->findOrFail($kelasId);
        $this->checkAuthorization($kelas->program_id);

        $request->validate([
            'modules' => 'required|array',
            'modules.*.id' => 'required|exists:modules,id',
            'modules.*.order' => 'required|integer|min:1'
        ]);

        foreach ($request->modules as $moduleData) {
            Module::where('id', $moduleData['id'])
                  ->where('kelas_id', $kelasId)
                  ->update(['order' => $moduleData['order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Urutan modul berhasil diperbarui.'
        ]);
    }

    public function duplicate($id)
    {
        $module = Module::with('kelas')->findOrFail($id);
        $this->checkAuthorization($module->kelas->program_id);

        $newModule = $module->replicate();
        $newModule->title = $module->title . ' (Salinan)';
        $newModule->order = Module::where('kelas_id', $module->kelas_id)->max('order') + 1;
        $newModule->save();

        return redirect()->route('instructor.kelas.edit', $module->kelas_id)
                         ->with('success', 'Modul berhasil diduplikasi.');
    }

    public function getByKelas($kelasId)
    {
        $kelas = Kelas::with('program')->findOrFail($kelasId);
        $this->checkAuthorization($kelas->program_id);

        $modules = Module::where('kelas_id', $kelasId)
                        ->orderBy('order', 'asc')
                        ->get(['id', 'title', 'order', 'is_published', 'is_mandatory']);

        return response()->json($modules);
    }
}
