<?php

namespace App\Http\Controllers\AdminProgram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Program;
use App\Models\Kelas;
use App\Models\Resource;




class ResourceControllerAP extends Controller
{
    /**
     * ===============================
     * LISTING (DASHBOARD MATERI)
     * ===============================
     */

    // Menampilkan seluruh kelas beserta materinya dalam 1 Program
    public function indexByProgram($programId)
    {
        // 1. Ambil detail Program (untuk judul halaman)
        $program = Program::findOrFail($programId);

        // 2. Ambil Kelas beserta Resource-nya (Eager Loading)
        $kelasList = Kelas::with(['resources' => function ($query) {
                                $query->orderBy('created_at', 'desc');
                            }])
                          ->where('program_id', $programId)
                          ->orderBy('title', 'asc')
                          ->get();

        return view('adminprogram.resources.indexByProgram', compact('program', 'kelasList'));
    }

    /**
     * ===============================
     * CREATE & STORE
     * ===============================
     */

    // Form tambah resource
    public function create($kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        return view('adminprogram.resources.create', compact('kelas'));
    }

    // Simpan resource baru
    public function store(Request $request, $kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'file'        => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,jpg,png,mp4|max:20480', // Max 20MB
            'link_url'    => 'nullable|url',
        ]);

        // Validasi: Harus ada File ATAU Link
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
            // Simpan di folder berdasarkan ID Program
            $path = "program-{$kelas->program_id}/resources";
            $data['file_path'] = $request->file('file')->store($path, 'public');
        }

        Resource::create($data);

        // Redirect kembali ke dashboard program
        return redirect()->route('adminprogram.resources.indexByProgram', $kelas->program_id)
                         ->with('success', "Materi berhasil ditambahkan ke kelas: {$kelas->title}");
    }

    /**
     * ===============================
     * EDIT & UPDATE
     * ===============================
     */

    // Form edit resource
    public function edit($id)
    {
        $resource = Resource::with('kelas.program')->findOrFail($id);
        return view('adminprogram.resources.edit', compact('resource'));
    }

    // Update resource
    public function update(Request $request, $id)
    {
        $resource = Resource::with('kelas')->findOrFail($id);

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
            // Hapus file lama jika ada
            if ($resource->file_path) {
                Storage::disk('public')->delete($resource->file_path);
            }
            // Simpan file baru
            $path = "program-{$resource->kelas->program_id}/resources";
            $data['file_path'] = $request->file('file')->store($path, 'public');
        }

        $resource->update($data);

        return redirect()->route('adminprogram.resources.indexByProgram', $resource->kelas->program_id)
                         ->with('success', 'Materi berhasil diperbarui.');
    }

    /**
     * ===============================
     * DELETE
     * ===============================
     */

    // Hapus resource
    public function destroy($id)
    {
        $resource = Resource::with('kelas')->findOrFail($id);
        $programId = $resource->kelas->program_id; // Simpan ID untuk redirect

        if ($resource->file_path) {
            Storage::disk('public')->delete($resource->file_path);
        }

        $resource->delete();

        return redirect()->route('adminprogram.resources.indexByProgram', $programId)
                         ->with('success', 'Materi berhasil dihapus.');
    }



    // Di dalam ResourceControllerAP.php

public function selectProgram()
{
    $user = Auth::user();

    // Ambil semua program yang dipegang oleh user ini
    // Asumsi relasi di model User: public function administeredPrograms()
    $programs = $user->administeredPrograms;

    return view('adminprogram.resources.selectProgram', compact('programs'));
}
}
