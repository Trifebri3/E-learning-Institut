<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EssaySubmission extends Model {
    protected $guarded = [];
    protected $dates = ['started_at', 'submitted_at'];

    public function exam()
    {
        return $this->belongsTo(EssayExam::class, 'essay_exam_id');
    }

    // Relasi ke jawaban
public function answers() {
    return $this->hasMany(EssayAnswer::class, 'essay_submission_id');
}
// di EssaySubmission.php
public function getSubmittedAtFormattedAttribute()
{
    return $this->submitted_at
        ? \Carbon\Carbon::parse($this->submitted_at)->format('d F Y, H:i')
        : 'Belum Disubmit';
}
public function user()
{
    return $this->belongsTo(\App\Models\User::class, 'user_id');
}
public function essaySubmissions()
{
    return $this->hasMany(\App\Models\EssaySubmission::class);
}

}

