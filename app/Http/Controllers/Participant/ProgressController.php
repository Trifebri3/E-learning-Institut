<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Kelas;
use App\Models\PresensiHasil;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;


class ProgressController extends Controller
{
    /**
     * Menampilkan halaman transkrip nilai untuk satu kelas tertentu.
     */
    public function show($kelasId)
    {
        $user = Auth::user();

        // 1. Ambil Kelas beserta semua relasi yang diperlukan untuk perhitungan
        $kelas = Kelas::with([
            'assignments.submissions' => fn($q) => $q->where('user_id', $user->id),
            'quizzes.attempts' => fn($q) => $q->where('user_id', $user->id),
            'essayExams.submissions' => fn($q) => $q->where('user_id', $user->id),
            'presensiSetup',
            'presensiHasil' => fn($q) => $q->where('user_id', $user->id),
            'learningPath.sections', // Untuk hitung total section
            'videoEmbeds',           // Untuk hitung total video
            'resources',             // Untuk hitung total materi
            'modules',               // Untuk hitung total modul
            'customGrades.users' => fn($q) => $q->where('user_id', $user->id),
        ])->findOrFail($kelasId);

        // Security Check
       $userProgramIds = $user->programs()->pluck('programs.id');

        if (!$userProgramIds->contains($kelas->program_id)) {
            abort(403, 'Akses ditolak.');
        }

        $components = [];
        $totalScore = 0;
        $componentCount = 0;

        // --- 1. PERHITUNGAN TUGAS (ASSIGNMENTS) ---
        if ($kelas->assignments->isNotEmpty()) {
            $totalAssignmentScore = 0;
            $gradedCount = 0;

            foreach ($kelas->assignments as $assignment) {
                $submission = $assignment->submissions->first();
                if ($submission && $submission->is_graded) {
                    // Normalisasi nilai ke skala 100 jika max_points bukan 100
                    $normalizedScore = ($submission->score / $assignment->max_points) * 100;
                    $totalAssignmentScore += $normalizedScore;
                    $gradedCount++;
                }
            }

            $avgAssignment = $gradedCount > 0 ? $totalAssignmentScore / $kelas->assignments->count() : 0;

            $components['Tugas'] = [
                'exists' => true,
                'score' => round($avgAssignment, 2),
                'details' => $gradedCount . ' dari ' . $kelas->assignments->count() . ' tugas dinilai.'
            ];
            $totalScore += $avgAssignment;
            $componentCount++;
        } else {
            $components['Tugas'] = ['exists' => false];
        }

        // --- 2. PERHITUNGAN QUIZ (PILIHAN GANDA) ---
        if ($kelas->quizzes->isNotEmpty()) {
            $totalQuizScore = 0;
            foreach ($kelas->quizzes as $quiz) {
                // Ambil nilai tertinggi user
                $bestAttempt = $quiz->attempts->sortByDesc('score')->first();
                $score = $bestAttempt ? $bestAttempt->score : 0;
                $totalQuizScore += $score;
            }
            $avgQuiz = $totalQuizScore / $kelas->quizzes->count();

            $components['Quiz Pilihan Ganda'] = [
                'exists' => true,
                'score' => round($avgQuiz, 2),
                'details' => $kelas->quizzes->count() . ' Quiz tersedia.'
            ];
            $totalScore += $avgQuiz;
            $componentCount++;
        } else {
            $components['Quiz Pilihan Ganda'] = ['exists' => false];
        }

        // --- 3. PERHITUNGAN UJIAN ESAI ---
// 3. UJIAN ESAI
// -----------------------------------------
$detailEssay = [];
$essayScore = 0;

$examCount = $kelas->essayExams->count();

if ($examCount > 0) {
    $totalScoreEssay = 0;

    foreach ($kelas->essayExams as $exam) {
        // Ambil submission user untuk ujian ini
        $submission = $exam->submissions()->where('user_id', auth()->id())->latest()->first();

        $score = 0;
        $status = 'Belum Dinilai';

        if ($submission && $submission->status === 'graded' && $submission->final_score !== null) {
            $score = $submission->final_score; // Ambil skor murni
            $status = $score; // bisa ditampilkan langsung
        }

        $totalScoreEssay += $score;

        $detailEssay[] = [
            'title'  => $exam->title,
            'score'  => $score,  // skor murni tiap ujian
            'status' => $status,
        ];
    }

    // Hitung rata-rata murni
    $essayScore = $totalScoreEssay / $examCount;

    // Tambahkan ke total skor keseluruhan
    $totalScore += $essayScore;
    $componentCount++;
}

// Pastikan array terakhir tetap konsisten
$components['Ujian Esai'] = [
    'exists'       => $examCount > 0,
    'score'        => $essayScore,
    'details_full' => $detailEssay,
];





        // --- 4. PERHITUNGAN PRESENSI ---
        // Presensi dihitung berdasarkan setup yang aktif
        $presensiSetups = $kelas->presensiSetup; // Relation hasOne, tapi logika Anda mungkin 1 kelas 1 setup untuk banyak pertemuan?
        // *CATATAN: Di struktur database sebelumnya, presensi_setups itu 1-to-1 dengan kelas.
        // Jika maksudnya 1 kelas hanya ada 1 sesi presensi (awal & akhir), maka:

        if ($presensiSetups) {
            $attendanceScore = 0;
            $hasil = $kelas->presensiHasil->first(); // Milik user ini (karena sudah difilter di query awal)

            if ($hasil) {
                if ($hasil->status_kehadiran == 'hadir_full') $attendanceScore = 100;
                elseif ($hasil->status_kehadiran == 'hadir_awal' || $hasil->status_kehadiran == 'hadir_akhir') $attendanceScore = 50;
            }

            $components['Presensi'] = [
                'exists' => true,
                'score' => $attendanceScore,
                'status' => $hasil ? $hasil->status_kehadiran : 'Alfa'
            ];
            $totalScore += $attendanceScore;
            $componentCount++;
        } else {
            $components['Presensi'] = ['exists' => false];
        }

        // --- 5. PROGRES KONTEN (Learning Path, Video, Materi, Modul) ---
        // Kita gabungkan ini menjadi satu komponen "Keaktifan Materi" atau pisah-pisah
        // Untuk detail, kita pisah saja.
        // A. Learning Path
        if ($kelas->learningPath && $kelas->learningPath->sections->count() > 0) {
            $totalSections = $kelas->learningPath->sections->count();
            $sectionIds = $kelas->learningPath->sections->pluck('id')->toArray();
            $completedSections = 0;
            if (!empty($sectionIds)) {
                // Hitung dari tabel pivot/kompletion yang menyimpan progress user pada section
                // Pastikan nama tabel 'path_section_user' sesuai struktur DB Anda; sesuaikan jika berbeda.
                $completedSections = DB::table('path_section_user')
                    ->whereIn('path_section_id', $sectionIds)
                    ->where('user_id', $user->id)
                    ->count();
            }

            $pathScore = ($completedSections / $totalSections) * 100;

            $components['Learning Path'] = [
                'exists' => true,
                'score' => round($pathScore, 2),
                'total_items' => $totalSections,
                'completed_items' => $completedSections
            ];
            $totalScore += $pathScore;
            $componentCount++;
        } else {
            $components['Learning Path'] = ['exists' => false];
        }

        // B. Video Wajib
if ($kelas->videoEmbeds->count() > 0) {
    $totalVideos = $kelas->videoEmbeds->count();
    $videoIds = $kelas->videoEmbeds->pluck('id');

    $watchedVideos = DB::table('video_embed_user')
        ->where('user_id', $user->id)
        ->whereIn('video_embed_id', $videoIds)
        ->count();

    $videoScore = ($totalVideos > 0) ? ($watchedVideos / $totalVideos) * 100 : 0;

    $components['Video Wajib'] = [
        'exists' => true,
        'score' => round($videoScore, 2),
        'total_items' => $totalVideos,
        'completed_items' => $watchedVideos
    ];

    $totalScore += $videoScore;
    $componentCount++;
} else {
    $components['Video Wajib'] = ['exists' => false];
}


        // C. Modul Pembelajaran
        if ($kelas->modules->count() > 0) {
            $totalModules = $kelas->modules->count();

            // Jika User model tidak memiliki relasi completedModules(), hitung langsung dari tabel pivot
            $moduleIds = $kelas->modules->pluck('id')->toArray();
            $readModules = 0;
            if (!empty($moduleIds)) {
                $readModules = DB::table('module_user')
                    ->whereIn('module_id', $moduleIds)
                    ->where('user_id', $user->id)
                    ->count();
            }

            $moduleScore = ($readModules / $totalModules) * 100;

            $components['Modul Bacaan'] = [
                'exists' => true,
                'score' => round($moduleScore, 2),
                'total_items' => $totalModules,
                'completed_items' => $readModules
            ];
            $totalScore += $moduleScore;
            $componentCount++;
        } else {
            $components['Modul Bacaan'] = ['exists' => false];
        }

        // --- 6. CUSTOM GRADES (Komponen Tambahan) ---
        if ($kelas->customGrades->isNotEmpty()) {
            $totalCustom = 0;
            foreach ($kelas->customGrades as $custom) {
                // Pivot data sudah di-load
                $userGrade = $custom->users->first();
                $score = $userGrade ? $userGrade->pivot->score : 0;
                $totalCustom += $score;
            }
            $avgCustom = $totalCustom / $kelas->customGrades->count();

            $components['Nilai Tambahan'] = [
                'exists' => true,
                'score' => round($avgCustom, 2),
                'details' => 'Rata-rata dari ' . $kelas->customGrades->count() . ' komponen tambahan.'
            ];
            $totalScore += $avgCustom;
            $componentCount++;
        } else {
            $components['Nilai Tambahan'] = ['exists' => false];
        }

        // --- FINAL CALCULATION ---
        $finalGrade = $componentCount > 0 ? $totalScore / $componentCount : 0;

        return view('participant.progress.show', compact('kelas', 'components', 'finalGrade'));
    }

