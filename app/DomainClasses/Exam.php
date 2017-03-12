<?php

namespace App\DomainClasses;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Exam extends Model
{
    public static function FromGroupId($groupId)
    {
        $groupDisciplineIds = Discipline::IdsFromGroupId($groupId);

        $exams =  DB::table('exams')
            ->where('is_active', true)
            ->whereIn('discipline_id', $groupDisciplineIds)
            ->join('disciplines', 'discipline_id', '=', 'disciplines.id')
            ->join('student_groups', 'disciplines.student_group_id', '=', 'student_groups.id')
            ->leftJoin('auditoriums as aud1', 'consultation_auditorium_id', '=', 'aud1.id')
            ->leftJoin('auditoriums as aud2', 'exam_auditorium_id', '=', 'aud2.id')
            ->select('exams.id', 'discipline_id',
                'exams.consultation_datetime', 'exams.exam_datetime',
                'disciplines.name as disc_name', 'student_groups.name as group_name',
                'aud1.name as cons_aud', 'aud2.name as exam_aud')
            ->get();

        $exams->map(function ($exam) {
            $exam->teacher_fio = Discipline_Teacher::TeacherFioFromDisciplineId($exam->discipline_id);
        });

        return $exams;
    }

    public static function FromGroupId_OldAPI($groupId)
    {
        $groupDisciplineIds = Discipline::IdsFromGroupId($groupId);

        $exams =  DB::table('exams')
            ->where('is_active', true)
            ->whereIn('discipline_id', $groupDisciplineIds)
            ->join('disciplines', 'discipline_id', '=', 'disciplines.id')
            ->join('student_groups', 'disciplines.student_group_id', '=', 'student_groups.id')
            ->leftJoin('auditoriums as aud1', 'consultation_auditorium_id', '=', 'aud1.id')
            ->leftJoin('auditoriums as aud2', 'exam_auditorium_id', '=', 'aud2.id')
            ->select('exams.id', 'discipline_id',
                'exams.consultation_datetime', 'exams.exam_datetime',
                'disciplines.name as disc_name', 'student_groups.name as group_name',
                'aud1.name as cons_aud', 'aud2.name as exam_aud',
                'aud1.id as cons_aud_id', 'aud2.id as exam_aud_id')
            ->get();

        $exams->map(function ($exam) {
            $exam->teacher_fio = Discipline_Teacher::TeacherFioFromDisciplineId($exam->discipline_id);
        });

        return $exams;
    }

    public function discipline()
    {
        return $this->belongsTo(Discipline::class);
    }

    public function consultation_auditorium()
    {
        return $this->belongsTo(Auditorium::class);
    }

    public function exam_auditorium()
    {
        return $this->belongsTo(Auditorium::class);
    }
}
