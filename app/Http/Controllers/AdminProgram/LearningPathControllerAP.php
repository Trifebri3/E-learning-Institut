<?php

namespace App\Http\Controllers\AdminProgram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Kelas;
use App\Models\LearningPath;
use App\Models\PathSection;

class LearningPathControllerAP extends Controller
{
    /**
     * Membuat Learning Path Baru (Hanya Judul).
     */
    public function store(Request $request, $kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);

        // Validasi: 1 Kelas cuma boleh 1 Learning Path
        if ($kelas->learningPath) {
            return back()->with('error', 'Kelas ini sudah memiliki Learning Path.');
        }

        $request->validate(['title' => 'required|string|max:255']);

        $lp = LearningPath::create([
            'kelas_id' => $kelasId,
            'title' => $request->title,
        ]);

        // Redirect langsung ke halaman manajemen section
        return redirect()->route('adminprogram.learningpath.manage', $lp->id)
                         ->with('success', 'Learning Path dibuat. Silakan tambah materi.');
    }

    /**
     * Halaman Manajemen Utama (List Section & Edit Judul).
     */
    public function manage($id)
    {
        $learningPath = LearningPath::with(['kelas.program', 'sections' => function($q) {
            $q->orderBy('order', 'asc');
        }])->findOrFail($id);

        $user = Auth::user();
        if (!$user->administeredPrograms->contains($learningPath->kelas->program_id)) {
            abort(403, 'Akses Ditolak.');
        }

        return view('adminprogram.learningpath.manage', compact('learningPath'));
    }

    /**
     * Update Judul Learning Path.
     */
    public function update(Request $request, $id)
    {
        $lp = LearningPath::findOrFail($id);
        $request->validate(['title' => 'required|string|max:255']);

        $lp->update(['title' => $request->title]);

        return back()->with('success', 'Judul kurikulum diperbarui.');
    }

    /**
     * Hapus Learning Path (dan semua isinya).
     */
    public function destroy($id)
    {
        $lp = LearningPath::findOrFail($id);
        $kelasId = $lp->kelas_id;
        $lp->delete(); // Cascade delete akan menghapus sections juga

        return redirect()->route('adminprogram.kelas.edit', $kelasId)
                         ->with('success', 'Learning Path dihapus.');
    }

    // --- BAGIAN MANAJEMEN SECTION (BAB) ---

    public function createSection($id)
    {
        $learningPath = LearningPath::with('kelas')->findOrFail($id);
        return view('adminprogram.learningpath.sections.create', compact('learningPath'));
    }

    public function storeSection(Request $request, $id)
    {
        $lp = LearningPath::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'order' => 'required|integer',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = [
            'learning_path_id' => $lp->id,
            'title' => $request->title,
            'content' => $request->content,
            'order' => $request->order,
        ];

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('learning-paths', 'public');
        }

        PathSection::create($data);

        return redirect()->route('adminprogram.learningpath.manage', $lp->id)
                         ->with('success', 'Bagian baru berhasil ditambahkan.');
    }

    public function editSection($sectionId)
    {
        $section = PathSection::with('learningPath.kelas')->findOrFail($sectionId);
        return view('adminprogram.learningpath.sections.edit', compact('section'));
    }

    public function updateSection(Request $request, $sectionId)
    {
        $section = PathSection::findOrFail($sectionId);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'order' => 'required|integer',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'order' => $request->order,
        ];

        if ($request->hasFile('image')) {
            if ($section->image_path) Storage::disk('public')->delete($section->image_path);
            $data['image_path'] = $request->file('image')->store('learning-paths', 'public');
        }

        $section->update($data);

        return redirect()->route('adminprogram.learningpath.manage', $section->learning_path_id)
                         ->with('success', 'Konten bagian diperbarui.');
    }

    public function destroySection($sectionId)
    {
        $section = PathSection::findOrFail($sectionId);

        if ($section->image_path) {
            Storage::disk('public')->delete($section->image_path);
        }

        $section->delete();

        return back()->with('success', 'Bagian dihapus.');
    }
}
