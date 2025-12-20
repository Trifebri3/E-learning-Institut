<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Peserta (users) yang terdaftar di program ini.
     */



    public function kelas()
    {
        return $this->hasMany(Kelas::class);
    }

    public function scopeActive($query)
    {
        $today = now();
        return $query->where('tanggal_mulai', '<=', $today)
                     ->where('tanggal_selesai', '>=', $today);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'program_user')
                    ->withTimestamps();
    }

    public function badgeTemplate()
    {
        return $this->hasOne(BadgeTemplate::class);
    }

    public function narasumbers()
    {
        return $this->hasMany(Narasumber::class);
    }

    public function classes()
    {
        return $this->hasMany(Kelas::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function exams()
    {
        return $this->hasMany(EssayExam::class);
    }

    public function essayExams()
    {
        return $this->hasMany(\App\Models\EssayExam::class, 'program_id');
    }

    /**
     * Accessor untuk materials_count
     */
    public function getMaterialsCountAttribute()
    {
        return $this->kelas->sum(function($kelas) {
            return ($kelas->modules->count() ?? 0) + ($kelas->videoEmbeds->count() ?? 0);
        });
    }

    /**
     * Scope untuk program yang diikuti user
     */
    public function scopeJoinedBy($query, $userId)
    {
        return $query->whereHas('participants', function($q) use ($userId) {
            $q->where('users.id', $userId);
        });
    }

    /**
     * Scope untuk program yang belum diikuti user
     */
    public function scopeNotJoinedBy($query, $userId)
    {
        return $query->whereDoesntHave('participants', function($q) use ($userId) {
            $q->where('users.id', $userId);
        });
    }
    public function instructors()
{
    return $this->belongsToMany(User::class, 'program_instructor', 'program_id', 'user_id');
}

// Peserta/instruktur
public function participants()
{
    return $this->belongsToMany(
        User::class,
        'program_instructor',
        'program_id',
        'user_id'
    );
}

// Admin
public function admins()
{
    return $this->belongsToMany(
        User::class,
        'program_admin',
        'program_id',
        'user_id'
    );
}


}
