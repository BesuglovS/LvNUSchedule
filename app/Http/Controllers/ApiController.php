<?php

namespace App\Http\Controllers;

use App\DomainClasses\Auditorium;
use App\DomainClasses\AuditoriumEvent;
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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public function index() {
        return view('api.index');
    }

    public function import(Request $request) {
        ini_set('max_execution_time', 300);

        $content = $request->getContent();
        $requestData = json_decode($content, true);

        $dbPrefix = $requestData['dbPrefix'];
        $tableSelector = $requestData['tableSelector'];
        $append = $requestData['append'];
        $data = json_decode($requestData['data'], true);

        switch ($tableSelector) {
            case "auditoriums":
                if ($append !== "1") {
                    $query = "DROP TABLE IF EXISTS " . $dbPrefix . "auditoriums";
                    DB::statement($query);
                }

                $query  = "CREATE TABLE IF NOT EXISTS " . $dbPrefix . "auditoriums ( " .
                    "`id` int(11) NOT NULL AUTO_INCREMENT, " .
                    "`name` varchar(100) NOT NULL, " .
                    "`building_id` INT NOT NULL, " .
                    "PRIMARY KEY  (`id`)" .
                    ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";

                DB::statement($query);

                $pdo = DB::getPdo();
                $pdo->beginTransaction();
                $statement = $pdo->prepare("INSERT INTO " . $dbPrefix . "auditoriums(id, name, building_id) VALUES ( ? , ? , ? )");

                foreach ($data as $auditorium) {
                    $statement->execute(array($auditorium["AuditoriumId"], $auditorium["Name"], $auditorium["BuildingId"]));
                }

                $pdo->commit();
                break;
            case "buildings":
                if ($append !== "1") {
                    $query = "DROP TABLE IF EXISTS " . $dbPrefix . "buildings";
                    DB::statement($query);
                }

                $query  = "CREATE TABLE IF NOT EXISTS " . $dbPrefix . "buildings ( " .
                    "`id` int(11) NOT NULL AUTO_INCREMENT, " .
                    "`name` varchar(100) NOT NULL, " .
                    "PRIMARY KEY  (`id`)" .
                    ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";

                DB::statement($query);

                $pdo = DB::getPdo();
                $pdo->beginTransaction();
                $statement = $pdo->prepare("INSERT INTO " . $dbPrefix . "buildings(id, name) VALUES ( ? , ? )");

                foreach ($data as $building) {
                    $statement->execute(array($building["BuildingId"], $building["Name"]));
                }

                $pdo->commit();
                break;
            case "calendars":
                if ($append !== "1") {
                    $query = "DROP TABLE IF EXISTS " . $dbPrefix . "calendars";
                    DB::statement($query);
                }

                $query  = "CREATE TABLE IF NOT EXISTS " . $dbPrefix . "calendars ( " .
                    "`id` int(11) NOT NULL AUTO_INCREMENT, " .
                    "`date` date NOT NULL, " .
                    "`state` int(11) NOT NULL, " .
                    "PRIMARY KEY  (`id`)" .
                    ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";

                DB::statement($query);

                $pdo = DB::getPdo();
                $pdo->beginTransaction();
                $statement = $pdo->prepare("INSERT INTO " . $dbPrefix . "calendars(id, date, state) VALUES ( ? , ? , ? )");

                foreach ($data as $calendar) {
                    $statement->execute(array($calendar["CalendarId"], $calendar["Date"], $calendar["State"]));
                }

                $pdo->commit();
                break;
            case "rings":
                if ($append !== "1") {
                    $query = "DROP TABLE IF EXISTS " . $dbPrefix . "rings";
                    DB::statement($query);
                }

                $query  = "CREATE TABLE IF NOT EXISTS " . $dbPrefix . "rings ( " .
                    "`id` int(11) NOT NULL AUTO_INCREMENT, " .
                    "`time` TIME NOT NULL, " .
                    "PRIMARY KEY  (`id`)" .
                    ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";

                DB::statement($query);

                $pdo = DB::getPdo();
                $pdo->beginTransaction();
                $statement = $pdo->prepare("INSERT INTO " . $dbPrefix . "rings(id, `time`) VALUES ( ? , ? )");

                foreach ($data as $ring) {
                    $statement->execute(array($ring["RingId"], $ring["Time"]));
                }

                $pdo->commit();
                break;
            case "students":
                if ($append !== "1") {
                    $query = "DROP TABLE IF EXISTS " . $dbPrefix . "students";
                    DB::statement($query);
                }

                $query  = "CREATE TABLE IF NOT EXISTS " . $dbPrefix . "students ( " .
                    "`id` int(11) NOT NULL AUTO_INCREMENT, " .
                    "`f` varchar(100) NOT NULL, " .
                    "`i` varchar(100) NOT NULL, " .
                    "`o` varchar(100) NOT NULL, " .
                    "`zach_number` varchar(10) NOT NULL, " .
                    "`birth_date` Date NOT NULL, " .
                    "`address` varchar(300) NOT NULL, " .
                    "`phone` varchar(300) NOT NULL, " .
                    "`orders` varchar(300) NOT NULL, " .
                    "`starosta` BOOLEAN NOT NULL, " .
                    "`n_factor` BOOLEAN NOT NULL, " .
                    "`paid_edu` BOOLEAN NOT NULL, " .
                    "`expelled` BOOLEAN NOT NULL, " .
                    "PRIMARY KEY  (`id`)" .
                    ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";

                DB::statement($query);

                $pdo = DB::getPdo();
                $pdo->beginTransaction();
                $statement = $pdo->prepare("INSERT INTO " . $dbPrefix . "students(id, f, i, o, `zach_number`, `birth_date`, `address`, `phone`, `orders`, `starosta`, `n_factor`, `paid_edu`, `expelled` ) VALUES ( ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? )");

                foreach ($data as $student) {
                    $statement->execute(array($student["StudentId"], $student["F"], $student["I"], $student["O"],
                        $student["ZachNumber"], $student["BirthDate"], $student["Address"], $student["Phone"],
                        $student["Orders"], $student["Starosta"], $student["NFactor"], $student["PaidEdu"],
                        $student["Expelled"]));
                }

                $pdo->commit();
                break;
            case "studentGroups":
                if ($append !== "1") {
                    $query = "DROP TABLE IF EXISTS " . $dbPrefix . "student_groups";
                    DB::statement($query);
                }

                $query  = "CREATE TABLE IF NOT EXISTS " . $dbPrefix . "student_groups ( " .
                    "`id` int(11) NOT NULL AUTO_INCREMENT, " .
                    "`name` varchar(100) NOT NULL, " .
                    "PRIMARY KEY  (`id`)" .
                    ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";

                DB::statement($query);

                $pdo = DB::getPdo();
                $pdo->beginTransaction();
                $statement = $pdo->prepare("INSERT INTO " . $dbPrefix . "student_groups(id, name) VALUES ( ? , ? )");

                foreach ($data as $studentGroup) {
                    $statement->execute(array($studentGroup["StudentGroupId"], $studentGroup["Name"]));
                }

                $pdo->commit();
                break;
            case "teachers":
                if ($append !== "1") {
                    $query = "DROP TABLE IF EXISTS " . $dbPrefix . "teachers";
                    DB::statement($query);
                }

                $query  = "CREATE TABLE IF NOT EXISTS " . $dbPrefix . "teachers ( " .
                    "`id` int(11) NOT NULL AUTO_INCREMENT, " .
                    "`fio` varchar(300) NOT NULL, " .
                    "`phone` varchar(150) NOT NULL, " .
                    "PRIMARY KEY  (`id`)" .
                    ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";

                DB::statement($query);

                $pdo = DB::getPdo();
                $pdo->beginTransaction();
                $statement = $pdo->prepare("INSERT INTO " . $dbPrefix . "teachers(id, fio, phone) VALUES ( ? , ? , ? )");

                foreach ($data as $teacher) {
                    $statement->execute(array($teacher["TeacherId"], $teacher["FIO"], $teacher["Phone"]));
                }

                $pdo->commit();
                break;
            case "disciplines":
                if ($append !== "1") {
                    $query = "DROP TABLE IF EXISTS " . $dbPrefix . "disciplines";
                    DB::statement($query);
                }

                $query  = "CREATE TABLE IF NOT EXISTS " . $dbPrefix . "disciplines ( " .
                    "id int(11) NOT NULL AUTO_INCREMENT, " .
                    "`name` varchar(200) NOT NULL, " .
                    "`attestation` TINYINT NOT NULL, " .
                    "`auditorium_hours` SMALLINT NOT NULL, " .
                    "`auditorium_hours_per_week` SMALLINT NOT NULL, " .
                    "`lecture_hours` SMALLINT NOT NULL, " .
                    "`practical_hours` SMALLINT NOT NULL, " .
                    "`student_group_id` INT NOT NULL, " .
                    "PRIMARY KEY  (`id`)" .
                    ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";

                DB::statement($query);

                $pdo = DB::getPdo();
                $pdo->beginTransaction();
                $statement = $pdo->prepare("INSERT INTO " . $dbPrefix . "disciplines (id, name, attestation, `auditorium_hours`, `auditorium_hours_per_week`, `lecture_hours`, `practical_hours`, `student_group_id`) VALUES ( ? , ? , ? , ? , ? , ? , ? , ? )");

                foreach ($data as $discipline) {
                    $statement->execute(array(
                        $discipline["DisciplineId"], $discipline["Name"],
                        $discipline["Attestation"], $discipline["AuditoriumHours"],
                        $discipline["AuditoriumHoursPerWeek"], $discipline["LectureHours"],
                        $discipline["PracticalHours"], $discipline["StudentGroupId"]));
                }

                $pdo->commit();
                break;
            case "studentsInGroups":
                if ($append !== "1") {
                    $query = "DROP TABLE IF EXISTS " . $dbPrefix . "student_student_group";
                    DB::statement($query);
                }

                $query  = "CREATE TABLE IF NOT EXISTS " . $dbPrefix . "student_student_group ( " .
                    "`id` INT NOT NULL AUTO_INCREMENT, " .
                    "`student_id` INT NOT NULL, " .
                    "`student_group_id` INT NOT NULL, " .
                    "PRIMARY KEY  (`id`)" .
                    ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";

                DB::statement($query);

                $pdo = DB::getPdo();
                $pdo->beginTransaction();
                $statement = $pdo->prepare("INSERT INTO " . $dbPrefix . "student_student_group (id, student_id, student_group_id) VALUES ( ? , ? , ? )");

                foreach ($data as $sig) {
                    $statement->execute(array($sig["StudentsInGroupsId"], $sig["StudentId"], $sig["StudentGroupId"]));
                }

                $pdo->commit();
                break;
            case "teacherForDisciplines":
                if ($append !== "1") {
                    $query = "DROP TABLE IF EXISTS " . $dbPrefix . "discipline_teacher";
                    DB::statement($query);
                }

                $query  = "CREATE TABLE IF NOT EXISTS " . $dbPrefix . "discipline_teacher ( " .
                    "`id` INT NOT NULL AUTO_INCREMENT, " .
                    "`teacher_id` INT NOT NULL, " .
                    "`discipline_id` INT NOT NULL, " .
                    "PRIMARY KEY  (`id`)" .
                    ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";

                DB::statement($query);

                $pdo = DB::getPdo();
                $pdo->beginTransaction();
                $statement = $pdo->prepare("INSERT INTO " . $dbPrefix . "discipline_teacher (id, teacher_id, discipline_id) VALUES ( ? , ? , ? )");

                foreach ($data as $tfd) {
                    $statement->execute(array($tfd["TeacherForDisciplineId"], $tfd["TeacherId"], $tfd["DisciplineId"]));
                }

                $pdo->commit();
                break;
            case "lessons":
                if ($append !== "1") {
                    $query = "DROP TABLE IF EXISTS " . $dbPrefix . "lessons";
                    DB::statement($query);
                }

                $query  = "CREATE TABLE IF NOT EXISTS " . $dbPrefix . "lessons ( " .
                    "`id` INT NOT NULL AUTO_INCREMENT, " .
                    "`state` SMALLINT NOT NULL, " .
                    "`discipline_teacher_id` INT NOT NULL, " .
                    "`calendar_id` INT NOT NULL, " .
                    "`ring_id` INT NOT NULL, " .
                    "`auditorium_id` INT NOT NULL, " .
                    "PRIMARY KEY  (`id`)" .
                    ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";

                DB::statement($query);

                $pdo = DB::getPdo();
                $pdo->beginTransaction();
                $statement = $pdo->prepare("INSERT INTO " . $dbPrefix . "lessons (id, state, discipline_teacher_id, `calendar_id`, `Ring_Id`, `Auditorium_Id`) VALUES ( ? , ? , ? , ? , ? , ? )");

                foreach ($data as $lesson) {
                    $statement->execute(array($lesson["LessonId"], $lesson["IsActive"],
                        $lesson["TeacherForDisciplineId"], $lesson["CalendarId"], $lesson["RingId"], $lesson["AuditoriumId"]));
                }

                $pdo->commit();
                break;
            case "configs":
                if ($append !== "1") {
                    $query = "DROP TABLE IF EXISTS " . $dbPrefix . "config_options";
                    DB::statement($query);
                }

                $query  = "CREATE TABLE IF NOT EXISTS " . $dbPrefix . "config_options ( " .
                    "`id` int(11) NOT NULL AUTO_INCREMENT, " .
                    "`key` varchar(100) NOT NULL, " .
                    "`value` varchar(300) NOT NULL, " .
                    "PRIMARY KEY  (`id`)" .
                    ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";

                DB::statement($query);

                $pdo = DB::getPdo();
                $pdo->beginTransaction();
                $statement = $pdo->prepare("INSERT INTO " . $dbPrefix . "config_options (id, `key`, `value`) VALUES ( ? , ? , ? )");

                foreach ($data as $configOption) {
                    $statement->execute(array($configOption["ConfigOptionId"], $configOption["Key"], $configOption["Value"]));
                }

                $pdo->commit();
                break;
            case "lessonLogEvents":
                if ($append !== "1") {
                    $query = "DROP TABLE IF EXISTS " . $dbPrefix . "lesson_log_events";
                    DB::statement($query);
                }

                $query  = "CREATE TABLE IF NOT EXISTS " . $dbPrefix . "lesson_log_events ( " .
                    "`id` int(11) NOT NULL AUTO_INCREMENT, " .
                    "`old_lesson_id` INT NOT NULL, " .
                    "`new_lesson_id` INT NOT NULL, " .
                    "`date_time` DATETIME NOT NULL, " .
                    "`public_comment` varchar(300) NOT NULL, " .
                    "`hidden_comment` varchar(300) NOT NULL, " .
                    "PRIMARY KEY  (`id`)" .
                    ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";

                DB::statement($query);

                $pdo = DB::getPdo();
                $pdo->beginTransaction();
                $statement = $pdo->prepare("INSERT INTO " . $dbPrefix . "lesson_log_events (id, old_lesson_id, new_lesson_id, date_time, public_comment, hidden_comment) VALUES ( ? , ? , ? , ? , ? , ? )");

                foreach ($data as $lessonLogEvent) {
                    $statement->execute(array($lessonLogEvent["LessonLogEventId"],
                        $lessonLogEvent["OldLessonId"], $lessonLogEvent["NewLessonId"], $lessonLogEvent["DateTime"],
                        $lessonLogEvent["PublicComment"], $lessonLogEvent["HiddenComment"]));
                }

                $pdo->commit();
                break;
            case "auditoriumEvents":
                if ($append !== "1") {
                    $query = "DROP TABLE IF EXISTS " . $dbPrefix . "auditorium_events";
                    DB::statement($query);
                }

                $query  = "CREATE TABLE IF NOT EXISTS " . $dbPrefix . "auditorium_events ( " .
                    "`id` INT NOT NULL AUTO_INCREMENT, " .
                    "`name` varchar(100) NOT NULL, " .
                    "`calendar_id` INT NOT NULL, " .
                    "`ring_id` INT NOT NULL, " .
                    "`auditorium_id` INT NOT NULL, " .
                    "PRIMARY KEY  (`id`)" .
                    ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";

                DB::statement($query);

                $pdo = DB::getPdo();
                $pdo->beginTransaction();
                $statement = $pdo->prepare("INSERT INTO " . $dbPrefix . "auditorium_events (`id`, `name`, `calendar_id`, `ring_id`, `auditorium_id`) VALUES ( ? , ? , ? , ? , ? )");

                foreach ($data as $AuditoriumEvent) {
                    $statement->execute(array($AuditoriumEvent["AuditoriumEventId"],
                        $AuditoriumEvent["Name"], $AuditoriumEvent["CalendarId"],
                        $AuditoriumEvent["RingId"], $AuditoriumEvent["AuditoriumId"]));
                }

                $pdo->commit();
                break;
            case "faculties":
                if ($append !== "1") {
                    $query = "DROP TABLE IF EXISTS " . $dbPrefix . "faculties";
                    DB::statement($query);
                }

                $query  = "CREATE TABLE IF NOT EXISTS " . $dbPrefix . "faculties ( " .
                "`id` INT NOT NULL AUTO_INCREMENT, " .
                "`name` varchar(100) NOT NULL, " .
                "`letter` varchar(100) NOT NULL, " .
                "`sorting_order` INT NOT NULL, " .
                "`schedule_signing_title` varchar(100) NOT NULL, " .
                "`dean_signing_schedule` varchar(100) NOT NULL, " .
                "`session_signing_title` varchar(100) NOT NULL, " .
                "`dean_signing_session_schedule` varchar(100) NOT NULL, " .
                "PRIMARY KEY  (`id`)" .
                ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";

                DB::statement($query);

                $pdo = DB::getPdo();
                $pdo->beginTransaction();
                $statement = $pdo->prepare("INSERT INTO " . $dbPrefix . "faculties (id, name, letter, sorting_order, schedule_signing_title, dean_signing_schedule, session_signing_title, dean_signing_session_schedule) VALUES ( ? , ? , ? , ? , ? , ? , ? , ? )");

                foreach ($data as $faculty) {
                    $statement->execute(array($faculty["FacultyId"], $faculty["Name"], $faculty["Letter"], $faculty["SortingOrder"], $faculty["ScheduleSigningTitle"], $faculty["DeanSigningSchedule"], $faculty["SessionSigningTitle"], $faculty["DeanSigningSessionSchedule"]));
                }

                $pdo->commit();
                break;
            case "GroupsInFaculties":
                if ($append !== "1") {
                    $query = "DROP TABLE IF EXISTS " . $dbPrefix . "faculty_student_group";
                    DB::statement($query);
                }

                $query  = "CREATE TABLE IF NOT EXISTS " . $dbPrefix . "faculty_student_group ( " .
                    "`id` INT NOT NULL AUTO_INCREMENT, " .
                    "`faculty_id` INT NOT NULL, " .
                    "`student_group_id` INT NOT NULL, " .
                    "PRIMARY KEY  (`id`)" .
                    ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";

                DB::statement($query);

                $pdo = DB::getPdo();
                $pdo->beginTransaction();
                $statement = $pdo->prepare("INSERT INTO " . $dbPrefix . "faculty_student_group (id, faculty_id, student_group_id) VALUES ( ? , ? , ? )");

                foreach ($data as $gif) {
                    $statement->execute(array($gif["GroupsInFacultyId"], $gif["FacultyId"], $gif["StudentGroupId"]));
                }

                $pdo->commit();
                break;
            case "exams":
                if ($append !== "1") {
                    $query = "DROP TABLE IF EXISTS " . $dbPrefix . "exams";
                    DB::statement($query);
                }

                $query  = "CREATE TABLE IF NOT EXISTS " . $dbPrefix . "exams ( " .
                    "`id` int(11) NOT NULL AUTO_INCREMENT, " .
                    "`discipline_id` int(11) NOT NULL, " .
                    "`is_active` int(4) NOT NULL, " .
                    "`consultation_datetime` varchar(20) NOT NULL, " .
                    "`consultation_auditorium_id` int(11) NOT NULL, " .
                    "`exam_datetime` varchar(20) NOT NULL, " .
                    "`exam_auditorium_id` int(11) NOT NULL, " .
                    "PRIMARY KEY  (`id`)" .
                    ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";

                DB::statement($query);

                $pdo = DB::getPdo();
                $pdo->beginTransaction();
                $statement = $pdo->prepare("INSERT INTO " . $dbPrefix . "exams (id, discipline_id, is_active, consultation_datetime, consultation_auditorium_id, exam_datetime, exam_auditorium_id) VALUES ( ? , ? , ? , ? , ? , ? , ? )");

                foreach ($data as $exam) {
                    $statement->execute(array($exam["ExamId"],
                        $exam["DisciplineId"],
                        $exam["IsActive"],
                        $exam["ConsultationDateTime"],
                        $exam["ConsultationAuditoriumId"],
                        $exam["ExamDateTime"],
                        $exam["ExamAuditoriumId"]));
                }

                $pdo->commit();
                break;
            case "examsLogEvents":
                if ($append !== "1") {
                    $query = "DROP TABLE IF EXISTS " . $dbPrefix . "exam_log_events";
                    DB::statement($query);
                }

                $query  = "CREATE TABLE IF NOT EXISTS " . $dbPrefix . "exam_log_events ( " .
                    "`id` int(11) NOT NULL AUTO_INCREMENT, " .
                    "`old_exam_id` int(11) NOT NULL, " .
                    "`new_exam_id` int(11) NOT NULL, " .
                    "`datetime` varchar(50) NOT NULL, " .
                    "PRIMARY KEY  (`id`)" .
                    ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";

                DB::statement($query);

                $pdo = DB::getPdo();
                $pdo->beginTransaction();
                $statement = $pdo->prepare("INSERT INTO " . $dbPrefix . "exam_log_events (id, old_exam_id, new_exam_id, datetime)  VALUES ( ? , ? , ? , ? )");

                foreach ($data as $examLogEvent) {
                    $statement->execute(array($examLogEvent["LogEventId"],
                        $examLogEvent["OldExamId"],
                        $examLogEvent["NewExamId"],
                        $examLogEvent["DateTime"])
                    );
                }

                $pdo->commit();
                break;
            case "scheduleNotes":
                if ($append !== "1") {
                    $query = "DROP TABLE IF EXISTS " . $dbPrefix . "schedule_notes";
                    DB::statement($query);
                }

                $query  = "CREATE TABLE IF NOT EXISTS " . $dbPrefix . "schedule_notes ( " .
                    "`id` int(11) NOT NULL AUTO_INCREMENT, " .
                    "`text` varchar(100) NOT NULL, " .
                    "`is_lesson` BOOLEAN NOT NULL, " .
                    "`lesson_id` INT NULL, " .
                    "`late_amount` INT NOT NULL, " .
                    "PRIMARY KEY  (`ScheduleNoteId`)" .
                    ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                DB::statement($query);

                $pdo = DB::getPdo();
                $pdo->beginTransaction();
                $statement = $pdo->prepare("INSERT INTO " . $dbPrefix . "schedule_notes (id, text, is_lesson, lesson_id, late_amount) VALUES ( ? , ? , ? , ?, ? )");

                foreach ($data as $note) {
                    $statement->execute(array($note["ScheduleNoteId"], $note["Text"], $note["IsLesson"],$note["LessonId"], $note["LateAmount"]));
                }

                $pdo->commit();
                break;
        }

        return response()->json(["success" => "OK"], 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function api(Request $request) {
        //ini_set('max_execution_time', 300);

        $input = $request->all();

        $action = $input["action"];

        $actions = ["list", "groupsBundle", "bundle", "update", "dailySchedule", "groupExams", "weekSchedule", "groupSchedule", "teacherWeekSchedule", "teacherSchedule", "teacherDisciplines", "groupDisciplines","teacherDisciplines", "dailyBuildingSchedule", "dailyBuildingLessons", "dailyBuildingAuditoriumEvents", "mainPageData"];

        if (!in_array($action, $actions))
        {
            return array("error" => "Неизвестное действие (action)");
        }

        switch ($action) {
            case "update":
            case "bundle":
                $bundle = array();
                $bundle["auditoriums"] = Auditorium::all();
                $bundle["calendars"] = Calendar::all();
                $bundle["disciplines"] = Discipline::all();
                $bundle["lessons"] = Lesson::all();
                $bundle["rings"] = Ring::all();
                $bundle["students"] = Student::all();
                $bundle["studentGroups"] = StudentGroup::all();
                $bundle["studentsInGroups"] = Student_Student_Group::all();
                $bundle["teachers"] = Teacher::all();
                $bundle["teacherForDisciplines"] = Discipline_Teacher::all();

                $bundle["configOptions"] = ConfigOption::all();
                $bundle["lessonLogEvents"] = LessonLogEvent::all();

                $bundle["faculties"] = Faculty::all();
                $bundle["groupsInFaculties"] = Faculty_Student_Group::all();

                return $bundle;
                break;
            case "groupsBundle":
                $bundle = array();
                $bundle["studentGroups"] = StudentGroup::all();
                $bundle["faculties"] = Faculty::all();
                $bundle["groupsInFaculties"] = Faculty_Student_Group::all();

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
                    case "auditoriums":             return Auditorium::all();
                    case "calendars":               return Calendar::all();
                    case "configOptions":           return ConfigOption::all();
                    case "disciplines":             return Discipline::all();
                    case "lessons":                 return Lesson::all();
                    case "rings":                   return Ring::all();
                    case "students":                return Student::all();
                    case "studentGroups":           return StudentGroup::all();
                    case "mainStudentGroups":       return StudentGroup::mainStudentGroups();
                    case "studentsInGroups":        return Student_Student_Group::all();
                    case "teachers":                return Teacher::all();
                    case "teacherForDisciplines":   return Discipline_Teacher::all();
                    case "lessonLogEvents":         return LessonLogEvent::all();
                    case "faculties":               return Faculty::all();
                    case "buildings":               return Building::all();
                    case "groupsInFaculties":       return Faculty_Student_Group::all();
                }
                break;
            case "dailySchedule":                   return $this->DailySchedule($input);
            case "weekSchedule":                    return $this->WeekSchedule($input);
            case "groupExams":                      return $this->GroupExams($input);
            case "teacherWeekSchedule":             return $this->TeacherWeekSchedule($input);
            case "teacherSchedule":                 return $this->TeacherSchedule($input);
            case "teacherDisciplines":              return $this->TeacherDisciplines($input);
            case "groupDisciplines":                return $this->GroupDisciplines($input);
            case "dailyBuildingSchedule":           return $this->DailyBuildingSchedule($input);
            case "dailyBuildingLessons":            return $this->DailyBuildingLessons($input);
            case "dailyBuildingAuditoriumEvents":   return $this->DailyBuildingAuditoriumEvents($input);

            // Site requests
            case "mainPageData":             return $this->MainPageData($input);
        }

        return array("error" => "Whoops, looks like something went wrong :-)");
    }

    private function DailySchedule($input) {
        if ((!isset($input['groupId'])) || (!isset($input['date'])))
        {
            return array("error" => "groupId и date (yyyy-mm-dd) обязательные параметры");
        }

        $groupId = $input["groupId"];
        $date = $input["date"];

        $groupDisciplineIds = Discipline::IdsFromGroupId($groupId);
        $disciplineTeacherIds = Discipline_Teacher::IdsFromDisciplineIds($groupDisciplineIds);
        $calendarId = Calendar::IdfromDate($date);

        return Lesson::GetDailyTFDLessons($disciplineTeacherIds, $calendarId);
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

        return $lessons;
    }

    private function GroupExams($input) {
        if (!isset($input['groupId']))
        {
            return array("error" => "groupId обязательный параметр");
        }

        $groupId = $input["groupId"];

        return Exam::FromGroupId($groupId);
    }

    private function TeacherWeekSchedule($input) {
        if ((!isset($input['teacherId'])) || (!isset($input['week'])))
        {
            return array("error" => "teacherId и week обязательные параметры");
        }

        $teacherId = $input["teacherId"];
        $week = $input["week"];

        $tfdIds = Discipline_Teacher::IdsFromTeacherId($teacherId);
        $lessons = Lesson::GetWeekTFDLessons($tfdIds, $week);

        return $lessons;
    }

    private function TeacherSchedule($input)
    {
        $dowLocal = array(
            1 => "Понедельник",
            2 => "Вторник",
            3 => "Среда",
            4 => "Четверг",
            5 => "Пятница",
            6 => "Суббота",
            7 => "Воскресенье"
        );

        if (!isset($input['teacherId']))
        {
            return array("error" => "teacherId обязательный параметр");
        }

        $teacherId = $input["teacherId"];

        $tfdIds = Discipline_Teacher::IdsFromTeacherId($teacherId);
        $lessons = Lesson::GetTFDLessonsQuery($tfdIds)
            ->orderBy('rings.time')
            ->get();

        $result = array();

        $lessons->map(function ($lesson) use (&$result, $dowLocal) {

            $dt = Carbon::createFromFormat('Y-m-d', $lesson->date);
            $dow = $dt->dayOfWeek;
            $dow = ($dow == 0) ? 7 : $dow;
            $lesson->dow = $dow;

            $lesson->time = substr($lesson->time, 0, 5);

            if (!array_key_exists($dow, $result)) {
                $result[$dow] = array();
                $result[$dow]["dow"] = $dow;
                $result[$dow]["dowString"] = $dowLocal[$dow];
                $result[$dow]["dowLessons"] = array();
            }

            if (!array_key_exists($lesson->time, $result[$dow]["dowLessons"])) {
                $result[$dow]["dowLessons"][$lesson->time] = array();
                $result[$dow]["dowLessons"][$lesson->time]["time"] = $lesson->time;
                $result[$dow]["dowLessons"][$lesson->time]["dowTimeLessons"] = array();
            }

            if (!array_key_exists($lesson->tfd_id, $result[$dow]["dowLessons"][$lesson->time]["dowTimeLessons"])) {
                $result[$dow]["dowLessons"][$lesson->time]["dowTimeLessons"][$lesson->tfd_id] = array();
                $result[$dow]["dowLessons"][$lesson->time]["dowTimeLessons"][$lesson->tfd_id]["tfdId"] = $lesson->tfd_id;
                $result[$dow]["dowLessons"][$lesson->time]["dowTimeLessons"][$lesson->tfd_id]["tfdIdDowTimeLessons"] = array();
            }

            $result[$dow]["dowLessons"][$lesson->time]["dowTimeLessons"][$lesson->tfd_id]["tfdIdDowTimeLessons"][] = $lesson;

        });

        foreach($result as $dow => $dowLessons)
        {
            foreach($dowLessons["dowLessons"] as $time => $dowTimeLessons)
            {
                foreach($dowTimeLessons["dowTimeLessons"] as $tfdId => $TfdIdDowTimeLessons)
                {
                    $weeks = array();
                    $aud = array();

                    foreach ($TfdIdDowTimeLessons["tfdIdDowTimeLessons"] as $lesson)
                    {
                        $week = Calendar::GetWeekNumber($lesson->date);
                        $weeks[] = $week;

                        if (!array_key_exists($lesson->aud_name, $aud)) {
                            $aud[$lesson->aud_name] = array();
                            $aud[$lesson->aud_name]["weeks"] = array();
                        }

                        $aud[$lesson->aud_name]["weeks"][] = $week;
                    }

                    $audArray = array();

                    if (count($aud) == 1)
                    {
                        foreach($aud as $audName => $audData) {
                            $audArray[] = $audName;
                        }
                    }
                    else {
                        foreach($aud as $audName => $audData) {
                            $audArray[] = static::GatherWeeksToString($aud[$audName]["weeks"]) . " - " . $audName;
                        }
                    }

                    $weeksString = static::GatherWeeksToString($weeks);
                    $result[$dow]["dowLessons"][$time]["dowTimeLessons"][$tfdId]['weeksString'] = $weeksString;
                    $result[$dow]["dowLessons"][$time]["dowTimeLessons"][$tfdId]['auditoriums'] = $audArray;

                    $result[$dow]["dowLessons"][$time]["dowTimeLessons"][$tfdId]['minWeek'] = min($weeks);
                    $result[$dow]["dowLessons"][$time]["dowTimeLessons"][$tfdId]['lesson'] = array();
                    $result[$dow]["dowLessons"][$time]["dowTimeLessons"][$tfdId]['lesson']['disc_name'] = $result[$dow]["dowLessons"][$time]["dowTimeLessons"][$tfdId]["tfdIdDowTimeLessons"][0]->disc_name;
                    $result[$dow]["dowLessons"][$time]["dowTimeLessons"][$tfdId]['lesson']['group_name'] = $result[$dow]["dowLessons"][$time]["dowTimeLessons"][$tfdId]["tfdIdDowTimeLessons"][0]->group_name;

                    unset($result[$dow]["dowLessons"][$time]["dowTimeLessons"][$tfdId]["tfdIdDowTimeLessons"]);
                }
            }
        }

        return $result;
    }

    public static function GatherWeeksToString($weekArray)
    {
        $result = array();
        $boolWeeks = array();
        for($i=0; $i<=25;$i++) {
            $boolWeeks[$i] = false;
        }
        foreach ($weekArray as $week) {
            $boolWeeks[$week] = true;
        }

        $prev = false;
        $baseNum = 25;
        for($i = 0; $i<=25; $i++)
        {
            if (($prev == false) && ($boolWeeks[$i] == true))
            {
                $baseNum = $i;
            }

            if (($boolWeeks[$i] == false) && (($i - $baseNum) > 2))
            {
                $result[] = $baseNum .  "-" . ($i - 1);

                for ($k = $baseNum; $k < $i; $k++)
                {
                    $boolWeeks[$k] = false;
                }
            }

            if ($boolWeeks[$i] == false)
                $baseNum = 25;

            $prev = $boolWeeks[$i];
        }

        $prev = false;
        $baseNum = 25;
        for($i = 1; $i<=25; $i = $i + 2)
        {
            if (($prev == false) && ($boolWeeks[$i] == true))
            {
                $baseNum = $i;
            }

            if (($boolWeeks[$i] == false) && (($i - $baseNum) > 4))
            {
                $result[] = $baseNum .  "-" . ($i - 2) . " (нечёт.)";

                for ($k = $baseNum; $k < $i; $k = $k + 2)
                {
                    $boolWeeks[$k] = false;
                }
            }

            if ($boolWeeks[$i] == false)
                $baseNum = 25;

            $prev = $boolWeeks[$i];
        }

        $prev = false;
        $baseNum = 25;
        for($i = 2; $i<=25; $i = $i + 2)
        {
            if (($prev == false) && ($boolWeeks[$i] == true))
            {
                $baseNum = $i;
            }

            if (($boolWeeks[$i] == false) && (($i - $baseNum) > 4))
            {
                $result[] = $baseNum .  "-" . ($i - 2) . " (чёт.)";

                for ($k = $baseNum; $k < $i; $k = $k + 2)
                {
                    $boolWeeks[$k] = false;
                }
            }

            if ($boolWeeks[$i] == false)
                $baseNum = 25;

            $prev = $boolWeeks[$i];
        }



        for ($i = 1; $i <= 25; $i++)
        {
            if ($boolWeeks[$i])
            {
                $result[] = $i;
            }
        }

        uasort($result, array('App\Http\Controllers\ApiController','weeksCompare'));

        return implode(", ", $result);
    }

    public static function weeksCompare($a, $b)
    {
        $dash = "-";
        $pos = mb_strpos($a,$dash);
        if ($pos !== false)
        {
            $a = mb_substr($a, 0, $pos);
        }

        $pos = mb_strpos($b,$dash);
        if ($pos !== false)
        {
            $b = mb_substr($b, 0, $pos);
        }

        if ($a > $b)
        {
            return 1;
        }
        elseif ($a < $b)
        {
            return -1;
        }

        return 0;
    }

    private function MainPageData($input)
    {
        $result = array();

        $result["currentWeek"] = Calendar::GetWeekNumber();
        $result["mainGroups"] = StudentGroup::mainStudentGroups();
        $result["teacherList"] = Teacher::IdAndFioList();
        $result["buildingsList"] = Building::all();
        $result["happy"] = $this->GetHappy();

        return $result;
    }

    private function GetHappy()
    {
        $result = array();

        $studentList = Student::GetHappy();

        for ($i = 0; $i < count($studentList); $i++) {
            $studentId =  $studentList[$i]->id;

            $groupNames = DB::table('student_student_group')
                ->where('student_id', '=', $studentId)
                ->join('student_groups', 'student_group_id', '=', 'student_groups.id')
                ->select('student_groups.name')
                ->get()
                ->map(function($item) { return $item->name;})
                ->toArray();

            array_filter($groupNames, function($item) {
                if (!((strpos($item, '-') !== false) ||
                    (strpos($item, '|') !== false) ||
                    (strpos($item, '+') !== false)))
                {
                    return true;
                } else {
                    return false;
                }
            });

            if (count($groupNames) > 0)
            {
                $result[] = $groupNames[0];
            }
        }

        sort($result);

        return $result;
    }

    private function DailyBuildingSchedule($input)
    {
        if ((!isset($input['buildingId'])) || (!isset($input['date'])))
        {
            return array("error" => "buildingId и date обязательные параметры");
        }

        $date = $input["date"];
        $buildingId = $input["buildingId"];

        $calendarId = DB::table('calendars')
            ->where('date', '=', $date)
            ->select('id')
            ->get()
            ->map(function($item) { return $item->id;});

        if ($calendarId->isEmpty()) {
            return array("error" => "Дата date не найдена в днях семестра");
        } else {
            $calendarId =  $calendarId[0];
        }

        $result = array();
        $result["table"] = array();

        $lessonList = Lesson::GetDailyBuildingLessons($calendarId, $buildingId);

        $timeArray = array();
        $audArray = array();

        foreach ($lessonList as $lesson)
        {
            if (!array_key_exists($lesson->time, $result["table"])) {
                $result["table"][$lesson->time] = array();
            }

            if (!array_key_exists($lesson->aud_name, $result["table"][$lesson->time])) {
                $result["table"][$lesson->time][$lesson->aud_name] = array();
            }

            $timeArray[] = $lesson->time;
            $audArray[] = $lesson->aud_name;

            $time = $lesson->time;
            unset($lesson->time);

            $aud = $lesson->aud_name;
            unset($lesson->aud_name);

            $lessonData = array();
            $lessonData["text"] = $lesson->group_name;
            $lessonData["title"] = $lesson->disc_name . "@" . $lesson->fio;

            $result["table"][$time][$aud][] = $lessonData;
        }

        $events = AuditoriumEvent::GetDailyBuildingAuditoriumEvents($calendarId, $buildingId);

        foreach ($events as $event)
        {
            if (!array_key_exists($event->time, $result["table"])) {
                $result["table"][$event->time] = array();
            }

            if (!array_key_exists($event->aud_name, $result["table"][$event->time])) {
                $result["table"][$event->time][$event->aud_name] = array();
            }

            $eventData = array();
            if (strpos($event->name, '@') !== false) {
                $eventSplit = explode(" ", $event->name);
                if (count($eventSplit) > 1)
                {
                    $eventData["text"] = $eventSplit[0];
                    $eventData["title"] = $eventSplit[1];
                }
                else
                {
                    $eventData["text"] = $event->name;
                    $eventData["title"] = "";
                }
            } else {
                $eventData["text"] = $event->name;
                $eventData["title"] = "";
            }

            $audArray[] = $event->aud_name;

            $result["table"][$event->time][$event->aud_name][] = $eventData;
        }

        $audArray = array_values(array_unique($audArray));
        sort($audArray);
        $result["audArray"] = $audArray;

        foreach ($result["table"] as $time => $timeLessons)
        {
            foreach ($audArray as $aud)
            {
                if (!array_key_exists($aud, $result["table"][$time])) {
                    $result["table"][$time][$aud] = array();
                }
            }
        }

        return $result;
    }

    private function DailyBuildingLessons($input)
    {
        if ((!isset($input['buildingId'])) || (!isset($input['calendarId'])))
        {
            return array("error" => "buildingId и calendarId обязательные параметры");
        }

        $calendarId = $input["calendarId"];
        $buildingId = $input["buildingId"];

        return Lesson::GetDailyBuildingLessons($calendarId, $buildingId);
    }

    private function DailyBuildingAuditoriumEvents($input)
    {
        if ((!isset($input['buildingId'])) || (!isset($input['calendarId'])))
        {
            return array("error" => "buildingId и calendarId обязательные параметры");
        }

        $calendarId = $input["calendarId"];
        $buildingId = $input["buildingId"];

        return AuditoriumEvent::GetDailyBuildingAuditoriumEvents($calendarId, $buildingId);
    }

    private function TeacherDisciplines($input)
    {
        if (!isset($input['teacherId']))
        {
            return array("error" => "teacherId обязательный параметр");
        }

        $teacherId = $input["teacherId"];

        return Discipline_Teacher::DisciplineListFromTeacherIdWithStat($teacherId);
    }

    private function GroupDisciplines($input)
    {
        if (!isset($input['groupId']))
        {
            return array("error" => "groupId обязательный параметр");
        }

        $groupId = $input["groupId"];

        return Discipline_Teacher::DisciplineListFromGroupIdWithStat($groupId);
    }
}
