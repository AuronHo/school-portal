<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }

    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class)->withPivot('teacher_id');
    }
}
