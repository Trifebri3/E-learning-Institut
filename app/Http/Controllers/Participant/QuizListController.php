<?php
namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Program;

class QuizListController extends Controller
{
    public function kelas($kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $quizzes = $kelas->quizzes;

        return view('participant.quiz.list', compact('kelas', 'quizzes'));
    }

    public function program($programId)
    {
        $program = Program::findOrFail($programId);
        $quizzes = $program->quizzes;

        return view('participant.quiz.program', compact('program', 'quizzes'));
    }
}
