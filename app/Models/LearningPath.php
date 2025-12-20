<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningPath extends Model
{
    protected $fillable = ['kelas_id', 'title'];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function sections()
    {
        return $this->hasMany(PathSection::class)->orderBy('order');
    }
}
