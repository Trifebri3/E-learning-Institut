<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresensiHasil extends Model
{
    // ...
protected $guarded = [];
public function user() { return $this->belongsTo(User::class); }
public function kelas() { return $this->belongsTo(Kelas::class); }
public function nomorInduk() { return $this->belongsTo(NomorInduk::class); }
}
