<?php

namespace App\DomainClasses;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Student extends Model
{
    public function student_groups()
    {
        return $this->belongsToMany(StudentGroup::class, "student_student_group")->using('App\DomainClasses\Student_Student_GroupPivot');
    }

    public static function ListOfNotExpelled()
    {
        return DB::table('students')
            ->where('expelled', '=', 0)
            ->get();
    }
}
