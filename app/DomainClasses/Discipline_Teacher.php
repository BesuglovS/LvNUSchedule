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

    public static function DisciplineListFromTeacherIdWithStat($teacherId)
    {
        $discList = DB::table('discipline_teacher')
            ->where('teacher_id', '=', $teacherId)
            ->join('disciplines', 'discipline_teacher.discipline_id', '=', 'disciplines.id')
            ->join('student_groups', 'disciplines.student_group_id', '=', 'student_groups.id')
            ->select('disciplines.*', 'student_groups.name as student_group_name', 'discipline_teacher.id as tfd_id')
            ->get()
            ->toArray();

        $monthList = array();
        $disciplines = array();

        for($i = 0; $i < count($discList); $i++){
            $discipline = $discList[$i];
            $disciplines[] = $discipline;

            $stats = Lesson::GetMonthStatByTfdId($discipline->tfd_id);

            $discipline->schedule_hours = $stats["count"]*2;
            unset($stats["count"]);

            $list = array_keys($stats);

            $monthList = array_merge($monthList, $list);

            $discipline->stat_by_month = $stats;
        }

        $monthList = array_unique($monthList);
        $monthList = array_values($monthList);
        sort($monthList);

        $result = array();
        $result["disciplines"] = $disciplines;
        $result["month_list"] = $monthList;

        return $result;
    }

    public static function ListFromTeacherId($teacherId)
    {
        $discIds = DB::table('discipline_teacher')
            ->where('teacher_id', '=', $teacherId)
            ->select('discipline_id')
            ->get()
            ->map(function($item) { return $item->discipline_id;});


        return Discipline::ListFormIdsWithGroupNames($discIds);
    }

    public static function DisciplineListFromGroupIdWithStat($groupId)
    {
        $groupIds = StudentGroup::GetGroupsOfStudentFromGroup($groupId);

        $discList = DB::table('discipline_teacher')
            ->whereIn('disciplines.student_group_id', $groupIds)
            ->join('disciplines', 'discipline_teacher.discipline_id', '=', 'disciplines.id')
            ->join('student_groups', 'disciplines.student_group_id', '=', 'student_groups.id')
            ->join('teachers', 'discipline_teacher.teacher_id', '=', 'teachers.id')
            ->select('disciplines.name', 'disciplines.auditorium_hours',
                'student_groups.name as student_group_name',
                'discipline_teacher.id as tfd_id',
                'disciplines.attestation')
            ->orderBy('disciplines.name')
            ->get()
            ->toArray();

        for($i = 0; $i < count($discList); $i++){
            $discList[$i]->schedule_hours = Lesson::GetScheduleHoursByTfdId($discList[$i]->tfd_id);
            unset($discList[$i]->tfd_id);
        }

        return $discList;
    }

    public function discipline() {
        return $this->belongsTo(Discipline::class);
    }

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }
}
