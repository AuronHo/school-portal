<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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

    use HasFactory;

    // Add this line to allow mass assignment for these specific columns!
    protected $fillable = [
        'name',
        'code',
        'description',
    ];
}
