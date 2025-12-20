<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kelas;
use App\Models\User;
use App\Models\CustomGradeValue;
use App\Models\ClassReport;
use App\Models\Program;

class ProgressControllerIN extends Controller
{
    /**
     * Halaman Index: Daftar Kelas yang diajar Instruktur.
     */
    public function index()
    {
        $user = Auth::user();

        // Ambil ID Program yang diajar instruktur
        $programIds = $user->instructedPrograms()->pluck('programs.id');

        // Ambil Kelas yang ada di program tersebut
        // DAN Instruktur ini harus terdaftar sebagai pengajar di kelas tersebut (Pivot kelas_narasumber)
        // Atau kita asumsikan jika dia instruktur program, dia bisa lihat semua kelas di program itu?
        // Sesuai struktur sebelumnya: Instruktur di-assign ke PROGRAM, tapi bisa juga spesifik di KELAS (Narasumber).
        // Kita gunakan scope Program saja agar lebih luas (Instruktur Program).

        $programs = Program::whereIn('id', $programIds)
            ->with(['kelas' => function($q) {
                $q->orderBy('tanggal', 'desc');
            }])
            ->get();

        return view('instructor.progress.index', compact('programs'));
    }

    /**
     * Halaman Detail: Leger Nilai Siswa di Kelas.
     */
    public function show($kelasId)
    {
        $user = Auth::user();
        $kelas = Kelas::with(['program', 'gradeSetting', 'customGradeColumns'])->findOrFail($kelasId);

        // Security Check: Pastikan instruktur mengajar di program ini
        if (!$user->instructedPrograms->contains($kelas->program_id)) {
            abort(403, 'Akses Ditolak. Anda bukan instruktur program ini.');
        }

        // Ambil Peserta
        $participants = User::whereHas('programs', function($q) use ($kelas) {
            $q->where('program_id', $kelas->program_id);
        })->with(['profile', 'nomorInduks' => function($q) use ($kelas) {
            $q->where('program_id', $kelas->program_id);
        }])->orderBy('name')->get();

        // Ambil Nilai yang sudah ada
        $reports = ClassReport::where('kelas_id', $kelasId)->get()->keyBy('user_id');

        // Ambil Nilai Manual
        $customValues = CustomGradeValue::whereIn('custom_grade_column_id', $kelas->customGradeColumns->pluck('id'))
                                        ->get()
                                        ->groupBy('user_id');

        return view('instructor.progress.show', compact('kelas', 'participants', 'reports', 'customValues'));
    }

