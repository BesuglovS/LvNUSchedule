<?php

namespace App\DomainClasses;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Faculty_Student_GroupPivot extends Pivot
{
    protected $table = 'faculty_student_group';
}
