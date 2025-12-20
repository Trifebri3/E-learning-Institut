<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Kelas;
use App\Models\LearningPath;
use App\Models\PathSection;

class LearningPathControllerIN extends Controller
{
    /* =============================
     * 1. MANAJEMEN LEARNING PATH - CRUD
     * ============================= */

    /**
     * Membuat Learning Path Baru
     */
    public function store(Request $request, $kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $user = Auth::user();

        if (!$this->canAccessProgram($user, $kelas->program_id)) {
            abort(403, 'Akses Ditolak.');
        }

        // Validasi: 1 Kelas hanya boleh memiliki 1 Learning Path
        if ($kelas->learningPath) {
            return back()->with('error', 'Kelas ini sudah memiliki Learning Path.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255'
        ]);

        $learningPath = LearningPath::create([
            'kelas_id' => $kelasId,
            'title' => $validated['title'],
        ]);

        return redirect()->route('instructor.learningpath.manage', $learningPath->id)
                         ->with('success', 'Learning Path berhasil dibuat. Silakan tambah materi.');
    }

    /**
     * Halaman Manajemen Learning Path
     */
    public function manage($id)
    {
        $learningPath = LearningPath::with([
            'kelas.program',
            'sections' => function($query) {
                $query->orderBy('order', 'asc');
            }
        ])->findOrFail($id);

        $user = Auth::user();
        if (!$this->canAccessProgram($user, $learningPath->kelas->program_id)) {
            abort(403, 'Akses Ditolak.');
        }

        return view('instructor.learningpath.manage', compact('learningPath'));
    }

    /**
     * Update Judul Learning Path
     */
    public function update(Request $request, $id)
    {
        $learningPath = LearningPath::findOrFail($id);
        $user = Auth::user();

        if (!$this->canAccessProgram($user, $learningPath->kelas->program_id)) {
            abort(403, 'Akses Ditolak.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255'
        ]);

        $learningPath->update(['title' => $validated['title']]);

        return back()->with('success', 'Judul kurikulum berhasil diperbarui.');
    }

    /**
     * Hapus Learning Path beserta semua section
     */
    public function destroy($id)
    {
        $learningPath = LearningPath::findOrFail($id);
        $user = Auth::user();

        if (!$this->canAccessProgram($user, $learningPath->kelas->program_id)) {
            abort(403, 'Akses Ditolak.');
        }

        $kelasId = $learningPath->kelas_id;
        $learningPath->delete(); // Cascade delete sections

        return redirect()->route('instructor.kelas.edit', $kelasId)
                         ->with('success', 'Learning Path berhasil dihapus.');
    }

    /* =============================
     * 2. MANAJEMEN SECTION (BAB) - CRUD
     * ============================= */

    public function createSection($id)
    {
        $learningPath = LearningPath::with('kelas')->findOrFail($id);
        $user = Auth::user();
        if (!$this->canAccessProgram($user, $learningPath->kelas->program_id)) {
            abort(403, 'Akses Ditolak.');
        }

        return view('instructor.learningpath.sections.create', compact('learningPath'));
    }

    public function storeSection(Request $request, $id)
    {
        $learningPath = LearningPath::findOrFail($id);
        $user = Auth::user();
        if (!$this->canAccessProgram($user, $learningPath->kelas->program_id)) {
            abort(403, 'Akses Ditolak.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'order' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'learning_path_id' => $learningPath->id,
            'title' => $validated['title'],
            'content' => $validated['content'],
            'order' => $validated['order'],
        ];

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('learning-paths', 'public');
        }

        PathSection::create($data);

        return redirect()->route('instructor.learningpath.manage', $learningPath->id)
                         ->with('success', 'Section berhasil ditambahkan.');
    }

    public function editSection($sectionId)
    {
        $section = PathSection::with('learningPath.kelas')->findOrFail($sectionId);
        $user = Auth::user();
        if (!$this->canAccessProgram($user, $section->learningPath->kelas->program_id)) {
            abort(403, 'Akses Ditolak.');
        }

        return view('instructor.learningpath.sections.edit', compact('section'));
    }

    public function updateSection(Request $request, $sectionId)
    {
        $section = PathSection::with('learningPath.kelas')->findOrFail($sectionId);
        $user = Auth::user();
        if (!$this->canAccessProgram($user, $section->learningPath->kelas->program_id)) {
            abort(403, 'Akses Ditolak.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'order' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'title' => $validated['title'],
            'content' => $validated['content'],
            'order' => $validated['order'],
        ];

        if ($request->hasFile('image')) {
            if ($section->image_path) {
                Storage::disk('public')->delete($section->image_path);
            }
            $data['image_path'] = $request->file('image')->store('learning-paths', 'public');
        }

        $section->update($data);

        return redirect()->route('instructor.learningpath.manage', $section->learning_path_id)
                         ->with('success', 'Section berhasil diperbarui.');
    }

    public function destroySection($sectionId)
    {
        $section = PathSection::with('learningPath.kelas')->findOrFail($sectionId);
        $user = Auth::user();
        if (!$this->canAccessProgram($user, $section->learningPath->kelas->program_id)) {
            abort(403, 'Akses Ditolak.');
        }

        if ($section->image_path) {
            Storage::disk('public')->delete($section->image_path);
        }

        $section->delete();

        return back()->with('success', 'Section berhasil dihapus.');
    }

    /* =============================
     * 3. METHOD BANTU
     * ============================= */

    /**
     * Preview Learning Path
     */
    public function preview($id)
    {
        $learningPath = LearningPath::with([
            'sections' => function($query) {
                $query->orderBy('order', 'asc');
            }
        ])->findOrFail($id);

        $user = Auth::user();
        if (!$this->canAccessProgram($user, $learningPath->kelas->program_id)) {
            abort(403, 'Akses Ditolak.');
        }

        return view('instructor.learningpath.preview', compact('learningPath'));
    }

    /**
     * Reorder Sections
     */
    public function reorderSections(Request $request, $id)
    {
        $learningPath = LearningPath::with('kelas')->findOrFail($id);
        $user = Auth::user();
        if (!$this->canAccessProgram($user, $learningPath->kelas->program_id)) {
            abort(403, 'Akses Ditolak.');
        }

        $request->validate([
            'sections' => 'required|array',
            'sections.*.id' => 'required|exists:path_sections,id',
            'sections.*.order' => 'required|integer|min:1'
        ]);

        foreach ($request->sections as $sectionData) {
            PathSection::where('id', $sectionData['id'])
                      ->update(['order' => $sectionData['order']]);
        }

        return response()->json(['success' => true, 'message' => 'Urutan section berhasil diperbarui.']);
    }

    /**
     * Toggle Status Section (Active/Inactive)
     */
    public function toggleSectionStatus($sectionId)
    {
        $section = PathSection::with('learningPath.kelas')->findOrFail($sectionId);
        $user = Auth::user();
        if (!$this->canAccessProgram($user, $section->learningPath->kelas->program_id)) {
            abort(403, 'Akses Ditolak.');
        }

        $section->update(['is_active' => !$section->is_active]);
        $status = $section->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Section berhasil $status.");
    }

    /* =============================
     * 4. HELPER FUNCTION
     * ============================= */

    private function canAccessProgram($user, $programId)
    {
        $adminPrograms = $user->administeredPrograms->pluck('id');
        $instructorPrograms = method_exists($user, 'instructedPrograms') ? $user->instructedPrograms->pluck('id') : collect();

        return $adminPrograms->merge($instructorPrograms)->contains($programId);
    }
}
