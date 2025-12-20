<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Assignment extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
    'due_date' => 'datetime',
];


    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function userSubmission($userId)
    {
        return $this->submissions()->where('user_id', $userId)->first();
    }
    public function program()
{
    return $this->belongsTo(Program::class);
}

}