    /**
     * Halaman Index (Daftar Kelas untuk dilihat progresnya)
     */
public function index()
{
    $user = Auth::user();

    // Ambil semua program yang diikuti user
    $programs = $user->programs()->with('kelas.assignments', 'kelas.quizzes')->get();

    // Total program
    $totalProgram = $programs->count();

    // Total kelas
    $totalKelas = $programs->sum(function ($program) {
        return $program->kelas->count();
    });

    // Hitung kelas yang completed
    $completedKelas = 0;

    foreach ($programs as $program) {
        foreach ($program->kelas as $kelas) {

            $assignmentCount = $kelas->assignments->count();
            $quizCount = $kelas->quizzes->count();

            $userAssignmentCompleted = DB::table('submissions')
                ->where('user_id', $user->id)
                ->whereIn('assignment_id', $kelas->assignments->pluck('id'))
                ->count();

            $userQuizCompleted = DB::table('quiz_attempts')
                ->where('user_id', $user->id)
                ->whereIn('quiz_id', $kelas->quizzes->pluck('id'))
                ->count();

            // Jika semua tugas + quiz selesai
            if (
                $userAssignmentCompleted === $assignmentCount &&
                $userQuizCompleted === $quizCount
            ) {
                $completedKelas++;
            }
        }
    }

    // Tugas total
    $totalTugas = DB::table('submissions')
        ->where('user_id', $user->id)
        ->count();

    // Quiz total
    $totalQuiz = DB::table('quiz_attempts')
        ->where('user_id', $user->id)
        ->count();

    // Presensi total
    $totalPresensi = PresensiHasil::where('user_id', $user->id)->count();
    // Hitung average progress per kelas
    $totalProgress = 0;
    $kelasCount = 0;

    foreach ($programs as $program) {
        foreach ($program->kelas as $kelas) {

            $assignmentCount = $kelas->assignments->count();
            $quizCount = $kelas->quizzes->count();

            $totalItems = $assignmentCount + $quizCount;

            if ($totalItems == 0) {
                continue; // kelas tanpa tugas/quiz tidak dihitung
            }

            $completedAssignments = DB::table('submissions')
                ->where('user_id', $user->id)
                ->whereIn('assignment_id', $kelas->assignments->pluck('id'))
                ->count();

            $completedQuiz = DB::table('quiz_attempts')
                ->where('user_id', $user->id)
                ->whereIn('quiz_id', $kelas->quizzes->pluck('id'))
                ->count();

            $progress = (($completedAssignments + $completedQuiz) / $totalItems) * 100;

            $totalProgress += $progress;
            $kelasCount++;
        }
    }

    // Rata-rata progress
    $averageProgress = $kelasCount > 0 ? round($totalProgress / $kelasCount) : 0;
    return view('participant.progress.index', compact(
        'programs',
        'totalProgram',
        'totalKelas',
        'completedKelas',
        'totalTugas',
        'totalQuiz',
        'totalPresensi',
            'averageProgress'
    ));
}



