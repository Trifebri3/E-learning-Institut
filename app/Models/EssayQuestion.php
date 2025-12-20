<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EssayQuestion extends Model {
    protected $guarded = [];
    public function exam() { return $this->belongsTo(EssayExam::class); }
}
