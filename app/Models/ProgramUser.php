<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProgramUser extends Pivot
{
    protected $table = 'program_user';

    protected $fillable = [
        'program_id',
        'user_id',
        'enrollment_method'
    ];
    
}

