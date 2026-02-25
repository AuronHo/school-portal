<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $guarded = [];

    // Database relationship to subject, teacher and task submission
    public function subject() {
    return $this->belongsTo(Subject::class);
    }

    public function teacher() {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function submissions() {
        return $this->hasMany(TaskSubmission::class);
    }

    protected $fillable = ['meeting_id', 'teacher_id', 'title', 'description', 'due_date', 'file_path'];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }
}
