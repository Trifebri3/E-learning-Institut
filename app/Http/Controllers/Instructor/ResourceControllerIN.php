<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Program;
use App\Models\Kelas;
use App\Models\Resource;

class ResourceControllerIN extends Controller
{
    /**
     * =========================================================================
     * HELPER: Ambil Semua ID Program yang Boleh Diakses
     * =========================================================================
     * Menggunakan helper isInstructor() / isAdminProgram() dari Model User
     */
    private function getAccessibleProgramIds()
    {
        $user = Auth::user();
        $ids = [];

        // 1. Jika Super Admin, boleh akses SEMUA program
        if ($user->isSuperAdmin()) {
            return Program::pluck('id')->toArray();
        }

        // 2. Jika Admin Program, ambil dari relasi administeredPrograms
        if ($user->isAdminProgram()) {
            // Pastikan relasi 'administeredPrograms' ada di Model User
            $ids = array_merge($ids, $user->administeredPrograms()->pluck('programs.id')->toArray());
        }

        // 3. Jika Instructor, ambil dari relasi instructedPrograms
        if ($user->isInstructor()) {
            // Pastikan relasi 'instructedPrograms' ada di Model User
            // Jika nama relasinya beda, sesuaikan di sini (misal: $user->programs())
            if (method_exists($user, 'instructedPrograms')) {
                $ids = array_merge($ids, $user->instructedPrograms()->pluck('programs.id')->toArray());
            }
        }

        // Hapus duplikat ID (jika user punya peran ganda di program yang sama)
        return array_unique($ids);
    }

    /**
     * ===============================
     * 1. ENTRY POINT (AUTO REDIRECT)
     * ===============================
     * Route: /instructor/resources
     */
    public function index()
    {
        // Ambil daftar ID yang boleh diakses
        $programIds = $this->getAccessibleProgramIds();

        // Jika kosong, berarti akun ini tidak terhubung ke program manapun
        if (empty($programIds)) {
            // Tampilkan pesan error yang spesifik sesuai role
            $role = Auth::user()->role;
            abort(403, "Halo $role, Anda belum ditugaskan ke Program manapun. Silakan hubungi Administrator.");
        }

        // Ambil ID program PERTAMA untuk di-redirect
        $firstProgramId = reset($programIds);

        // Redirect ke dashboard materi program tersebut
        return redirect()->route('instructor.resources.indexByProgram', $firstProgramId);
    }

    /**
     * ===============================
     * 2. LIST MATERI (DASHBOARD)
     * ===============================
     * Route: /instructor/program/{id}/resources
     */
    public function indexByProgram($programId)
    {
        // === CEK KEAMANAN ===
        $allowedIds = $this->getAccessibleProgramIds();

        if (!in_array($programId, $allowedIds)) {
            abort(403, 'AKSES DITOLAK: Anda tidak terdaftar sebagai Instruktur atau Admin di program ini.');
        }
        // ====================

        $program = Program::findOrFail($programId);

        // Ambil kelas & resource (Eager Loading)
        $kelasList = Kelas::with(['resources' => function ($query) {
                                $query->orderBy('created_at', 'desc');
                            }])
                          ->where('program_id', $programId)
                          ->orderBy('title', 'asc')
                          ->get();

        return view('instructor.resources.indexByProgram', compact('program', 'kelasList'));
    }

    /**
     * ===============================
     * 3. CREATE
     * ===============================
     */
    public function create($kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);

        // === CEK KEAMANAN (Berdasarkan Program ID Kelas) ===
        $allowedIds = $this->getAccessibleProgramIds();
        if (!in_array($kelas->program_id, $allowedIds)) {
            abort(403, 'AKSES DITOLAK: Anda tidak memiliki akses ke kelas ini.');
        }

        return view('instructor.resources.create', compact('kelas'));
    }

    public function store(Request $request, $kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);

        // === CEK KEAMANAN ===
        $allowedIds = $this->getAccessibleProgramIds();
        if (!in_array($kelas->program_id, $allowedIds)) {
            abort(403, 'AKSES DITOLAK.');
        }

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'file'        => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,jpg,png,mp4|max:20480',
            'link_url'    => 'nullable|url',
        ]);

        if (!$request->hasFile('file') && empty($request->link_url)) {
            return back()->withInput()->withErrors(['file' => 'Wajib menyertakan File Dokumen ATAU Link URL.']);
        }

        $data = [
            'kelas_id'     => $kelasId,
            'title'        => $request->title,
            'description'  => $request->description,
            'link_url'     => $request->link_url,
            'is_published' => $request->has('is_published'),
        ];

        if ($request->hasFile('file')) {
            $path = "program-{$kelas->program_id}/resources";
            $data['file_path'] = $request->file('file')->store($path, 'public');
        }

        Resource::create($data);

        return redirect()->route('instructor.resources.indexByProgram', $kelas->program_id)
                         ->with('success', "Materi berhasil ditambahkan ke kelas: {$kelas->title}");
    }

    /**
     * ===============================
     * 4. EDIT & UPDATE
     * ===============================
     */
    public function edit($id)
    {
        $resource = Resource::with('kelas.program')->findOrFail($id);

        // === CEK KEAMANAN ===
        $allowedIds = $this->getAccessibleProgramIds();
        if (!in_array($resource->kelas->program_id, $allowedIds)) {
            abort(403, 'AKSES DITOLAK.');
        }

        return view('instructor.resources.edit', compact('resource'));
    }

    public function update(Request $request, $id)
    {
        $resource = Resource::with('kelas')->findOrFail($id);

        // === CEK KEAMANAN ===
        $allowedIds = $this->getAccessibleProgramIds();
        if (!in_array($resource->kelas->program_id, $allowedIds)) {
            abort(403, 'AKSES DITOLAK.');
        }

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'file'        => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,jpg,png,mp4|max:20480',
            'link_url'    => 'nullable|url',
        ]);

        $data = [
            'title'        => $request->title,
            'description'  => $request->description,
            'link_url'     => $request->link_url,
            'is_published' => $request->has('is_published') ? true : false,
        ];

        if ($request->hasFile('file')) {
            if ($resource->file_path) {
                Storage::disk('public')->delete($resource->file_path);
            }
            $path = "program-{$resource->kelas->program_id}/resources";
            $data['file_path'] = $request->file('file')->store($path, 'public');
        }

        $resource->update($data);

        return redirect()->route('instructor.resources.indexByProgram', $resource->kelas->program_id)
                         ->with('success', 'Materi berhasil diperbarui.');
    }

    /**
     * ===============================
     * 5. DELETE
     * ===============================
     */
    public function destroy($id)
    {
        $resource = Resource::with('kelas')->findOrFail($id);
        $programId = $resource->kelas->program_id;

        // === CEK KEAMANAN ===
        $allowedIds = $this->getAccessibleProgramIds();
        if (!in_array($programId, $allowedIds)) {
            abort(403, 'AKSES DITOLAK.');
        }

        if ($resource->file_path) {
            Storage::disk('public')->delete($resource->file_path);
        }

        $resource->delete();

        return redirect()->route('instructor.resources.indexByProgram', $programId)
                         ->with('success', 'Materi berhasil dihapus.');
    }
}
