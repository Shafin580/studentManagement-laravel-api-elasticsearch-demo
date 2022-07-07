<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Routine extends Model
{
    use HasFactory;

    protected $fillable = [
        'classId',
        'courseId',
        'weekDayId',
        'classBeginTime',
        'classEndTime',

    ];

    public function weekDay()
    {
        return $this->belongsTo(WeekDay::class, 'weekDayId', 'id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'courseId', 'id');
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'classId', 'id');
    }
}
