<?php

namespace App\DomainClasses;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Lesson extends Model
{
    public static function GetDailyTFDLessons($disciplineTeacherIds, $calendarId)
    {
        return DB::table('lessons')
            ->where('calendar_id', '=', $calendarId)
            ->where('state', '=', 1)
            ->whereIn('discipline_teacher_id', $disciplineTeacherIds)
            ->join('discipline_teacher as tfd', 'discipline_teacher_id', '=', 'tfd.id')
            ->join('disciplines', 'tfd.discipline_id', '=', 'disciplines.id')
            ->join('teachers', 'teacher_id', '=', 'teachers.id')
            ->join('student_groups', 'student_group_id', '=', 'student_groups.id')
            ->join('rings', 'ring_id', '=', 'rings.id')
            ->join('auditoriums', 'auditorium_id', '=', 'auditoriums.id')
            ->select('lessons.id', 'rings.time', 'disciplines.name as disc_name',
                'teachers.fio', 'auditoriums.name as aud_name',
                'student_groups.name as group_name')
            ->orderBy('rings.time')
            ->get();
    }

    public function discipline_teacher()
    {
        return $this->belongsTo(Discipline_Teacher::class);
    }

    public function calendar()
    {
        return $this->belongsTo(Calendar::class);
    }

    public function ring()
    {
        return $this->belongsTo(Ring::class);
    }

    public function auditorium()
    {
        return $this->belongsTo(Auditorium::class);
    }

    public function schedule_notes()
    {
        return $this->hasMany(ScheduleNote::class);
    }

    public function Event()
    {
        return $this->belongsTo(LessonLogEvent::class);
    }
}
