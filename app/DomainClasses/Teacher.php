<?php

namespace App\DomainClasses;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    public $timestamps = false;

    public function disciplines()
    {
        return $this->belongsToMany(Discipline::class, 'discipline_teacher')->using('App\DomainClasses\Discipline_TeacherPivot');
    }
}
