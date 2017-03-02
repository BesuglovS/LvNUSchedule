<?php

namespace App\DomainClasses;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StudentGroup extends Model
{
    public function disciplines()
    {
        return $this->hasMany(Discipline::class);
    }

    public function discipline_names()
    {
        return $this->hasMany(DisciplineName::class);
    }

    public function faculties()
    {
        return $this->belongsToMany(Faculty::class, "faculty_student_group")->using('App\DomainClasses\Faculty_Student_GroupPivot');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, "student_student_group")->using('App\DomainClasses\Student_Student_GroupPivot');
    }

    public static function mainStudentGroups()
    {
        $groups = StudentGroup::orderBy('name')->get();
        $result = array();

        for ($i = 1; $i < count($groups); $i++) {
            if ((strpos($groups[$i],'+Н') == false) &&
                (strpos($groups[$i],' + ') == false) &&
                (strpos($groups[$i],'-А-') == false) &&
                (strpos($groups[$i],'-Н-') == false) &&
                (strpos($groups[$i],'-Ф-') == false) &&
                (strpos($groups[$i],'I') == false))
            {
                $result[] = $groups[$i];
            }
        }

        return $result;
    }

    public static function GetGroupsOfStudentFromGroup($groupId)
    {
        $studentIds = DB::table('student_student_group')
            ->where('student_group_id', '=', $groupId)
            ->select('student_id')
            ->get()
            ->map(function($item) { return $item->student_id;});

        $groupIds = DB::table('student_student_group')
            ->whereIn('student_id', $studentIds)
            ->select('student_group_id')
            ->get()
            ->map(function($item) { return $item->student_group_id;})
            ->unique();

        return $groupIds;
    }
}
