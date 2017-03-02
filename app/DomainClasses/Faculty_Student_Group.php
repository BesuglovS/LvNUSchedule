<?php

namespace App\DomainClasses;

use Illuminate\Database\Eloquent\Model;

class Faculty_Student_Group extends Model
{
    protected $table = 'faculty_student_group';


    public function faculty() {
        return $this->belongsTo(Faculty::class);
    }

    public function student_group() {
        return $this->belongsTo(StudentGroup::class);
    }
}
