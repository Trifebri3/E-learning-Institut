<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Quiz;

class QuizCard extends Component
{
    public $quiz;

    public function __construct(Quiz $quiz)
    {
        $this->quiz = $quiz;
    }

    public function render()
    {
        return view('components.quiz-card');
    }
}
