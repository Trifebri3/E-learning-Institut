<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Narasumber extends Model
{
   use HasFactory;
    protected $guarded = [];

    // Milik Program
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    // Bisa mengajar di banyak kelas
    public function kelas()
    {
        return $this->belongsToMany(Kelas::class, 'kelas_narasumber');
    }
}
