<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EssayExam extends Model {
    protected $guarded = [];

    public function kelas() { return $this->belongsTo(Kelas::class); }
    public function questions() { return $this->hasMany(EssayQuestion::class); }
    public function submissions() { return $this->hasMany(EssaySubmission::class); }

    public function userSubmission() {
        return $this->submissions()
            ->where('user_id', Auth::id())
            ->latest()
            ->first();
    }
}
