<?php

namespace App\DomainClasses;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Discipline_Teacher extends Model
{
    protected $table = 'discipline_teacher';

    public static function IdsFromDisciplineIds($disciplineIds)
    {
        return DB::table('discipline_teacher')
            ->whereIn('discipline_id', $disciplineIds)
            ->select('id')
            ->get()
            ->map(function($item) { return $item->id;});
    }

    public static function FromDisciplineIds($disciplineIds)
    {
        return DB::table('discipline_teacher')
            ->whereIn('discipline_id', $disciplineIds)
            ->get();
    }

    public static function TeacherFioFromDisciplineId($disciplineId)
    {
        $result = DB::table('discipline_teacher')
            ->where('discipline_id', '=', $disciplineId)
            ->join('teachers', 'teacher_id', '=', 'teachers.id')
            ->select('teachers.fio')
            ->get()
            ->map(function($item) { return $item->fio;});

        return (count($result) > 0) ? $result[0] : "";
    }

    public static function IdsFromTeacherId($teacherId)
    {
        return DB::table('discipline_teacher')
            ->where('teacher_id', '=', $teacherId)
            ->select('id')
            ->get()
            ->map(function($item) { return $item->id;});
    }

    public function discipline() {
        return $this->belongsTo(Discipline::class);
    }

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }
}
