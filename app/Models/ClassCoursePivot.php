<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassCoursePivot extends Model
{
    protected $fillable = [
        'classId',
        'courseId',
    ];

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'classId', 'id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'courseId', 'id');
    }
}
