<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Quiz extends Model {
    protected $guarded = [];

    public function kelas() { return $this->belongsTo(Kelas::class); }
    public function questions() { return $this->hasMany(Question::class); }
    public function attempts() { return $this->hasMany(QuizAttempt::class); }

    // Helper: Cek sisa kesempatan user
    public function remainingAttempts() {
        $used = $this->attempts()->where('user_id', Auth::id())->count();
        return max(0, $this->max_attempts - $used);
    }


    // relasi ke attempts (submission)
    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }


}
