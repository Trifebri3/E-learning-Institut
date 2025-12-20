<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\VideoEmbed;
use App\Models\EssaySubmission;


class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'otp',
        'otp_expires_at',
        'google_id',
                'agreed_to_tos_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function isSuperAdmin() { return $this->role === 'superadmin'; }
    public function isAdminProgram() { return $this->role === 'adminprogram'; }
    public function isInstructor() { return $this->role === 'instructor'; }
    public function isParticipant() { return $this->role === 'participant'; }
    public function profile()
{
    return $this->hasOne(Profile::class);
}






    public function nomorInduks()
{
    return $this->hasMany(NomorInduk::class);
}
// ...
public function presensiHasil() { return $this->hasMany(PresensiHasil::class); }
public function badges()
{
    return $this->belongsToMany(BadgeTemplate::class, 'badge_user')
                ->withPivot('earned_at')
                ->orderBy('earned_at', 'desc');
}
// ...
public function accessedResources()
{
    return $this->belongsToMany(Resource::class, 'resource_user');
}
public function resources()
{
    return $this->belongsToMany(Resource::class, 'resource_user')
        ->withPivot('opened_at')
        ->withTimestamps();
}
// ...
public function completedModules()
{
    return $this->belongsToMany(Module::class, 'module_user');
}
public function completedPathSections()
{
    return $this->belongsToMany(PathSection::class, 'path_section_user')
                ->withPivot('completed_at');
}
// ...
public function watchedVideoEmbeds()
{
    return $this->belongsToMany(VideoEmbed::class, 'video_embed_user')
                ->withPivot('watched_at')
                ->withoutTimestamps();
}
public function watchedVideos()
{
    return $this->belongsToMany(\App\Models\VideoEmbed::class, 'video_embed_user', 'user_id', 'video_embed_id')
                ->withPivot('watched_at')
                ->withTimestamps();
}
   public function essaySubmissions()
    {
        return $this->hasMany(EssaySubmission::class, 'user_id', 'id');
    }



public function submissions()
{
    return $this->hasMany(Submission::class);
}
// Untuk melihat riwayat ujian user
public function quizAttempts() {
    return $this->hasMany(QuizAttempt::class);
}
// ...
public function readAnnouncements() {
    return $this->belongsToMany(Announcement::class, 'announcement_user')
                ->withPivot('read_at');
}
// ...
public function sentMessages() { return $this->hasMany(DirectMessage::class, 'sender_id'); }
public function receivedMessages() { return $this->hasMany(DirectMessage::class, 'receiver_id'); }
    public function managedPrograms()
    {
        return $this->belongsToMany(Program::class, 'programadmins');
    }
    // Relasi untuk Admin Program (Program yang mereka kelola)


    public function nomorInduk()
{
    return $this->hasOne(\App\Models\NomorInduk::class);
}


// Relasi ke Piagam
public function piagam()
{
    return $this->hasMany(Piagam::class);
}

// Program sebagai peserta/instruktur
public function programs()
{
    return $this->belongsToMany(
        Program::class,
        'program_instructor', // nama pivot table
        'user_id',
        'program_id'
    );
}

// Program yang dikelola sebagai admin
// Program yang dikelola sebagai admin
public function administeredPrograms()
{
    return $this->belongsToMany(
        Program::class,
        'program_admin', // tabel pivot admin
        'user_id',
        'program_id'
    );
}

// Program yang dia jadi instruktur
public function instructedPrograms()
{
    return $this->belongsToMany(
        Program::class,
        'program_instructor', // tabel pivot instruktur
        'user_id',
        'program_id'
    );
}




}

