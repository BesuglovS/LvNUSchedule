<?php

namespace App\DomainClasses;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Discipline extends Model
{
    public function student_group()
    {
        return $this->belongsTo(StudentGroup::class);
    }

    public function discipline_names()
    {
        return $this->hasMany(DisciplineName::class);
    }

    public function teacher()
    {
        return $this->belongsToMany(Teacher::class, 'discipline_teacher')->using('App\DomainClasses\Discipline_TeacherPivot');
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public static function IdsFromGroupId($groupId) {
        $groupIds = StudentGroup::GetGroupsOfStudentFromGroup($groupId);
        return static::IdsFromGroupIdsStraight($groupIds);
    }

    public static function IdsFromGroupIdsStraight($groupIds) {
        return DB::table('disciplines')
            ->whereIn('student_group_id', $groupIds)
            ->select('id')
            ->get()
            ->map(function($item) { return $item->id;});
    }
}
