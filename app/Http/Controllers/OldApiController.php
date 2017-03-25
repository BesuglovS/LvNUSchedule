<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\DomainClasses\Auditorium;
use App\DomainClasses\Building;
use App\DomainClasses\Calendar;
use App\DomainClasses\ConfigOption;
use App\DomainClasses\Discipline;
use App\DomainClasses\Discipline_Teacher;
use App\DomainClasses\Exam;
use App\DomainClasses\Faculty;
use App\DomainClasses\Faculty_Student_Group;
use App\DomainClasses\Lesson;
use App\DomainClasses\LessonLogEvent;
use App\DomainClasses\Ring;
use App\DomainClasses\Student;
use App\DomainClasses\Student_Student_Group;
use App\DomainClasses\StudentGroup;
use App\DomainClasses\Teacher;


class OldApiController extends Controller
{
    public function index() {
        return view('api.index');
    }

    public function api(Request $request) {
        ini_set('max_execution_time', 60);

        $input = $request->all();

        $action = $input["action"];

        if (!(($action == "list") ||
            ($action == "groupsBundle") ||
            ($action == "bundle") ||
            ($action == "update") ||
            ($action == "dailySchedule") ||
            ($action == "groupExams") ||
            ($action == "weekSchedule") ||
            ($action == "groupSchedule") ||
            ($action == "TeacherWeekSchedule") ||
            ($action == "TeacherSchedule")
        ))
        {
            return array("error" => "Неизвестное действие (action)");
        }

        switch ($action) {
            case "update":
            case "bundle":
                $bundle = array();
                $bundle["auditoriums"] =  $this->GetAuditoriumsList();
                $bundle["calendars"] = $this->GetCalendarsList();
                $bundle["disciplines"] = $this->GetDisciplinesList();
                $bundle["lessons"] = $this->GetLessonsList();
                $bundle["rings"] = $this->GetRingsList();
                $bundle["students"] = $this->GetStudentsList();
                $bundle["studentGroups"] = $this->GetStudentGroupsList();
                $bundle["studentsInGroups"] = $this->GetStudentInGroupsList();
                $bundle["teachers"] = $this->GetTechersList();
                $bundle["teacherForDisciplines"] = $this->GetTFDList();

                $bundle["configOptions"] = $this->GetConfigOptionsList();
                $bundle["lessonLogEvents"] = $this->GetLogEvents();

                $bundle["faculties"] = $this->GetFacultiesList();
                $bundle["groupsInFaculties"] = $this->GetGroupInFaculties();

                return $bundle;
                break;
            case "groupsBundle":
                $bundle = array();
                $bundle["studentGroups"] = $this->GetStudentGroupsList();
                $bundle["faculties"] = $this->GetFacultiesList();
                $bundle["groupsInFaculties"] = $this->GetGroupInFaculties();

                return $bundle;
                break;
            case "list":
                if(!isset($input['listtype']))
                {
                    return array("error" => "listtype - обязательный параметр при list запросе.");
                }
                else
                {
                    $listType = $input["listtype"];
                }

                switch ($listType) {
                    case "auditoriums":             return $this->GetAuditoriumsList();
                    case "calendars":               return $this->GetCalendarsList();
                    case "configOptions":           return $this->GetConfigOptionsList();
                    case "disciplines":             return $this->GetDisciplinesList();
                    case "lessons":                 return $this->GetLessonsList();
                    case "rings":                   return $this->GetRingsList();
                    case "students":                return $this->GetStudentsList();
                    case "studentGroups":           return $this->GetStudentGroupsList();
                    case "mainStudentGroups":       return $this->GetMainStudentGroupsList();
                    case "studentsInGroups":        return $this->GetStudentInGroupsList();
                    case "teachers":                return $this->GetTechersList();
                    case "teacherForDisciplines":   return $this->GetTFDList();
                    case "lessonLogEvents":         return $this->GetLogEvents();
                    case "faculties":               return $this->GetFacultiesList();
                    case "buildings":               return $this->GetBuildingsList();
                    case "groupsInFaculties":       return $this->GetGroupInFaculties();
                }
                break;
            case "dailySchedule":           return $this->DailySchedule($input);
            case "weekSchedule":            return $this->WeekSchedule($input);
            case "groupExams":              return $this->GroupExams($input);
            case "TeacherWeekSchedule":     return $this->TeacherWeekSchedule($input);
        }

        return array("error" => "Whoops, looks like something went wrong :-)");
    }

