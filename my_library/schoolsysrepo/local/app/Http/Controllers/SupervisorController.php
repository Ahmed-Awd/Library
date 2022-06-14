<?php

namespace App\Http\Controllers;


use App\Academic;
use App\AcademicSemester;
use App\Course;
use App\Language;
use App\Quiz;
use App\QuizApplicability;
use App\Staff;
use App\Student;
use App\SupervisorStaff;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Yajra\Datatables\Datatables;

class SupervisorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
        $data['active_class'] = 'master_settings';

        $data['layout'] = getLayout();
        $data['title'] = getPhrase('dashboard');
        return view('educational_supervisor.all-supervisors', $data);
    }

    public function getDatatableOfTeachers()
    {

        $records = User::join('supervisors_staff', 'supervisors_staff.staff_id', '=', 'users.id')
            ->select([
                'users.name',
                'users.slug'
            ])
            ->where('supervisors_staff.supervisor_id', Auth::user()->id);
        $currentSlug = explode('/', $_SERVER['HTTP_REFERER']);
        $currentSlug = $currentSlug[count($currentSlug) - 1];
        $link = '';
        $titleOfAction = '';
        if ($currentSlug == 'teachers-subjects') {
            $link = 'assign-subject/';
            $titleOfAction = 'assign_subject_to_teacher';
        } elseif ($currentSlug == 'staff-topic-plan') {
            $link = 'lesson-plans/';
            $titleOfAction = 'lesson_plans_of_teacher';
        } elseif ($currentSlug == 'teacher-student-attendance') {
            $link = 'students-attendance/';
            $titleOfAction = 'teacher_students_attendance';
        } elseif ($currentSlug == 'teachers-timetable') {
            $link = 'teacher-timetable/';
            $titleOfAction = 'teacher_timetable';
        } elseif ($currentSlug == 'students-marks') {
            $link = 'students-marks/';
            $titleOfAction = 'students_marks';
        }

        return Datatables::of($records)
            ->addColumn('action', function ($records) use ($link, $titleOfAction) {
                return '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                        <li>
                       
                            <a href="' . $link . $records->slug . '"><i class="fa fa-pencil"></i>' . getPhrase("$titleOfAction") . '</a></li>
                            
                            </ul>
                    </div>';
            })
            ->removeColumn('id')
            ->removeColumn('username')
            ->removeColumn('slug')
            ->make();
    }

    public function getDatatable()
    {
        $records = User::select(['name', 'slug'])->where('role_id', 9);
        return Datatables::of($records)
            ->addColumn('action', function ($records) {
                return '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                        <li>
                       
                            <a href="assign-staff/' . $records->slug . '"><i class="fa fa-pencil"></i>' . getPhrase("assign_teachers_to_supervisor") . '</a></li>
                            
                            </ul>
                    </div>';
            })
            ->removeColumn('slug')
            ->make();
    }

    public function getStudentsView($slug)
    {
        
        $user = User::where('slug', $slug)->first(['id', 'name', 'slug']);
        $classes = Staff::join('courses', 'courses.id', '=', 'staff.course_id')
            ->where('staff.user_id', $user->id)
            ->select(['courses.course_title', 'courses.id'])
            ->get();
        $data['classes'] = $classes;
        $data['title'] = getPhrase('students-marks-of-teacher');
        $data['layout'] = getLayout();
        $data['active_class'] = 'students-marks';
        $data['slug'] = $user;
        return view('educational_supervisor.students-marks', $data);
    }

    public function getClassMarks(Request $request, $slug)
    {
        if ($request->course_id == 'select') {
            return array('students' => array());
        }
        $user = User::where('slug', $slug)->first(['id']);
        $currentAcademic = new Academic();
        $currentAcademic = $currentAcademic->getCurrentAcademic()->id;
        $currentSemester = new AcademicSemester();
        $currentSemester = $currentSemester->getCurrentSemeterOfAcademicYear($currentAcademic)->sem_num;
        $quiz_details = Quiz::get();
        $subjects = QuizApplicability::
        join('quizzes', 'quizapplicability.quiz_id', '=', 'quizzes.id')
            ->join('subjects', 'subjects.id', '=', 'quizzes.subject_id')
            ->join('subjectpreferences', 'subjectpreferences.subject_id', '=', 'subjects.id')
            ->where('subjectpreferences.user_id', $user->id)
            ->where('subjectpreferences.record_status','!=',3)
            ->where('quizzes.record_status','!=',3)
            ->where('quizapplicability.academic_id', '=', $currentAcademic)
            ->where('quizapplicability.semister', '=', $currentSemester)
            ->select([
                'quizzes.id as quiz_id',
                'quizzes.title',
                'quizzes.subject_id',
                'subject_title',
                'subject_code',
                'quizzes.offline_quiz_category_id',
                'total_marks'
            ])
            ->groupBy('quiz_id')
            ->get();
        foreach ($quiz_details as $quiz_datail) {
            $students = Student::join('users', 'users.id', '=', 'students.user_id')
                /* ->join('quizresults', 'quizresults.user_id', '=', 'users.id')*/
                /*->where('quizresults.quiz_id', '=', $quiz_datail->id)*/
                ->where('students.course_id', '=', $request->course_id)
                /* ->where('quizresults.academic_id', '=', $currentAcademic)
                 ->where('quizresults.semister', '=', $currentSemester)*/
                ->groupBy('roll_no')
                ->select([
                    'users.id as user_id',
                    'roll_no',
                    'name',
                    'image',
                    'students.id as student_id',
                    'users.slug as user_slug'
                ])
                ->get();
        }
        $students_list = [];
        $final_list = [];
        if (isset($students)) {
            foreach ($students as $student) {
                $temp = [];
                $temp['user_id'] = $student->user_id;
                $temp['name'] = $student->name;
                $temp['roll_no'] = $student->roll_no;
                $temp['image'] = $student->image;
                $temp['slug'] = $student->user_slug;
                $subject_marks = [];
                $average = 0;
                foreach ($subjects as $subject) {
                    $marks_records = $this->getSubjectMarks($student->user_id,
                        $subject->subject_id);
                    $subject_marks['subject_id'] = $subject->subject_id;
                    $subject_marks['subject_title'] = $subject->subject_title;
                    $subject_marks['subject_code'] = $subject->subject_code;
                    $subject_marks ['score'] = isset($marks_records) ? $marks_records : null;
                    $subject_marks ['percentage'] = isset($marks_records->percentage) ? $marks_records->percentage : 0;
                    $average = $average + $subject_marks ['percentage'];
                    $temp['marks'][] = $subject_marks;
                }
                $temp['average'] = 0;
                $total_subjects = count($subjects);

                if ($total_subjects) {
                    $temp['average'] = round($average / count($subjects));
                }

                $students_list[] = $temp;

            }
        }
        $final_list['students'] = $students_list;
        $final_list['subjects'] = $subjects;
        // $final_list['course_title'] = $title;
        return $final_list;
    }

    public function getSubjectMarks($user_id, $subject_id)
    {
        $records = Quiz::join('quizresults', 'quizzes.id', '=', 'quizresults.quiz_id')
            ->where('quizzes.subject_id', '=', $subject_id)
            ->where('quizresults.user_id', '=', $user_id)
            ->select([
                'user_id',
                'quizresults.marks_obtained',
                'subject_id',
                'quizresults.total_marks',
                'quizresults.percentage',
                'quizresults.exam_status'
            ])->first();
        return $records;
    }

    public function getTeachers($slug)
    {
        $roleNameOfAuth = Auth::user()->role_id;
        $data['roleNameOfAuth'] = $roleNameOfAuth;
        $data['layout'] = getLayout();
        if ($slug == 'teachers-subjects') {
            $data['active_class'] = 'teachers-subjects';
            $data['title'] = getPhrase('assign_subjects_to_teachers');
        } elseif ($slug == 'staff-topic-plan') {
            $data['active_class'] = 'staff-topic-plan';
            $data['title'] = getPhrase('staff_topic_plan');
        } elseif ($slug == 'teacher-student-attendance') {
            $data['active_class'] = 'teacher-student-attendance';
            $data['title'] = getPhrase('students_attendance_of_teacher');
        } elseif ($slug == 'teachers-timetable') {
            $data['active_class'] = 'teachers-timetable';
            $data['title'] = getPhrase('teacher_timetable');
        } elseif ($slug == 'students-marks') {
            $data['active_class'] = 'students-marks';
            $data['title'] = getPhrase('students_marks');
        }
        return view('educational_supervisor.teachers', $data);
    }


    public function assignStuff($slug)
    {

        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
        $record = User::where('slug', '=', $slug)->first();
        if ($isValid = $this->isValidRecord($record)) {
            return redirect($isValid);
        }

        $staff_records = User::where('role_id', '3')->where('status', '1')->get(['id', 'name', 'username']);
        $allocated_staff = User::join('supervisors_staff', 'supervisors_staff.staff_id', '=', 'users.id')
            ->where('supervisors_staff.supervisor_id', $record->id)
            ->where('supervisors_staff.record_status','!=', '3')->get([
                'users.id',
                'users.name',
                'users.username',
                'users.slug'
            ]);
        $data['record'] = $record;
        $data['active_class'] = 'users';
        $data['title'] = getPhrase('staff_to_supervisor');
        $data['items'] = json_encode(array('staff' => $staff_records, 'allocated_staff' => $allocated_staff));
        $data['right_bar'] = true;
        $data['right_bar_path'] = 'educational_supervisor.item-view-right-bar';
        $data['right_bar_data'] = array(
            'item' => $staff_records,
        );
        $data['layout'] = getLayout();
        $data['module_helper'] = getModuleHelper('allocate-course-to-academics');
        return view('educational_supervisor.add-edit', $data);
    }

    public function isValidRecord($record)
    {
        if ($record === null) {

            flash(getPhrase('Ooops'), getPhrase("page_not_found"), 'error');
            return $this->getRedirectUrl();
        }

        return false;
    }

    public function updateStaffSupervisors(Request $request, $slug)
    {
        $record = User::where('slug', '=', $slug)->first(['id']);

        if ($isValid = $this->isValidRecord($record)) {
            return redirect($isValid);
        }
        DB::beginTransaction();
        try {
            $teacherNames = '';
            $exist = '';
            if (count($request->selected_list)) {
                $previousRecord = SupervisorStaff::where('supervisor_id', $record->id)->delete();
                foreach (array_unique($request->selected_list) as $key => $value) {
                   /* if (SupervisorStaff::where('staff_id', $value)->first() != null) {
                        $teacherName = User::where('id', $value)->first(['name']);
                        $exist = 1;
                        $teacherNames .= '(' . $teacherName->name . ')';
                        continue;
                    }*/
                    $newRecord = new SupervisorStaff();
                    $newRecord->supervisor_id = $record->id;
                    $newRecord->staff_id = $value;
                    $newRecord->save();
                }

            }
            DB::commit();
            
           /* if ($exist == 1) {
                flash(getPhrase('Ooops'), $teacherNames . ' ' . getPhrase('Assigned_to_another_supervisor'), 'error');
            } else {
                flash(getPhrase('success'), getPhrase('records_updated_successfully'), 'success');
            }*/
            flash(getPhrase('success'), getPhrase('records_updated_successfully'), 'success');
        } catch (Exception $ex) {
            DB::rollBack();
            if (getSetting('show_foreign_key_constraint', 'module')) {

                flash(getPhrase('Ooops'), $ex->getMessage(), 'error');
            } else {
                flash(getPhrase('Ooops'), getPhrase('improper_data'), 'error');
            }
        }

        return redirect()->back();
    }

    public function checkStatus(Request $request)
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
        $supervisorId = $request->supervisorId;
        $staffId = $request->staffId;
        SupervisorStaff::where('supervisor_id', $supervisorId)->where('staff_id', $staffId)->delete();

    }

    public function printClassMarks(Request $request, $slug)
    {
        if ($request->course_id == 'select') {
            return array('students' => array());
        }
        $user = User::where('slug', $slug)->first(['id']);
        $currentAcademic = new Academic();
        $currentAcademic = $currentAcademic->getCurrentAcademic()->id;
        $currentSemester = new AcademicSemester();
        $currentSemester = $currentSemester->getCurrentSemeterOfAcademicYear($currentAcademic)->sem_num;
        $quiz_details = Quiz::get();
        $subjects = QuizApplicability::
        join('quizzes', 'quizapplicability.quiz_id', '=', 'quizzes.id')
            ->join('subjects', 'subjects.id', '=', 'quizzes.subject_id')
            ->join('subjectpreferences', 'subjectpreferences.subject_id', '=', 'subjects.id')
            ->where('subjectpreferences.user_id', $user->id)
            ->where('subjectpreferences.record_status','!=',3)
            ->where('quizzes.record_status','!=',3)
            ->where('quizapplicability.academic_id', '=', $currentAcademic)
            ->where('quizapplicability.semister', '=', $currentSemester)
            ->select([
                'quizzes.id as quiz_id',
                'quizzes.title',
                'quizzes.subject_id',
                'subject_title',
                'subject_code',
                'quizzes.offline_quiz_category_id',
                'total_marks'
            ])
            ->groupBy('quiz_id')
            ->get();
        foreach ($quiz_details as $quiz_datail) {
            $students = Student::join('users', 'users.id', '=', 'students.user_id')
                /* ->join('quizresults', 'quizresults.user_id', '=', 'users.id')*/
                /*->where('quizresults.quiz_id', '=', $quiz_datail->id)*/
                ->where('students.course_id', '=', $request->classNumber)
                /* ->where('quizresults.academic_id', '=', $currentAcademic)
                 ->where('quizresults.semister', '=', $currentSemester)*/
                ->groupBy('roll_no')
                ->select([
                    'users.id as user_id',
                    'roll_no',
                    'name',
                    'image',
                    'students.id as student_id',
                    'users.slug as user_slug'
                ])
                ->get();
        }
        $students_list = [];
        $final_list = [];
        if (isset($students)) {
            foreach ($students as $student) {
                $temp = [];
                $temp['user_id'] = $student->user_id;
                $temp['name'] = $student->name;
                $temp['roll_no'] = $student->roll_no;
                $temp['image'] = $student->image;
                $temp['slug'] = $student->user_slug;
                $subject_marks = [];
                $average = 0;
                foreach ($subjects as $subject) {
                    $marks_records = $this->getSubjectMarks($student->user_id,
                        $subject->subject_id);
                    $subject_marks['subject_id'] = $subject->subject_id;
                    $subject_marks['subject_title'] = $subject->subject_title;
                    $subject_marks['subject_code'] = $subject->subject_code;
                    $subject_marks ['score'] = isset($marks_records) ? $marks_records : null;
                    $subject_marks ['percentage'] = isset($marks_records->percentage) ? $marks_records->percentage : 0;
                    $average = $average + $subject_marks ['percentage'];
                    $temp['marks'][] = $subject_marks;
                }
                $temp['average'] = 0;
                $total_subjects = count($subjects);

                if ($total_subjects) {
                    $temp['average'] = round($average / count($subjects));
                }

                $students_list[] = $temp;

            }
        }
        $final_list['students'] = $students_list;
        $final_list['subjects'] = $subjects;
        $data ['final_list'] = $final_list;
        $data ['classNameConcat'] = DB::table('courses as t1')->
                              select(['t1.course_title as child','t2.course_title as parent'])->
                              join('courses AS t2', 't2.id', '=', 't1.parent_id')->
                              where('t1.id', $request->classNumber)
                              ->get();
        $classTitle=' '.getPhrase('for').' '.$data['classNameConcat'][0]->child.' '.$data['classNameConcat'][0]->parent;
        $data['lang']=Language::getDefaultLanguageRecord()->is_rtl;
        $data['title'] = getPhrase('outstanding_students_report') . $classTitle;
        return view('educational_supervisor.print_marks', $data);

    }
}
