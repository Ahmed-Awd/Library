<?php


namespace App\Repositories;


use App\Models\Academic;
use App\Models\AcademicSemester;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\CourseSubject;
use App\Models\Student;
use App\Models\StudentSubject;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Model;

class CourseRepository implements CourseRepositoryInterface
{
    protected Course $course;

    public function __construct(Course $course)
    {
        $this->course = $course;
    }

    public function get()
    {
        return $this->course->all();
    }

    public function show($course,$withClasses)
    {
        if($withClasses == true){
            return $course->with('classes')->first();
        }
        return $course;
    }

    public function getClasses($course)
    {
        return $course->classes;
    }

    public function delete($course)
    {
        $course->delete();
    }

    public function store($data)
    {
        $record['title']         = $data['title'];
        $record['description']   = $data['description'];
        $record['status']        = "Active";
        $record['is_graduated']  = $data['is_graduated'];
        $record['category_id']  = $data['category_id'];
        $this->course->create($record);
    }

    public function update($course,$data)
    {
        $course->update($data);
    }

    public function getSubjects($course)
    {
        $academic_year = new Academic();
        $academic_id = $academic_year->defaultYear()->id;
        $data['course_title'] = $course->title;
        $data['semesters'] = AcademicSemester::with(['courseSubject'=> function($query) use($course) {
                                        $query->where('course_id', $course->id);
                                        $query->select('semester_id','subject_id');
                                    },'courseSubject.subject'])
                                    ->where('academic_id',$academic_id)
                                    ->select('id','academic_id','sem_num')
                                    ->get();
        return $data;
    }

    public function setSubjects($data, $course)
    {
        if($data['semester'] == 'all'){
            $academic_year = new Academic();
            $semesters = $academic_year->defaultSemesters();
        }
        $students = $this->studentsOfCourse($course);
        foreach($data['subjects'] as $subject){
            $subjectRecord = new Subject();
            $subjectRecord->title = $subject['title'].' - '. $course->title;
            $subjectRecord->is_lab = $subject['is_lab'];
            $subjectRecord->category_id = auth()->user()->category_id;
            $subjectRecord->save();

            $record['course_id'] = $course->id;
            $record['subject_id'] = $subjectRecord->id;
            $record['branch_id'] = auth()->user()->branch_id;
            if($data['semester'] == 'all'){
                foreach ($semesters as $semester) {
                    $record['semester_id'] = $semester->id;
                    CourseSubject::create($record);
                    foreach ($students as $student) {
                        $student_subject['student_id'] = $student;
                        $student_subject['subject_id'] = $subjectRecord->id;
                        $student_subject['semester_id'] = $semester->id;
                        StudentSubject::create($student_subject);
                    }
                }
            } else {
                $record['semester_id'] = $data['semester'];
                CourseSubject::create($record);
            }
        }


    }

    public function getClassSubjects($class)
    {
       $semester_id = new AcademicSemester();
       $semester_id = $semester_id->defaultSemester();

       $student = Student::where('class_id', $class->id)->first();
       if(!$student){
           return "there is no students in class";
       } else {
            return StudentSubject::with(['subject'])
                         ->join('course_subjects','course_subjects.subject_id','student_subjects.subject_id')
                         ->where('course_id', $class->course_id)
                         ->where('student_id',$student->user_id)
                         ->where('student_subjects.semester_id',$semester_id)
                        ->groupBy('course_subjects.subject_id')
                         ->get();
       }
    }
    public function studentsOfCourse($course) {
            return Student::join('classes', 'classes.id', 'students.class_id')
                               ->where('course_id', $course->id)
                              ->pluck('user_id');
    }

    public function deleteClassSubject($class, $subject) {

        $semester_id = new AcademicSemester();
        $semester_id = $semester_id->defaultSemester();

        StudentSubject::join('students', 'student_subjects.student_id', 'students.user_id')
            ->where('class_id', $class->id)
            ->where('subject_id', $subject)
            ->where('semester_id', $semester_id)
            ->delete();
    }

}
