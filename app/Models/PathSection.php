<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PathSection extends Model
{
    protected $fillable = [
        'learning_path_id',
        'title',
        'content',
        'image_path',
        'order'
    ];

    public function learningPath()
    {
        return $this->belongsTo(LearningPath::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'path_section_user')
                    ->withPivot('completed_at');
    }
}
