<?php

namespace App\DomainClasses;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    public function discipline()
    {
        return $this->belongsTo(Discipline::class);
    }

    public function consultation_auditorium()
    {
        return $this->belongsTo(Auditorium::class);
    }

    public function exam_auditorium()
    {
        return $this->belongsTo(Auditorium::class);
    }
}
