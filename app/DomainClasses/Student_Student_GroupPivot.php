<?php

namespace App\DomainClasses;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Student_Student_GroupPivot extends Pivot
{
    protected $table = 'student_student_group';
}
