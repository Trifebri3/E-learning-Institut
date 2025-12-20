<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Model;
class CustomGradeColumn extends Model { protected $guarded = [];
    public function values(): HasMany
    {
        return $this->hasMany(CustomGradeValue::class, 'custom_grade_column_id');
    }
}
