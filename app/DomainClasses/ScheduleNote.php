<?php

namespace App\DomainClasses;

use Illuminate\Database\Eloquent\Model;

class ScheduleNote extends Model
{
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