    private function DailySchedule($input) {
        if ((!isset($input['groupIds'])) || (!isset($input['date'])))
        {
            return array("error" => "groupId и date (yyyy-mm-dd) обязательные параметры");
        }

        $groupId = $input["groupIds"];
        $date = $input["date"];

        $groupDisciplineIds = Discipline::IdsFromGroupId($groupId);
        $disciplineTeacherIds = Discipline_Teacher::IdsFromDisciplineIds($groupDisciplineIds);
        $calendarId = Calendar::IdfromDate($date);

        $lessonsList =  Lesson::GetDailyTFDLessons($disciplineTeacherIds, $calendarId);

        $lessonsList->map(function ($lesson) use ($date) {
            $dt = $date . " " . $lesson->time;
            $now = Carbon::now();
            $lessonStart = Carbon::createFromFormat('Y-m-d H:i', $dt);
            $diff = $now->diffInMinutes($lessonStart);
            $onGoing = (($diff >= 0) && ($diff < 80)) ? 1 : 0;

            $lesson->onGoing = $onGoing;

            $lesson->Time = substr($lesson->time, 0, 5);
            unset($lesson->time);

            $lesson->discName = $lesson->disc_name;
            unset($lesson->disc_name);

            $lesson->FIO = $lesson->fio;
            unset($lesson->fio);

            $lesson->audName = $lesson->aud_name;
            unset($lesson->aud_name);

            $lesson->groupName = $lesson->group_name;
            unset($lesson->group_name);

            unset($lesson->id);
        });

        $resultItem = array();
        $resultItem["studentGroupName"] = StudentGroup::NameFromId($groupId);
        $resultItem["studentGroupId"] = $groupId;
        $resultItem["Lessons"] = $lessonsList;

        $result = array($resultItem);

        return $result;
    }

    private function WeekSchedule($input) {
        if ((!isset($input['groupId'])) || (!isset($input['week'])))
        {
            return array("error" => "groupId и week обязательные параметры");
        }

        $groupId = $input["groupId"];
        $week = $input["week"];

        $groupDisciplineIds = Discipline::IdsFromGroupId($groupId);
        $disciplineTeacherIds = Discipline_Teacher::IdsFromDisciplineIds($groupDisciplineIds);
        $lessons = Lesson::GetWeekTFDLessons($disciplineTeacherIds, $week);

        $lessons->map(function ($lesson) {
            $dt = Carbon::createFromFormat('Y-m-d', $lesson->date);
            $dow = $dt->dayOfWeek;
            $dow = ($dow == 0) ? 7 : $dow;

            $lesson->Time = $lesson->time;
            unset($lesson->time);

            $lesson->discName = $lesson->disc_name;
            unset($lesson->disc_name);

            $lesson->FIO = $lesson->fio;
            unset($lesson->fio);

            $lesson->audName = $lesson->aud_name;
            unset($lesson->aud_name);

            $lesson->groupName = $lesson->group_name;
            unset($lesson->group_name);

            unset($lesson->id);

            $lesson->dow = (string) $dow;
        });

        return $lessons;
    }

    private function GroupExams($input) {
        if (!isset($input['groupId']))
        {
            return array("error" => "groupId обязательный параметр");
        }

        $groupId = $input["groupId"];

        $Exams = Exam::FromGroupId_OldAPI($groupId);

        $Exams->map(function ($exam) {
            $default_datetime = "01.01.2020 0:00";

            $exam->DisciplineId = $exam->discipline_id;
            unset($exam->discipline_id);

            $exam->ConsultationDateTime =
                ($exam->consultation_datetime == $default_datetime) ? "" :
                $exam->consultation_datetime;
            unset($exam->consultation_datetime);

            $exam->ConsultationAuditoriumId = ($exam->cons_aud_id == null) ? "" : $exam->cons_aud_id;
            unset($exam->cons_aud_id);

            $exam->ExamDateTime =
                ($exam->exam_datetime == $default_datetime) ? "" :
                    $exam->exam_datetime;
            unset($exam->exam_datetime);

            $exam->ExamAuditoriumId = ($exam->exam_aud_id == null) ? "" : $exam->exam_aud_id;
            unset($exam->exam_aud_id);

            $exam->ConsultationAuditoriumName = ($exam->cons_aud == null) ? "" : $exam->cons_aud;
            unset($exam->cons_aud);

            $exam->ExamAuditoriumName = ($exam->exam_aud == null) ? "" : $exam->exam_aud;
            unset($exam->exam_aud);

            $exam->DisciplineName = $exam->disc_name;
            unset($exam->disc_name);

            $exam->TeacherFIO = $exam->teacher_fio;
            unset($exam->teacher_fio);

            $exam->StudentGroupName = $exam->group_name;
            unset($exam->group_name);

            unset($exam->id);
        });

        $result = array();
        $result[$groupId] = array();
        $result[$groupId]["Exams"] = $Exams;
        $result[$groupId]["groupId"] = (string) $groupId;
        $result[$groupId]["groupName"] = StudentGroup::NameFromId($groupId);

        return $result;
    }

