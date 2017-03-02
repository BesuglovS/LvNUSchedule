<?php

namespace App\DomainClasses;

use Illuminate\Database\Eloquent\Model;

class Student_Student_Group extends Model
{
    protected $table = 'student_student_group';


    public function student() {
        return $this->belongsTo(Student::class);
    }

    public function student_group() {
        return $this->belongsTo(StudentGroup::class);
    }
}
