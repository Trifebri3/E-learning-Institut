<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


use Illuminate\Database\Eloquent\Model;

class CustomGradeUser extends Model
{
    use HasFactory;

    protected $table = 'custom_grade_user';
    protected $guarded = [];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke custom grade
    public function customGrade()
    {
        return $this->belongsTo(CustomGrade::class);
    }
}