    /**
     * Input Nilai Manual via AJAX (Sikap, dll).
     */
    public function storeCustomScore(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'column_id' => 'required|exists:custom_grade_columns,id',
            'score' => 'required|numeric|min:0|max:100',
        ]);

        // Instruktur boleh input nilai manual
        CustomGradeValue::updateOrCreate(
            [
                'custom_grade_column_id' => $request->column_id,
                'user_id' => $request->user_id,
            ],
            ['score' => $request->score]
        );

        return response()->json(['success' => true]);
    }

    /**
     * Trigger Hitung Nilai Otomatis (Kalkulasi Ulang).
     * Instruktur butuh ini untuk melihat update nilai setelah menilai tugas.
     */
    public function calculate(Request $request, $kelasId)
    {
        // Kita bisa reuse logika dari EraportControllerAP dengan memanggil service atau copy logic.
        // Untuk kebersihan, kita salin logika intinya di sini agar independen.

        $kelas = Kelas::with([
            'gradeSetting', 'customGradeColumns', 'presensiSetup',
            'assignments', 'quizzes', 'essayExams', 'videoEmbeds',
            'modules', 'learningPath.sections'
        ])->findOrFail($kelasId);

        $settings = $kelas->gradeSetting;
        if (!$settings) return back()->with('error', 'Bobot penilaian belum diatur oleh Admin Program.');

        $users = User::whereHas('programs', fn($q) => $q->where('program_id', $kelas->program_id))->get();

        foreach ($users as $user) {
            // 1. Presensi
            $presensiScore = 0;
            if ($kelas->presensiSetup) {
                $hasil = $kelas->presensiHasil()->where('user_id', $user->id)->first();
                if ($hasil && $hasil->status_kehadiran == 'hadir_full') $presensiScore = 100;
                elseif ($hasil && in_array($hasil->status_kehadiran, ['hadir_awal', 'hadir_akhir'])) $presensiScore = 50;
            }

            // 2. Tugas
            $tugasScore = 0;
            if ($kelas->assignments->count() > 0) {
                $totalMax = 0; $earned = 0;
                foreach ($kelas->assignments as $asg) {
                    $sub = $asg->userSubmission($user->id);
                    $totalMax += $asg->max_points;
                    if ($sub && $sub->is_graded) $earned += $sub->score;
                }
                $tugasScore = $totalMax > 0 ? ($earned / $totalMax) * 100 : 0;
            } elseif ($settings->weight_tugas > 0) { $tugasScore = 100; }

            // 3. Quiz
            $quizScore = 0;
            if ($kelas->quizzes->count() > 0) {
                $totalQ = 0;
                foreach ($kelas->quizzes as $quiz) {
                    $best = $quiz->attempts()->where('user_id', $user->id)->max('score');
                    $totalQ += ($best ?? 0);
                }
                $quizScore = $totalQ / $kelas->quizzes->count();
            } elseif ($settings->weight_quiz > 0) { $quizScore = 100; }

            // 4. Essay
            $essayScore = 0;
            if ($kelas->essayExams->count() > 0) {
                $totalE = 0;
                foreach ($kelas->essayExams as $exam) {
                    $sub = $exam->submissions()->where('user_id', $user->id)->where('status', 'graded')->first();
                    $totalE += $sub ? $sub->final_score : 0;
                }
                $essayScore = $totalE / $kelas->essayExams->count();
            } elseif ($settings->weight_essay > 0) { $essayScore = 100; }

            // 5. Progress
            $totalItems = $kelas->videoEmbeds->count() + $kelas->modules->count();
            if ($kelas->learningPath) $totalItems += $kelas->learningPath->sections->count();

            $completed = 0;
            $completed += $user->watchedVideos()->whereIn('video_embed_id', $kelas->videoEmbeds->pluck('id'))->count();
            $completed += $user->completedModules()->whereIn('module_id', $kelas->modules->pluck('id'))->count();
            if ($kelas->learningPath) {
                $completed += $user->completedPathSections()->whereIn('path_section_id', $kelas->learningPath->sections->pluck('id'))->count();
            }
            $progressScore = $totalItems > 0 ? ($completed / $totalItems) * 100 : 100;

            // 6. Custom
            $customTotal = 0;
            if ($kelas->customGradeColumns->count() > 0) {
                $cols = $kelas->customGradeColumns;
                $userCustomVals = CustomGradeValue::whereIn('custom_grade_column_id', $cols->pluck('id'))
                                                  ->where('user_id', $user->id)->avg('score');
                $customTotal = $userCustomVals ?? 0;
            } elseif ($settings->weight_custom > 0) { $customTotal = 100; }

            // Final
            $finalScore =
                ($presensiScore * ($settings->weight_presensi / 100)) +
                ($tugasScore * ($settings->weight_tugas / 100)) +
                ($quizScore * ($settings->weight_quiz / 100)) +
                ($essayScore * ($settings->weight_essay / 100)) +
                ($progressScore * ($settings->weight_progress / 100)) +
                ($customTotal * ($settings->weight_custom / 100));

            // Huruf
            $letter = 'E';
            if ($finalScore >= 85) $letter = 'A';
            elseif ($finalScore >= 75) $letter = 'B';
            elseif ($finalScore >= 60) $letter = 'C';
            elseif ($finalScore >= 50) $letter = 'D';

            // Simpan Rapor (Hanya update nilai, tidak ubah status lulus/publish yang diatur admin)
            ClassReport::updateOrCreate(
                ['kelas_id' => $kelas->id, 'user_id' => $user->id],
                [
                    'score_presensi' => $presensiScore,
                    'score_tugas' => $tugasScore,
                    'score_quiz' => $quizScore,
                    'score_essay' => $essayScore,
                    'score_progress' => $progressScore,
                    'score_custom' => $customTotal,
                    'final_score' => $finalScore,
                    'letter_grade' => $letter,
                ]
            );
        }

        return back()->with('success', 'Nilai peserta berhasil dihitung ulang.');
    }
}
