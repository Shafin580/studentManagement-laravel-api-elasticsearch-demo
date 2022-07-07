<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function classCoursePivot()
    {
        return $this->hasMany(ClassCoursePivot::class, 'classId', 'id');
    }

    public function student()
    {
        return $this->hasMany(Student::class, 'classId', 'id');
    }

    public function routine()
    {
        return $this->hasMany(Routine::class, 'classId', 'id');
    }
}
