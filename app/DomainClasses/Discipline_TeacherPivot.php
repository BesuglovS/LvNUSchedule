<?php

namespace App\DomainClasses;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Discipline_TeacherPivot extends Pivot
{
    protected $table = 'discipline_teacher';
}
