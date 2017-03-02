<?php

namespace App\DomainClasses;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    public function student_groups()
    {
        return $this->belongsToMany(StudentGroup::class, "faculty_student_group")->using('App\DomainClasses\Faculty_Student_GroupPivot');
    }
}
