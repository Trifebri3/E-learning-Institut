<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Piagam extends Model
{
    // Tabel yang digunakan
    protected $table = 'piagam';

    // Mass assignment
    protected $guarded = [];
    

    // Cast untuk tanggal
    protected $casts = [
        'issued_at' => 'date',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke program
    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