    private function getProgressData($programId)
    {
        $user = Auth::user();

        // Pastikan user terdaftar di program
        $program = $user->programs()->where('program_id', $programId)->firstOrFail();

        // Ambil seluruh kelas dalam program + relasi yang dibutuhkan
        $allKelas = Kelas::with([
            'assignments.submissions' => fn($q) => $q->where('user_id', $user->id),
            'quizzes.attempts' => fn($q) => $q->where('user_id', $user->id),
            'essayExams.submissions' => fn($q) => $q->where('user_id', $user->id),
            'presensiSetup',
            'presensiHasil' => fn($q) => $q->where('user_id', $user->id),
            'learningPath.sections',
            'videoEmbeds',
            'modules',
            'customGrades.users' => fn($q) => $q->where('user_id', $user->id),
        ])
        ->where('program_id', $programId)
        ->get();

        // Tempat akumulasi nilai
        $summary = [
            'assignment'   => 0,
            'quiz'         => 0,
            'essay'        => 0,
            'presensi'     => 0,
            'learning'     => 0,
            'video'        => 0,
            'modul'        => 0,
            'custom'       => 0,
            'final'        => 0,
        ];

        $kelasCount = $allKelas->count();
        $kelasDetails = [];

        foreach ($allKelas as $kelas) {

            $components = [];
            $totalScore = 0;
            $componentCount = 0;

            // -----------------------------------------
            // 1. TUGAS
            // -----------------------------------------
$detailAssignments = [];
$assignmentScore = 0;

if ($kelas->assignments->count() > 0) {
    $sum = 0;

    foreach ($kelas->assignments as $a) {
        // Ambil submission user
        $sub = $a->submissions()->first();

        // Pastikan max_points > 0 agar tidak error
        $maxPoints = $a->max_points ?: 100; // default 100 jika null atau 0

        // Hitung score, hanya jika tugas sudah dinilai
        $score = 0;
        if ($sub && $sub->is_graded) {
            $score = ($sub->score / $maxPoints) * 100;
        }

        $detailAssignments[] = [
            'title' => $a->title,
            'score' => round($score, 2),
        ];

        $sum += $score;
    }

    // Rata-rata tugas
    $assignmentScore = $sum / $kelas->assignments->count();
    $totalScore += $assignmentScore;
    $componentCount++;
}

$components['Tugas'] = [
    'score' => round($assignmentScore, 2),
    'details_full' => $detailAssignments
];


            // -----------------------------------------
            // 2. QUIZ
            // -----------------------------------------
            $detailQuiz = [];
            $quizScore = 0;

            if ($kelas->quizzes->count() > 0) {
                $sum = 0;

                foreach ($kelas->quizzes as $qz) {
                    $attempt = $qz->attempts->sortByDesc('score')->first();
                    $score = $attempt ? $attempt->score : 0;

                    $detailQuiz[] = [
                        'title' => $qz->title,
                        'score' => $score
                    ];

                    $sum += $score;
                }

                $quizScore = $sum / $kelas->quizzes->count();
                $totalScore += $quizScore;
                $componentCount++;
            }

            $components['Quiz Pilihan Ganda'] = [
                'score' => round($quizScore, 2),
                'details_full' => $detailQuiz
            ];

            // -----------------------------------------
   // 3. UJIAN ESAI
// -----------------------------------------
$detailEssay = [];
$essayScore = 0;

$examCount = $kelas->essayExams->count();

if ($examCount > 0) {
    $totalScoreEssay = 0;

    foreach ($kelas->essayExams as $exam) {
        // Ambil submission user untuk ujian ini
        $submission = $exam->submissions()->where('user_id', auth()->id())->latest()->first();

        $score = 0;
        $status = 'Belum Dinilai';

        if ($submission && $submission->status === 'graded' && $submission->final_score !== null) {
            $score = $submission->final_score; // Ambil skor murni
            $status = $score; // bisa ditampilkan langsung
        }

        $totalScoreEssay += $score;

        $detailEssay[] = [
            'title'  => $exam->title,
            'score'  => $score,  // skor murni tiap ujian
            'status' => $status,
        ];
    }

    // Hitung rata-rata murni
    $essayScore = $totalScoreEssay / $examCount;

    // Tambahkan ke total skor keseluruhan
    $totalScore += $essayScore;
    $componentCount++;
}

// Pastikan array terakhir tetap konsisten
$components['Ujian Esai'] = [
    'exists'       => $examCount > 0,
    'score'        => $essayScore,
    'details_full' => $detailEssay,
];



            // -----------------------------------------
            // 4. PRESENSI
            // -----------------------------------------
            $presensiScore = 0;
            $hasil = $kelas->presensiHasil->first();

            if ($hasil) {
                $presensiScore =
                    ($hasil->status_kehadiran == 'hadir_full') ? 100 :
                    (($hasil->status_kehadiran == 'hadir_awal' || $hasil->status_kehadiran == 'hadir_akhir') ? 50 : 50);
            }

            $components['Presensi'] = [
                'score' => $presensiScore,
                'details_full' => [
                    ['title' => 'Status Kehadiran', 'status' => $hasil->status_kehadiran ?? 'Alfa']
                ]
            ];

            $totalScore += $presensiScore;
            $componentCount++;

            // -----------------------------------------
            // 5. LEARNING PATH
            // -----------------------------------------
            $learningScore = 0;
            $detailLearning = [];

            if ($kelas->learningPath && $kelas->learningPath->sections->count() > 0) {
                $total = $kelas->learningPath->sections->count();
                $done = 0;

                foreach ($kelas->learningPath->sections as $s) {
                    $status = DB::table('path_section_user')
                        ->where('path_section_id', $s->id)
                        ->where('user_id', $user->id)
                        ->exists();

                    $detailLearning[] = [
                        'title' => $s->title,
                        'status' => $status ? 'Selesai' : 'Belum'
                    ];

                    if ($status) $done++;
                }

                $learningScore = ($done / $total) * 100;
                $totalScore += $learningScore;
                $componentCount++;
            }

            $components['Learning Path'] = [
                'score' => round($learningScore, 2),
                'details_full' => $detailLearning
            ];

            // -----------------------------------------
            // 6. VIDEO
            // -----------------------------------------
            $videoScore = 0;
            $detailVideo = [];

            if ($kelas->videoEmbeds->count() > 0) {
                $total = $kelas->videoEmbeds->count();
                $done = 0;

                foreach ($kelas->videoEmbeds as $v) {
                       $watched = DB::table('video_embed_user')
                        ->where('video_embed_id', $v->id)
                        ->where('user_id', $user->id)
                        ->exists();

                    $detailVideo[] = [
                        'title' => $v->title,
                        'status' => $watched ? 'Sudah Ditonton' : 'Belum'
                    ];

                    if ($watched) $done++;
                }

                $videoScore = ($done / $total) * 100;
                $totalScore += $videoScore;
                $componentCount++;
            }

            $components['Video Wajib'] = [
                'score' => round($videoScore, 2),
                'details_full' => $detailVideo
            ];

            // -----------------------------------------
            // 7. MODUL
            // -----------------------------------------
            $modulScore = 0;
            $detailModul = [];

            if ($kelas->modules->count() > 0) {
                $total = $kelas->modules->count();
                $done = 0;

                foreach ($kelas->modules as $m) {
                    $read = DB::table('module_user')
                        ->where('module_id', $m->id)
                        ->where('user_id', $user->id)
                        ->exists();

                    $detailModul[] = [
                        'title' => $m->title,
                        'status' => $read ? 'Dibaca' : 'Belum'
                    ];

                    if ($read) $done++;
                }

                $modulScore = ($done / $total) * 100;
                $totalScore += $modulScore;
                $componentCount++;
            }

            $components['Modul Bacaan'] = [
                'score' => round($modulScore, 2),
                'details_full' => $detailModul
            ];

            // -----------------------------------------
            // 8. CUSTOM GRADE
            // -----------------------------------------
            $customScore = 0;
            $detailCustom = [];

            if ($kelas->customGrades->count() > 0) {
                $sum = 0;

                foreach ($kelas->customGrades as $cg) {
                    $ug = $cg->users->first();
                    $score = $ug ? $ug->pivot->score : 0;

                    $detailCustom[] = [
                        'title' => $cg->title,
                        'score' => $score
                    ];

                    $sum += $score;
                }

                $customScore = $sum / $kelas->customGrades->count();
                $totalScore += $customScore;
                $componentCount++;
            }

            $components['Nilai Tambahan'] = [
                'score' => round($customScore, 2),
                'details_full' => $detailCustom
            ];

            // FINAL SCORE KELAS
            $final = ($componentCount > 0) ? $totalScore / $componentCount : 0;

            // Tambahkan ke summary per program
            $summary['assignment'] += $assignmentScore;
            $summary['quiz'] += $quizScore;
            $summary['essay'] += $essayScore;
            $summary['presensi'] += $presensiScore;
            $summary['learning'] += $learningScore;
            $summary['video'] += $videoScore;
            $summary['modul'] += $modulScore;
            $summary['custom'] += $customScore;
            $summary['final'] += $final;

            // Simpan detail per kelas
            $kelasDetails[] = [
                'kelas' => $kelas,
                'components' => $components,
                'final_score' => round($final, 2)
            ];
        }

        // HITUNG RATA-RATA PER PROGRAM
        foreach ($summary as $key => $val) {
            $summary[$key] = round($val / max(1, $kelasCount), 2);
        }

        return [
            'program' => $program,
            'kelasDetails' => $kelasDetails,
            'summary' => $summary,
            'kelasCount' => $kelasCount,
            'user' => $user
        ];
    }

