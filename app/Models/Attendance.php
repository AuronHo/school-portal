<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }
}
