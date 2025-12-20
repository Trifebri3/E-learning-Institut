<?php

namespace App\Services;

use App\Models\GradeSetting;
use App\Models\CustomGradeValue;
use Illuminate\Support\Facades\DB;

class GradeCalculationService
{
    public function calculateAllScores($kelasId, $userId)
    {
        return [
            'presensi' => $this->calculatePresensiScore($kelasId, $userId),
            'tugas' => $this->calculateTugasScore($kelasId, $userId),
            'quiz' => $this->calculateQuizScore($kelasId, $userId),
            'essay' => $this->calculateEssayScore($kelasId, $userId),
            'progress' => $this->calculateProgressScore($kelasId, $userId),
            'custom' => $this->calculateCustomScore($kelasId, $userId),
        ];
    }

    public function calculateFinalScore($kelasId, $scores)
    {
        $gradeSetting = GradeSetting::where('kelas_id', $kelasId)->first();

        if (!$gradeSetting) {
            return 0;
        }

        $totalScore = 0;

        // Hitung berdasarkan bobot yang ada
        $totalScore += ($scores['presensi'] * $gradeSetting->weight_presensi) / 100;

        // Hanya hitung komponen yang ada nilainya (tidak 0)
        if ($scores['tugas'] > 0) {
            $totalScore += ($scores['tugas'] * $gradeSetting->weight_tugas) / 100;
        }

        if ($scores['quiz'] > 0) {
            $totalScore += ($scores['quiz'] * $gradeSetting->weight_quiz) / 100;
        }

        if ($scores['essay'] > 0) {
            $totalScore += ($scores['essay'] * $gradeSetting->weight_essay) / 100;
        }

        if ($scores['progress'] > 0) {
            $totalScore += ($scores['progress'] * $gradeSetting->weight_progress) / 100;
        }

        if ($scores['custom'] > 0) {
            $totalScore += ($scores['custom'] * $gradeSetting->weight_custom) / 100;
        }

        return round($totalScore, 2);
    }

    public function calculateLetterGrade($score)
    {
        if ($score >= 85) return 'A';
        if ($score >= 75) return 'B';
        if ($score >= 65) return 'C';
        if ($score >= 55) return 'D';
        return 'E';
    }

    public function generateAutoFeedback($letterGrade)
    {
        $feedbacks = [
            'A' => 'Sangat memuaskan! Anda telah menguasai semua materi dengan excellent.',
            'B' => 'Baik! Pemahaman Anda terhadap materi sudah cukup baik.',
            'C' => 'Cukup! Perlu peningkatan pada beberapa aspek pembelajaran.',
            'D' => 'Kurang! Disarankan untuk mengulang materi yang belum dipahami.',
            'E' => 'Sangat kurang! Perlu belajar lebih giat dan konsultasi dengan pengajar.',
        ];

        return $feedbacks[$letterGrade] ?? 'Belum dapat dinilai.';
    }

    protected function calculatePresensiScore($kelasId, $userId)
    {
        // Logic untuk menghitung nilai presensi
        // Contoh: (jumlah hadir / total sesi) * 100
        $totalSessions = 16; // Contoh total sesi
        $attendedSessions = 14; // Contoh jumlah hadir

        return ($attendedSessions / $totalSessions) * 100;
    }

    protected function calculateTugasScore($kelasId, $userId)
    {
        // Logic untuk menghitung nilai tugas
        // Return 0 jika tidak ada tugas
        return 85; // Contoh nilai
    }

    protected function calculateQuizScore($kelasId, $userId)
    {
        // Logic untuk menghitung nilai quiz
        // Return 0 jika tidak ada quiz
        return 78; // Contoh nilai
    }

    protected function calculateEssayScore($kelasId, $userId)
    {
        // Logic untuk menghitung nilai essay
        // Return 0 jika tidak ada essay
        return 0; // Contoh: tidak ada essay
    }

    protected function calculateProgressScore($kelasId, $userId)
    {
        // Logic untuk menghitung progress belajar
        // (modul selesai / total modul) * 100
        $totalModules = 10;
        $completedModules = 8;

        return ($completedModules / $totalModules) * 100;
    }

    protected function calculateCustomScore($kelasId, $userId)
    {
        // Hitung rata-rata nilai custom
        $customScores = CustomGradeValue::whereHas('customGradeColumn', function($query) use ($kelasId) {
                $query->where('kelas_id', $kelasId);
            })
            ->where('user_id', $userId)
            ->pluck('score');

        if ($customScores->isEmpty()) {
            return 0;
        }

        return $customScores->avg();
    }
}
