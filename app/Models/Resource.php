<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    // ...
    protected $guarded = [];
    protected $table = 'resources';

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    // Siapa saja yang sudah mengakses resource ini

    public function users()
{
    return $this->belongsToMany(User::class, 'resource_user')
        ->withPivot('opened_at')
        ->withTimestamps();
}

}
