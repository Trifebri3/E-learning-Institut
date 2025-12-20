<?php


namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $fillable = [
    'kelas_id',
    'title',
    'content',
    'is_mandatory',
    'order',
    'is_published'
];


    public function users()
    {
        return $this->belongsToMany(User::class, 'module_user')
                    ->withPivot('completed_at');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
