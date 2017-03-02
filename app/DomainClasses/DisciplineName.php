<?php

namespace App\DomainClasses;

use Illuminate\Database\Eloquent\Model;

class DisciplineName extends Model
{
    public function discipline()
    {
        return $this->belongsTo(Discipline::class);
    }

    public function student_group()
    {
        return $this->belongsTo(StudentGroup::class);
    }
}
