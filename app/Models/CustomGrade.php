<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomGrade extends Model {
    use HasFactory;

    protected $guarded = [];

    // Relasi ke kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    // Relasi ke user (many-to-many melalui custom_grade_user)
    public function users()
    {
        return $this->belongsToMany(User::class, 'custom_grade_user')
                    ->withPivot('score', 'feedback')
                    ->withTimestamps();
    }

    // Relasi ke pivot table untuk query langsung
    public function userGrades()
    {
        return $this->hasMany(CustomGradeUser::class);
    }
}
