<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeekDay extends Model
{
    protected $fillable = [
        'name',
    ];

    public function routine()
    {
        return $this->hasMany(Routine::class, 'weekDayId', 'id');
    }
}
