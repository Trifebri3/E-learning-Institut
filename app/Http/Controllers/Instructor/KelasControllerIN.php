<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Kelas;
use App\Models\Narasumber;
use App\Models\PresensiSetup;

class KelasControllerIN extends Controller
{
    /**
     * Menampilkan daftar kelas dari program yang dikelola.
     */
public function index(Request $request, $programId = null)
{
    $user = Auth::user();

    // Ambil ID program yang user kelola sebagai admin
    $adminProgramIds = $user->administeredPrograms()->pluck('programs.id')->toArray();

    // Ambil ID program yang user jadi instruktur
    if (method_exists($user, 'instructedPrograms')) {
        $instrProgramIds = $user->instructedPrograms()->pluck('programs.id')->toArray();
    } else {
        $instrProgramIds = [];
    }

    // Gabungkan ID program unik
    $programIds = array_unique(array_merge($adminProgramIds, $instrProgramIds));

    // Query kelas berdasarkan program yang bisa diakses user
    $query = Kelas::with(['program', 'narasumbers'])
        ->whereIn('program_id', $programIds);

    // Filter berdasarkan program tertentu jika diberikan
    if ($programId) {
        $query->where('program_id', $programId);
    }

    // Pagination & urut berdasarkan tanggal
    $kelas = $query->orderBy('tanggal', 'desc')->paginate(10);

    // Untuk dropdown filter program admin
    $programs = $user->administeredPrograms;

    return view('instructor.kelas.index', compact('kelas', 'programs', 'programId'));
}



    /**
     * Halaman Edit (DASHBOARD KELAS).
     */
 public function edit($id)
{
    $kelas = Kelas::with([
        'program', 'narasumbers', 'presensiSetup',
        'modules', 'videoEmbeds', 'assignments',
        'quizzes', 'essayExams', 'learningPath'
    ])->findOrFail($id);

    $user = Auth::user();

    // Gabungan program yang dikelola admin + program yang dia jadi instruktur
    $accessibleProgramIds = array_unique(array_merge(
        $user->administeredPrograms()->pluck('programs.id')->toArray(),
        method_exists($user, 'instructedPrograms') ? $user->instructedPrograms()->pluck('programs.id')->toArray() : []
    ));

    if (!in_array($kelas->program_id, $accessibleProgramIds)) {
        abort(403, 'Akses Ditolak.');
    }

    $exams = $kelas->essayExams()->with(['questions','submissions'])->paginate(5);
    $assignments = $kelas->assignments;
    $availableNarasumbers = Narasumber::where('program_id', $kelas->program_id)->get();

    return view('instructor.kelas.edit', compact('kelas', 'availableNarasumbers', 'exams', 'assignments'));
}

public function update(Request $request, $id)
{
    $kelas = Kelas::findOrFail($id);
    $user = Auth::user();

    // Gabungan program yang bisa diakses user
    $accessibleProgramIds = array_unique(array_merge(
        $user->administeredPrograms()->pluck('programs.id')->toArray(),
        method_exists($user, 'instructedPrograms') ? $user->instructedPrograms()->pluck('programs.id')->toArray() : []
    ));

    if (!in_array($kelas->program_id, $accessibleProgramIds)) {
        abort(403, 'Akses Ditolak.');
    }

    $rules = [
        'title' => 'required|string|max:255',
        'tipe' => 'required|in:materi,interaktif',
        'tanggal' => 'required|date',
        'jam_mulai' => 'required',
        'jam_selesai' => 'nullable|after:jam_mulai',
        'tempat' => 'required|string',
        'deskripsi' => 'required|string',
        'banner' => 'nullable|image|max:2048',
        'narasumber_ids' => 'nullable|array',
        'narasumber_ids.*' => 'exists:narasumbers,id',
    ];

    if ($request->tipe == 'interaktif') {
        $rules['link_zoom'] = 'required|url';
    }

    $request->validate($rules);

    $data = $request->except(['banner', 'narasumber_ids', 'presensi']);

    if ($request->hasFile('banner')) {
        if ($kelas->banner_path) {
            Storage::disk('public')->delete($kelas->banner_path);
        }
        $data['banner_path'] = $request->file('banner')->store('images/kelas', 'public');
    }

    $kelas->update($data);
    $kelas->narasumbers()->sync($request->input('narasumber_ids', []));

    if ($request->has('presensi')) {
        $pData = $request->presensi;
        PresensiSetup::updateOrCreate(
            ['kelas_id' => $kelas->id],
            [
                'token_awal' => $pData['token_awal'],
                'token_akhir' => $pData['token_akhir'],
                'buka_awal' => $pData['buka_awal'],
                'tutup_awal' => $pData['tutup_awal'],
                'buka_akhir' => $pData['buka_akhir'],
                'tutup_akhir' => $pData['tutup_akhir'],
                'is_active' => isset($pData['is_active']),
            ]
        );
    }

    return redirect()->route('instructor.kelas.edit', $kelas->id)
        ->with('success', 'Data kelas diperbarui.');
}

public function togglePublish($id)
{
    $kelas = Kelas::findOrFail($id);
    $user = Auth::user();

    $accessibleProgramIds = array_unique(array_merge(
        $user->administeredPrograms()->pluck('programs.id')->toArray(),
        method_exists($user, 'instructedPrograms') ? $user->instructedPrograms()->pluck('programs.id')->toArray() : []
    ));

    if (!in_array($kelas->program_id, $accessibleProgramIds)) {
        abort(403, 'Akses Ditolak.');
    }

    $kelas->update(['is_published' => !$kelas->is_published]);
    $status = $kelas->is_published ? 'dipublikasikan' : 'tidak dipublikasikan';

    return back()->with('success', "Kelas berhasil $status.");
}

}
