<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Forum extends Model {
    protected $guarded = [];
    public function posts() { return $this->hasMany(ForumPost::class); }
    public function program() { return $this->belongsTo(Program::class); }
}
