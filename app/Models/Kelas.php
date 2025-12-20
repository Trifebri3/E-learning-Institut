<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;
    protected $guarded = []; // Izinkan mass assignment
    protected $table = 'kelas'; // Tentukan nama tabel

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function presensiSetup()
{
    return $this->hasOne(PresensiSetup::class);
}
    public function participants()
    {
        return $this->belongsToMany(User::class, 'kelas_user', 'kelas_id', 'user_id')
                    ->withTimestamps();
    }

// Hasil presensi dari semua peserta (1-to-Many)
public function presensiHasil()
{
    return $this->hasMany(PresensiHasil::class);
}
public function isFinished($user = null)
{
    // Jika tidak ada tanggal, anggap belum selesai
    if (!$this->tanggal) {
        return false;
    }

    // Gabungkan tanggal + jam_selesai
    $endDateTime = $this->jam_selesai
        ? \Carbon\Carbon::parse("{$this->tanggal} {$this->jam_selesai}")
        : \Carbon\Carbon::parse("{$this->tanggal} 23:59:59"); // fallback

    // Jika waktu selesai sudah lewat → selesai
    return now()->greaterThan($endDateTime);
}

public function narasumbers()
{
    return $this->belongsToMany(Narasumber::class, 'kelas_narasumber');
}
// ...
public function resources()
{
    return $this->hasMany(Resource::class, 'kelas_id');
}
// ...
public function modules()
{
    return $this->hasMany(Module::class)->orderBy('order');
}
public function learningPath()
    {
        return $this->hasOne(LearningPath::class);
    }
    // ...
public function videoEmbeds()
{
    return $this->hasMany(VideoEmbed::class);
}
public function assignments()
{
    return $this->hasMany(Assignment::class);
}

public function quizzes() {
    return $this->hasMany(Quiz::class);
}
public function essayExams()
{
    return $this->hasMany(EssayExam::class, 'kelas_id');
}
// ...
public function customGrades() {
    return $this->hasMany(CustomGrade::class);
}
    public function essayExam()
    {
        return $this->hasOne(EssayExam::class);
    }



    // Grade setting untuk kelas ini
    public function gradeSetting()
    {
        return $this->hasOne(GradeSetting::class);
    }

    // Kolom nilai manual (custom) untuk kelas ini
    public function customColumns()
    {
        return $this->hasMany(CustomGradeColumn::class);
    }




    /**
     * Relationship dengan Custom Grade Columns
     */
    public function customGradeColumns()
    {
        return $this->hasMany(CustomGradeColumn::class, 'kelas_id');
    }


    /**
     * Relationship dengan Class Reports
     */
    public function classReports()
    {
        return $this->hasMany(ClassReport::class, 'kelas_id');
    }






}
