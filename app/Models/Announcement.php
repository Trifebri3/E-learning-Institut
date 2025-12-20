<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Announcement extends Model
{
    protected $guarded = [];

    public function program() {
        return $this->belongsTo(Program::class);
    }

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi ke user yang SUDAH membaca
    public function readers() {
        return $this->belongsToMany(User::class, 'announcement_user')
                    ->withPivot('read_at');
    }

    // Helper: Cek apakah user login sudah membaca ini
    public function isReadByCurrentUser() {
        return $this->readers()->where('user_id', Auth::id())->exists();
    }
}
