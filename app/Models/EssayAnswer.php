<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EssayAnswer extends Model {
    protected $guarded = [];
        protected $dates = [
        'started_at',
        'submitted_at',
        'created_at',
        'updated_at'
    ];
        public function essayQuestion()
    {
        return $this->belongsTo(EssayQuestion::class, 'essay_question_id');
    }
        public function question() // <== ini harus sesuai nama yang dipanggil di Blade
    {
        return $this->belongsTo(EssayQuestion::class, 'essay_question_id');
    }

    // Relasi ke submission (opsional)
    public function submission()
    {
        return $this->belongsTo(EssaySubmission::class, 'essay_submission_id');
    }
}
