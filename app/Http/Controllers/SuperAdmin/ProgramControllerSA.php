<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProgramControllerSA extends Controller
{
    /**
     * Menampilkan daftar program.
     */
    public function index()
    {
        // Ambil program beserta jumlah peserta dan daftar adminnya
        $programs = Program::with('admins')->withCount('participants')->latest()->paginate(10);
        return view('superadmin.programs.index', compact('programs'));
    }

    /**
     * Form buat program baru.
     */
    public function create()
    {

        // Ambil user dengan role 'admin_program' untuk dipilih
        $admins = User::where('role', 'admin_program')->get();

        $instructors = User::where('role', 'instructor')->get();
        return view('superadmin.programs.create', compact('admins', 'instructors'));
    }

    /**
     * Simpan program baru.
     */
public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'redeem_code' => 'required|string|unique:programs,redeem_code|max:50',
        'kuota' => 'required|integer|min:1',
        'lokasi' => 'required|string',
        'waktu_mulai' => 'required|date',
        'waktu_selesai' => 'required|date|after_or_equal:waktu_mulai',
        'logo' => 'nullable|image|max:2048',
        'banner' => 'nullable|image|max:4096',

        'admin_ids' => 'nullable|array',
        'admin_ids.*' => 'exists:users,id',

        'instructors' => 'nullable|array',
        'instructors.*' => 'exists:users,id',
    ]);

    // Data dasar program
    $data = $request->except(['logo', 'banner', 'admin_ids', 'instructors']);

    // Upload Logo
    if ($request->hasFile('logo')) {
        $data['logo_path'] = $request->file('logo')->store('images/logos', 'public');
    }

    // Upload Banner
    if ($request->hasFile('banner')) {
        $data['banner_path'] = $request->file('banner')->store('images/banners', 'public');
    }

    // Buat program dulu
    $program = Program::create($data);

    // ====== RELASI ADMIN PROGRAM ======
    if ($request->has('admin_ids')) {
        $program->admins()->sync($request->admin_ids);
    } else {
        $program->admins()->sync([]);
    }

    // ====== RELASI INSTRUKTUR ======
    if ($request->has('instructors')) {
        $program->instructors()->sync($request->instructors);
    } else {
        $program->instructors()->sync([]);
    }

    return redirect()->route('superadmin.programs.index')
                     ->with('success', 'Program berhasil dibuat.');
}


    /**
     * Form edit program.
     */
    public function edit($id)
    {
        $program = Program::with('admins')->findOrFail($id);
        $admins = User::where('role', 'adminprogram')->get();
            $instructors = User::where('role', 'instructor')->get();

        // Ambil array ID admin yang sudah terhubung untuk checkbox
        $selectedAdmins = $program->admins->pluck('id')->toArray();

        return view('superadmin.programs.edit', compact('program', 'admins', 'selectedAdmins', 'instructors'));
    }

    /**
     * Update program.
     */
    public function update(Request $request, $id)
    {
        $program = Program::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'redeem_code' => 'required|string|max:50|unique:programs,redeem_code,' . $id,
            'kuota' => 'required|integer|min:1',
            'lokasi' => 'required|string',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date|after_or_equal:waktu_mulai',
            'logo' => 'nullable|image|max:2048',
            'banner' => 'nullable|image|max:4096',
            'admin_ids' => 'nullable|array',

        'instructors' => 'nullable|array',
        'instructors.*' => 'exists:users,id',
        ]);

        $data = $request->except(['logo', 'banner', 'admin_ids', 'instructors']);

        // Handle Upload (Hapus lama jika ada baru)
        if ($request->hasFile('logo')) {
            if ($program->logo_path) Storage::disk('public')->delete($program->logo_path);
            $data['logo_path'] = $request->file('logo')->store('images/logos', 'public');
        }
        if ($request->hasFile('banner')) {
            if ($program->banner_path) Storage::disk('public')->delete($program->banner_path);
            $data['banner_path'] = $request->file('banner')->store('images/banners', 'public');
        }

            if ($request->has('instructors')) {
        $program->instructors()->sync($request->instructors);
    } else {
        $program->instructors()->sync([]); // kosongkan jika tidak memilih apa pun
    }

        $program->update($data);

        // Sync Admin Program
        $program->admins()->sync($request->input('admin_ids', []));

        return redirect()->route('superadmin.programs.index')->with('success', 'Program berhasil diperbarui.');
    }

    /**
     * Hapus program.
     */
    public function destroy($id)
    {
        $program = Program::findOrFail($id);

        if ($program->logo_path) Storage::disk('public')->delete($program->logo_path);
        if ($program->banner_path) Storage::disk('public')->delete($program->banner_path);

        $program->delete();

        return redirect()->route('superadmin.programs.index')->with('success', 'Program berhasil dihapus.');
    }


public function show($id)
{
    $program = Program::with([
        'admins',
        'participants',
        'classes.modules',     // relasi modul
        'assignments',         // relasi tugas
        'essayExams'           // relasi ujian
    ])
    ->withCount([
        'classes as kelas_count',
        'assignments as tugas_count',
        'essayExams as ujian_count'
    ])
    ->findOrFail($id);
    /*
    |-------------------------------------------------
    | 1. KELAS (punya langsung program_id)
    |-------------------------------------------------
    */
    $classes = \DB::table('kelas')
        ->where('program_id', $id)
        ->select('id', 'title', 'tipe', 'tanggal', 'jam_mulai', 'jam_selesai')
        ->get();


    /*
    |-------------------------------------------------
    | 2. ASSIGNMENTS (JOIN kelas → ambil berdasarkan program)
    |-------------------------------------------------
    */
    $allAssignments = \DB::table('assignments AS a')
        ->join('kelas AS k', 'k.id', '=', 'a.kelas_id')
        ->where('k.program_id', $id)
        ->select(
            'a.id',
            'a.title',
            'a.due_date',
            'a.is_published',
            'k.title AS kelas_title'
        )
        ->get();

    $program = Program::with(['instructors'])->findOrFail($id);
    /*
    |-------------------------------------------------
    | 3. ESSAY EXAMS (JOIN kelas → ambil berdasarkan program)
    |-------------------------------------------------
    */
    $allExams = \DB::table('essay_exams AS e')
        ->join('kelas AS k', 'k.id', '=', 'e.kelas_id')
        ->where('k.program_id', $id)
        ->select(
            'e.id',
            'e.title',
            'e.duration_minutes',
            'e.is_published',
            'k.title AS kelas_title'
        )
        ->get();


    /*
    |-------------------------------------------------
    | 4. Hitung total kelas
    |-------------------------------------------------
    */
    $kelasCount = $classes->count();

    return view('superadmin.programs.show', compact(
        'program',
        'classes',
        'allAssignments',
        'allExams',
        'kelasCount'
    ));
}


}
