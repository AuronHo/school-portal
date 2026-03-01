<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskSubmission extends Model
{
    protected $fillable = [
        'task_id',
        'student_id',
        'file_path',
        'grade',
        'teacher_feedback'
    ];

    // Link back to the task
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    // Link to the student (who is a User)
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}