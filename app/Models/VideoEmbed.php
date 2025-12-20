<?php

namespace App\Models;
use App\Models\User; // <-- WAJIB
use App\Models\Kelas; // opsional tapi disarankan
use Illuminate\Database\Eloquent\Model;

class VideoEmbed extends Model
{
    //
    protected $guarded = [];
    protected $fillable = [
        'kelas_id', 'title', 'youtube_id', 'description', 'is_published'
    ];



// app/Models/VideoEmbed.php
public function watchedByUsers()
{
    return $this->belongsToMany(\App\Models\User::class, 'video_embed_user', 'video_embed_id', 'user_id')
                ->withPivot('watched_at')
                ->withTimestamps();
}


    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