    private function TeacherWeekSchedule($input) {
        if ((!isset($input['teacherId'])) || (!isset($input['week'])))
        {
            return array("error" => "teacherId и week обязательные параметры");
        }

        $teacherId = $input["teacherId"];
        $week = $input["week"];

        $tfdIds = Discipline_Teacher::IdsFromTeacherId($teacherId);
        $lessons = Lesson::GetWeekTFDLessons_OldAPI($tfdIds, $week);

        $lessons->map(function ($lesson) {
            $dt = Carbon::createFromFormat('Y-m-d', $lesson->date);
            $dow = $dt->dayOfWeek;
            $dow = ($dow == 0) ? 7 : $dow;

            $lesson->Date = $lesson->date;
            unset($lesson->date);

            $lesson->Time = $lesson->time;
            unset($lesson->time);

            $lesson->groupName = $lesson->group_name;
            unset($lesson->group_name);

            $lesson->disciplineName = $lesson->disc_name;
            unset($lesson->disc_name);

            $lesson->auditoriumName = $lesson->aud_name;
            unset($lesson->aud_name);

            $lesson->TeacherForDisciplineId = (string) $lesson->tfd_id;
            unset($lesson->tfd_id);

            unset($lesson->id);
            unset($lesson->fio);

            $lesson->dow = $dow;
        });

        return $lessons;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    private function GetAuditoriumsList()
    {
        $result = Auditorium::all();

        $result->map(function ($aud) {
            $aud->AuditoriumId = (string) $aud->id;
            unset($aud->id);

            $aud->Name = $aud->name;
            unset($aud->name);

            unset($aud->building_id);
        });

        return $result;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    private function GetCalendarsList()
    {
        $result = Calendar::all();

        $result->map(function ($cal) {
            $cal->CalendarId = (string) $cal->id;
            unset($cal->id);

            $cal->Date = $cal->date;
            unset($cal->date);

            unset($cal->state);
        });

        return $result;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    private function GetConfigOptionsList()
    {
        $result =  ConfigOption::all();

        $result->map(function ($co) {
            $co->ConfigOptionId = (string) $co->id;
            unset($co->id);

            $co->Key = $co->key;
            unset($co->key);

            $co->Value = $co->value;
            unset($co->value);
        });

        return $result;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    private function GetDisciplinesList()
    {
        $result = Discipline::all();

        $result->map(function ($disc) {
            $disc->DisciplineId = (string) $disc->id;
            unset($disc->id);

            $disc->Name = $disc->name;
            unset($disc->name);

            $disc->Attestation = (string) $disc->attestation;
            unset($disc->attestation);

            $disc->AuditoriumHours = (string) $disc->auditorium_hours;
            unset($disc->auditorium_hours);

            $disc->LectureHours = (string) $disc->lecture_hours;
            unset($disc->lecture_hours);

            $disc->PracticalHours = (string) $disc->practical_hours;
            unset($disc->practical_hours);

            $disc->StudentGroupId = (string) $disc->student_group_id;
            unset($disc->student_group_id);

            unset($disc->auditorium_hours_per_week);
        });

        return $result;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    private function GetLessonsList()
    {
        $result =  Lesson::all();

        $result->map(function ($l) {
            $l->LessonId = (string) $l->id;
            unset($l->id);

            $l->IsActive = (string) $l->state;
            unset($l->state);

            $l->TeacherForDisciplineId = (string) $l->discipline_teacher_id;
            unset($l->discipline_teacher_id);

            $l->CalendarId = (string) $l->calendar_id;
            unset($l->calendar_id);

            $l->RingId = (string) $l->ring_id;
            unset($l->ring_id);

            $l->AuditoriumId = (string) $l->auditorium_id;
            unset($l->auditorium_id);
        });

        return $result;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    private function GetRingsList()
    {
        $result =  Ring::all();

        $result->map(function ($ring) {
            $ring->RingId = (string) $ring->id;
            unset($ring->id);

            $ring->Time = $ring->time;
            unset($ring->time);
        });

        return $result;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    private function GetStudentsList()
    {
        $result =  Student::ListOfNotExpelled();

        $result->map(function ($st) {
            $st->StudentId = (string) $st->id;
            unset($st->id);

            $st->F = $st->f;
            unset($st->f);

            $st->I = $st->i;
            unset($st->i);

            $st->O = $st->o;
            unset($st->o);

            $st->Starosta = (string) $st->starosta;
            unset($st->starosta);

            $st->NFactor = (string) $st->n_factor;
            unset($st->n_factor);

            $st->Expelled = (string) $st->expelled;
            unset($st->expelled);

            unset($st->zach_number);
            unset($st->birth_date);
            unset($st->address);
            unset($st->phone);
            unset($st->orders);
            unset($st->paid_edu);
        });

        return $result;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    private function GetStudentGroupsList()
    {
        $result = StudentGroup::all();

        $result->map(function ($sg) {
            $sg->StudentGroupId = (string) $sg->id;
            unset($sg->id);

            $sg->Name = $sg->name;
            unset($sg->name);
        });

        return $result;
    }

    /**
     * @return array
     */
    private function GetMainStudentGroupsList()
    {
        $result = StudentGroup::mainStudentGroups();

        array_walk($result,
            function ($sg) {
                $sg->StudentGroupId = (string) $sg->id;
                unset($sg->id);

                $sg->Name = $sg->name;
                unset($sg->name);
        });

        return $result;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    private function GetStudentInGroupsList()
    {
        $result = Student_Student_Group::all();

        $result->map(function ($sig) {
            $sig->StudentsInGroupsId = (string) $sig->id;
            unset($sig->id);

            $sig->StudentId = (string) $sig->student_id;
            unset($sig->student_id);

            $sig->StudentGroupId = (string) $sig->student_group_id;
            unset($sig->student_group_id);
        });

        return $result;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    private function GetTechersList()
    {
        $result = Teacher::OrderByFio();

        $result->map(function ($t) {
            $t->TeacherId = (string) $t->id;
            unset($t->id);

            $t->FIO = (string) $t->fio;
            unset($t->fio);

            unset($t->phone);
        });

        return $result;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    private function GetTFDList()
    {
        $result = Discipline_Teacher::all();

        $result->map(function ($tfd) {
            $tfd->TeacherForDisciplineId = (string) $tfd->id;
            unset($tfd->id);

            $tfd->TeacherId = (string) $tfd->teacher_id;
            unset($tfd->teacher_id);

            $tfd->DisciplineId = (string) $tfd->discipline_id;
            unset($tfd->discipline_id);
        });

        return $result;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    private function GetLogEvents()
    {
        $result = LessonLogEvent::all();

        $result->map(function ($lle) {
            $lle->LessonLogEventId = (string) $lle->id;
            unset($lle->id);

            $lle->OldLessonId = (string) $lle->old_lesson_id;
            unset($lle->old_lesson_id);

            $lle->NewLessonId = (string) $lle->new_lesson_id;
            unset($lle->new_lesson_id);

            $lle->DateTime = (string) $lle->date_time;
            unset($lle->date_time);

            $lle->Comment = $lle->public_comment;
            unset($lle->public_comment);

            unset($lle->hidden_comment);
        });

        return $result;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    private function GetFacultiesList()
    {
        $result = Faculty::all();

        $result->map(function ($f) {
            $f->FacultyOd = (string) $f->id;
            unset($f->id);

            $f->Name = $f->name;
            unset($f->name);

            $f->Letter = $f->letter;
            unset($f->letter);

            $f->SortingOrder = (string) $f->sorting_order;
            unset($f->sorting_order);

            unset($f->schedule_signing_title);
            unset($f->dean_signing_schedule);
            unset($f->session_signing_title);
            unset($f->dean_signing_session_schedule);

        });

        return $result;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    private function GetBuildingsList()
    {
        $result = Building::all();

        $result->map(function ($b) {
            $b->BuildingId = (string) $b->id;
            unset($b->id);

            $b->Name = $b->name;
            unset($b->name);
        });

        return $result;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    private function GetGroupInFaculties()
    {
        $result = Faculty_Student_Group::all();

        $result->map(function ($gif) {
            $gif->GroupsInFacultyId = (string) $gif->id;
            unset($gif->id);

            $gif->FacultyId = (string) $gif->faculty_id;
            unset($gif->faculty_id);

            $gif->StudentGroupId = (string) $gif->student_group_id;
            unset($gif->student_group_id);
        });

        return $result;
    }
}
