<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $fillable = [
        'classroom_id',
        'subject_id',
        'week_number',
        'topic',
        'meeting_date'
    ];

    // Link back to the Class
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    // Link back to the Subject
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    protected $casts = [
        'meeting_date' => 'datetime', // This enables the ->format() method
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function rollCalls()
    {
        return $this->hasMany(Attendance::class, 'meeting_id');
    }
}