    /**
     * Preview HTML Progress Program
     */
    public function printProgram($programId)
    {
        $data = $this->getProgressData($programId);
        return view('participant.progress.print', $data);
    }

    /**
     * Preview PDF Progress Program
     */
public function previewPDF($programId)
{
    try {
        $data = $this->getProgressData($programId);

        $pdf = PDF::loadView('participant.progress.print-pdf', $data)
            ->setPaper('A4', 'portrait')
            ->setOptions([
                'dpi' => 150,
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'margin_top' => 15,
                'margin_right' => 10,
                'margin_bottom' => 15,
                'margin_left' => 10,
                'page_offset' => 0
            ]);

        $filename = 'Progress-Program-' . Str::slug($data['program']->title) . '-' . now()->format('Y-m-d') . '.pdf';

        return $pdf->stream($filename);

    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Gagal menampilkan PDF: ' . $e->getMessage());
    }
}
    /**
     * Download PDF Progress Program
     */
    public function downloadPDF($programId)
    {
        try {
            $data = $this->getProgressData($programId);

            $pdf = PDF::loadView('participant.progress.print-pdf', $data)
                ->setPaper('A4', 'portrait')
                ->setOptions([
                    'dpi' => 150,
                    'defaultFont' => 'sans-serif',
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'chroot' => public_path()
                ]);

            $filename = 'Progress-Program-' . Str::slug($data['program']->title) . '-' . now()->format('Y-m-d') . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengunduh PDF: ' . $e->getMessage());
        }
    }

}
