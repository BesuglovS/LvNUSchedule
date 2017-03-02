<?php

namespace App\DomainClasses;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    public function student_groups()
    {
        return $this->belongsToMany(StudentGroup::class, "student_student_group")->using('App\DomainClasses\Student_Student_GroupPivot');
    }
}
