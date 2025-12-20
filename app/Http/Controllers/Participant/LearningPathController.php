<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\PathSection;
use Illuminate\Support\Facades\Auth;
use App\Models\Kelas;


class LearningPathController extends Controller
{
public function showSection($id)
{
    $section = PathSection::with(['learningPath.kelas'])->findOrFail($id);
    $user = Auth::user();

    $kelas = $section->learningPath->kelas;
    $isEnrolled = $user->programs->contains($kelas->program_id);

    if (!$isEnrolled) {
        abort(403, 'Anda tidak memiliki akses ke materi ini.');
    }
        $sections = $kelas->learningPath->kelas()->orderBy('id')->get();

    // Cari index kelas saat ini
    $currentIndex = $sections->search(fn($s) => $s->id == $kelas->id);

    $previousSection = $sections[$currentIndex - 1] ?? null;
    $nextSection     = $sections[$currentIndex + 1] ?? null;

    return view('participant.learningpath.section_show', compact('section', 'kelas', 'user', 'previousSection', 'nextSection'));
}

    // Tandai selesai
public function completeSection($id)
{
    $section = PathSection::findOrFail($id);
    $user = Auth::user();

    $user->completedPathSections()->syncWithoutDetaching([$id]);

    return back()->with('success', 'Bagian learning path berhasil ditandai selesai!');
}

    public function show($id)
{
    $kelas = Kelas::with(['learningPath.sections'])->findOrFail($id);

    $learningPath = $kelas->learningPath; // PENTING

    return view('participant.kelas.show', compact('kelas', 'learningPath'));
}


}
