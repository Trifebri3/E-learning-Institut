<?php

namespace App\Http\Controllers\AdminProgram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminRaportController extends Controller
{
    /**
     * Menampilkan halaman rekap raport untuk satu kelas
     */

    public function index()
{
    // Ambil semua kelas
    $kelasList = \App\Models\Kelas::with('program')->orderBy('name')->get();

    return view('adminprogram.raport.index', compact('kelasList'));
}

    public function show($kelasId)
    {
        $kelas = Kelas::with([
            'assignments.submissions.user',
            'quizzes.attempts.user',
            'essayExams.submissions.user',
            'presensiSetup',
            'presensiHasil.user',
            'learningPath.sections',
            'videoEmbeds',
            'modules',
            'customGrades.users'
        ])->findOrFail($kelasId);

        // Ambil semua peserta kelas ini
$participants = User::whereHas('programs', fn($q) => $q->where('programs.id', $kelas->program_id))
                    ->with(['nomorInduks'])
                    ->orderBy('name')
                    ->get();


        $reportData = [];

        foreach ($participants as $user) {
            $components = [];
            $totalScore = 0;
            $componentCount = 0;

            // --- Assignment ---
            if ($kelas->assignments->isNotEmpty()) {
                $totalAssignmentScore = 0;
                $gradedCount = 0;

                foreach ($kelas->assignments as $assignment) {
                    $submission = $assignment->submissions->where('user_id', $user->id)->first();
                    if ($submission && $submission->is_graded) {
                        $normalizedScore = ($submission->score / $assignment->max_points) * 100;
                        $totalAssignmentScore += $normalizedScore;
                        $gradedCount++;
                    }
                }

                if ($gradedCount > 0) {
                    $avgAssignment = $totalAssignmentScore / $kelas->assignments->count();
                    $components['Tugas'] = round($avgAssignment, 2);
                    $totalScore += $avgAssignment;
                    $componentCount++;
                }
            }

            // --- Quiz ---
            if ($kelas->quizzes->isNotEmpty()) {
                $totalQuizScore = 0;

                foreach ($kelas->quizzes as $quiz) {
                    $bestAttempt = $quiz->attempts->where('user_id', $user->id)->sortByDesc('score')->first();
                    $score = $bestAttempt ? $bestAttempt->score : null;
                    if (!is_null($score)) {
                        $totalQuizScore += $score;
                    }
                }

                if ($totalQuizScore > 0) {
                    $avgQuiz = $totalQuizScore / $kelas->quizzes->count();
                    $components['Quiz Pilihan Ganda'] = round($avgQuiz, 2);
                    $totalScore += $avgQuiz;
                    $componentCount++;
                }
            }

            // --- Essay Exam ---
            if ($kelas->essayExams->isNotEmpty()) {
                $totalEssayScore = 0;
                $gradedEssayCount = 0;

                foreach ($kelas->essayExams as $exam) {
                    $submission = $exam->submissions->where('user_id', $user->id)->first();
                    if ($submission && $submission->status == 'graded') {
                        $maxPoints = $exam->questions->sum('max_score');
                        $normalized = $maxPoints > 0 ? ($submission->final_score / $maxPoints) * 100 : 0;
                        $totalEssayScore += $normalized;
                        $gradedEssayCount++;
                    }
                }

                if ($gradedEssayCount > 0) {
                    $avgEssay = $totalEssayScore / $kelas->essayExams->count();
                    $components['Ujian Esai'] = round($avgEssay, 2);
                    $totalScore += $avgEssay;
                    $componentCount++;
                }
            }

            // --- Presensi ---
            if ($kelas->presensiSetup) {
                $hasil = $kelas->presensiHasil->where('user_id', $user->id)->first();
                $attendanceScore = 0;

                if ($hasil) {
                    if ($hasil->status_kehadiran == 'hadir_full') $attendanceScore = 100;
                    elseif ($hasil->status_kehadiran == 'hadir_awal' || $hasil->status_kehadiran == 'hadir_akhir') $attendanceScore = 50;
                }

                if ($hasil) {
                    $components['Presensi'] = $attendanceScore;
                    $totalScore += $attendanceScore;
                    $componentCount++;
                }
            }

            // --- Learning Path ---
            if ($kelas->learningPath && $kelas->learningPath->sections->count() > 0) {
                $totalSections = $kelas->learningPath->sections->count();
                $sectionIds = $kelas->learningPath->sections->pluck('id')->toArray();
                $completedSections = DB::table('path_section_user')
                                        ->whereIn('path_section_id', $sectionIds)
                                        ->where('user_id', $user->id)
                                        ->count();
                if ($completedSections > 0) {
                    $components['Learning Path'] = round(($completedSections / $totalSections) * 100, 2);
                    $totalScore += ($completedSections / $totalSections) * 100;
                    $componentCount++;
                }
            }

            // --- Video ---
            if ($kelas->videoEmbeds->count() > 0) {
                $totalVideos = $kelas->videoEmbeds->count();
                $watchedVideos = $user->watchedVideos()
                    ->whereIn('video_embed_id', $kelas->videoEmbeds->pluck('id'))
                    ->count();

                if ($watchedVideos > 0) {
                    $components['Video Wajib'] = round(($watchedVideos / $totalVideos) * 100, 2);
                    $totalScore += ($watchedVideos / $totalVideos) * 100;
                    $componentCount++;
                }
            }

            // --- Modul ---
            if ($kelas->modules->count() > 0) {
                $totalModules = $kelas->modules->count();
                $readModules = DB::table('module_user')
                                 ->whereIn('module_id', $kelas->modules->pluck('id'))
                                 ->where('user_id', $user->id)
                                 ->count();

                if ($readModules > 0) {
                    $components['Modul Bacaan'] = round(($readModules / $totalModules) * 100, 2);
                    $totalScore += ($readModules / $totalModules) * 100;
                    $componentCount++;
                }
            }

            // --- Custom Grades ---
            if ($kelas->customGrades->isNotEmpty()) {
                $totalCustom = 0;
                $customCount = 0;

                foreach ($kelas->customGrades as $custom) {
                    $userGrade = $custom->users->where('id', $user->id)->first();
                    if ($userGrade) {
                        $totalCustom += $userGrade->pivot->score;
                        $customCount++;
                    }
                }

                if ($customCount > 0) {
                    $components['Nilai Tambahan'] = round($totalCustom / $customCount, 2);
                    $totalScore += $totalCustom / $customCount;
                    $componentCount++;
                }
            }

            // Hitung final rata-rata
            $finalGrade = $componentCount > 0 ? $totalScore / $componentCount : null;

            $reportData[] = [
                'user' => $user,
                'components' => $components,
                'finalGrade' => $finalGrade !== null ? round($finalGrade, 2) : '-'
            ];
        }

        return view('adminprogram.raport.show', compact('kelas', 'reportData'));
    }
}
