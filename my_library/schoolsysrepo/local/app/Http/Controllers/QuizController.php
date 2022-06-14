<?php

namespace App\Http\Controllers;

use App;
use App\QuestionBank;
use App\Quiz;
use App\QuizCategory;
use App\Subject;
use Auth;
use DB;
use Exception;
use Illuminate\Http\Request;
use PHPSTORM_META\type;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Redirect;

class QuizController extends Controller
{

    protected $examSettings;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getExamSettings()
    {
        return $this->examSettings;
    }

    public function setExamSettings()
    {
        $this->examSettings = getExamSettings();
    }

    /**
     * This menthod will dispaly the quizzes dashboard
     * @return [type] [description]
     */
    public function quizdashboard()
    {

        if (!checkRole(getUserGrade(3))) {
            prepareBlockUserMessage();
            return back();
        }

        $data['active_class'] = 'exams';
        $data['title'] = getPhrase('exams_dashboard');
        $data['layout'] = getLayout();
        $data['module_helper'] = getModuleHelper('exams-dashboard');
        return view('exams.dashboard', $data);

    }

    /**
     * Course listing method
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        if (!checkRole(getUserGrade(3))) {
            prepareBlockUserMessage();
            return back();
        }

        $data['active_class'] = 'exams';
        $data['title'] = getPhrase('quizzes');
        $data['module_helper'] = getModuleHelper('quiz-list');
        return view('exams.quiz.list', $data);
    }

    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatable($slug = '')
    {

        if (!checkRole(getUserGrade(3))) {
            prepareBlockUserMessage();
            return back();
        }

        $records = array();

        if ($slug == '') {
            if(is_teacher()){
                $ids = teacher_quizzes();
                $records = Quiz::withoutGlobalScope(App\Scopes\BranchScope::class)
                    ->join('quizcategories', 'quizzes.category_id', '=', 'quizcategories.id')
                    ->join('courses','quizcategories.course_id','=','courses.id')
                    ->join('course_subject','quizzes.course_id','=','course_subject.course_parent_id')
                    ->where('quizzes.type','=','online')
                    ->where('course_subject.staff_id',Auth::user()->id)
                    ->whereIn('quizzes.subject_id',$ids)
                    ->groupBy('quizzes.id')
                    ->select([
                        'type',
                        'title',
                        'dueration',
                        'courses.course_title',
                        'quizcategories.category',
                        'is_paid',
                        'total_marks',
                        'quizzes.subject_id',
                        'tags',
                        'quizzes.slug',
                        'quizzes.id as quiz_id',
                        'quizzes.created_by_user', 'quizzes.updated_by_user', 'quizzes.created_by_ip', 'quizzes.updated_by_ip', 'quizzes.created_at', 'quizzes.updated_at'

                    ])
                    ->orderBy('quizzes.updated_at', 'desc');

            }else {
                $records = Quiz::withoutGlobalScope(App\Scopes\BranchScope::class)
                    ->join('quizcategories', 'quizzes.category_id', '=', 'quizcategories.id')
                    ->join('courses','quizcategories.course_id','=','courses.id')
                    ->where('quizzes.type','=','online')
                    ->select([
                        'type',
                        'title',
                        'dueration',
                        'courses.course_title',
                        'quizcategories.category',
                        'quizzes.subject_id',
                        'is_paid',
                        'total_marks',
                        'tags',
                        'quizzes.slug',
                        'quizzes.id as quiz_id',
                        'quizzes.created_by_user', 'quizzes.updated_by_user', 'quizzes.created_by_ip', 'quizzes.updated_by_ip', 'quizzes.created_at', 'quizzes.updated_at'

                    ])
                   ->where('courses.category_id',Auth::user()->category_id)
                    ->orderBy('quizzes.updated_at', 'desc');
            }


        } else {
            $category = QuizCategory::getRecordWithSlug($slug);
            $records = Quiz::join('quizcategories', 'quizzes.category_id', '=', 'quizcategories.id')
                ->join('courses','quizcategories.course_id','=','courses.id')
                ->where('quizzes.type','=','online')
                ->select([
                    'type',
                    'title',
                    'dueration',
                    'courses.course_title',
                    'quizcategories.category',
                    'is_paid',
                    'total_marks',
                    'quizzes.subject_id',
                    'tags',
                    'quizzes.slug',
                    'quizzes.id as quiz_id',
                    'quizzes.created_by_user','quizzes.updated_by_user','quizzes.created_by_ip','quizzes.updated_by_ip','quizzes.created_at','quizzes.updated_at'
                ])
                ->where('quizzes.category_id', '=', $category->id)
                ->orderBy('quizcategories.updated_at', 'desc');
        }


        return Datatables::of($records)

            ->addColumn('academic_year', function ($record) {
                return getSubjectDetails($record->subject_id)['year']->academic_year_title;
            })
            ->addColumn('semester', function ($record) {
                return getPhrase(SemesterName(getSubjectDetails($record->subject_id)['sem']));
            })
            ->addColumn('branch', function ($record) {
                return getSubjectDetails($record->subject_id)['course']->course_title;
            })
            ->addColumn('subject', function ($record) {
                return getSubjectDetails($record->subject_id)['subject']->subject_title;
            })

            ->addColumn('action', function ($records) {

                $records->created_by_user_name = App\User::get_user_name($records->created_by_user);
                $records->updated_by_user_name = App\User::get_user_name($records->updated_by_user);
                $view = "<li><a onclick='pop_it($records)'><i class=\"fa fa-eye\"></i>".getPhrase('view_record_history')."</a></li>";

                $link_data = '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                           <li><a href="' . URL_QUIZ_UPDATE_QUESTIONS . $records->slug . '"><i class="fa fa-spinner" aria-hidden="true"></i>' . getPhrase("update_questions") . '</a></li>
                            <li><a href="' . URL_QUIZ_EDIT . '/' . $records->slug . '"><i class="fa fa-pencil"></i>' . getPhrase("edit") . '</a></li>'.$view;

                $temp = '';
                if (checkRole(getUserGrade(1))) {
                    $temp .= ' <li><a href="javascript:void(0);" onclick="deleteRecord(\'' . $records->slug . '\');"><i class="fa fa-trash"></i>' . getPhrase("delete") . '</a></li>';
                }

                $temp .= '</ul></div>';


                $link_data .= $temp;
                return $link_data;
            })
            ->editColumn('type', function ($records) {
                return ucfirst($records->type);
            })
            ->editColumn('is_paid', function ($records) {
                return ($records->is_paid) ? '<span class="label label-primary">' . getPhrase('paid') . '</span>' : '<span class="label label-success">' . getPhrase('free') . '</span>';
            })
            ->editColumn('title', function ($records) {
                return '<a href="' . URL_QUIZ_UPDATE_QUESTIONS . $records->slug . '">' . $records->title . ' (' . $records->quiz_id . ')' . '</a>';
            })
            ->removeColumn('quiz_id')
            ->removeColumn('slug')
            ->removeColumn('course_title')
            ->removeColumn('tags')
            ->removeColumn('subject_id')
            ->removeColumn('created_by_user')
            ->removeColumn('updated_by_user')
            ->removeColumn('created_by_ip')
            ->removeColumn('updated_by_ip')
            ->removeColumn('created_at')
            ->removeColumn('updated_at')
            ->make();
    }

    /**
     * This method loads the create view
     * @return void
     */
    public function create()
    {
        if (!checkRole(getUserGrade(3))) {
            prepareBlockUserMessage();
            return back();
        }
        $data['record'] = false;
        $data['active_class'] = 'exams';
        $data['categories'] = array_pluck(QuizCategory::all(), 'category', 'id');
        $data['offline_categories'] = array_pluck(App\OfflineQuizCategories::
        where('status', '=', 1)->get(), 'title', 'id');
        $data['instructions'] = array_pluck(App\Instruction::all(), 'title', 'id');
        if(Auth::user()->role_id == 3){
            $subjects = App\Subject::join('subjectpreferences','subjects.id','=','subjectpreferences.subject_id')->select('subjects.id','subjects.subject_title')->where('user_id','=',Auth::user()->id)->get();
            $data['subjects']       	= array_pluck($subjects, 'subject_title', 'id');
        }else{    	$data['subjects']       	= array_pluck(App\Subject::all(), 'subject_title', 'id');  }
        $data['academic_years'] = addSelectToList(getAcademicYears());
        $data['items'] = json_encode(
            array(
                'academic_years' => \App\Academic::select(['id', 'academic_year_title'])->get()
            ));
        $data['title'] = getPhrase('create_quiz');
        $data['module_helper'] = getModuleHelper('create-quiz');
        $data['edit_or_add'] = 'add';
        $data['sid'] = 0;
        return view('exams.quiz.add-edit', $data);
    }

    /**
     * This method loads the edit view based on unique slug provided by user
     * @param  [string] $slug [unique slug of the record]
     * @return [view with record]
     */
    public function edit($slug)
    {
        if (!checkRole(getUserGrade(3))) {
            prepareBlockUserMessage();
            return back();
        }

        $record = Quiz::getRecordWithSlug($slug);
        if ($isValid = $this->isValidRecord($record)) {
            return redirect($isValid);
        }

        $data['record'] = $record;
        $data['sid'] = $data['record']->subject_id;
        $data['active_class'] = 'exams';
        $data['settings'] = false;
        $data['offline_categories'] = array_pluck(App\OfflineQuizCategories::
        where('status', '=', 1)->get(), 'title', 'id');

        $data['instructions'] = array_pluck(App\Instruction::all(), 'title', 'id');
        $data['categories'] = array_pluck(QuizCategory::all(), 'category', 'id');
        $data['title'] = getPhrase('edit_quiz');
        if(Auth::user()->role_id == 3){
            $subjects = App\Subject::join('subjectpreferences','subjects.id','=','subjectpreferences.subject_id')->select('subjects.id','subjects.subject_title')->where('user_id','=',Auth::user()->id)->get();
            $data['subjects']       	= array_pluck($subjects, 'subject_title', 'id');
        }else{    	$data['subjects']       	= array_pluck(App\Subject::all(), 'subject_title', 'id');  }
        $data['academic_years'] = addSelectToList(\App\Academic::pluck('academic_year_title', 'id'));

        $items = App\QuizApplicability::join('academics', 'academics.id', '=', 'quizapplicability.academic_id')
            ->join('courses', 'courses.id', '=', 'quizapplicability.course_id')
            ->where('quiz_id', '=', $record->id)
            ->select([
                'academic_id',
                'academic_year_title',
                'course_parent_id',
                'course_id',
                'course_title',
                'year',
                'semister',
                'course_dueration'
            ])
            ->get();


        $data['items'] = json_encode(
            array(
                'academic_years' => \App\Academic::select(['id', 'academic_year_title'])->get(),
                'available_items' => $items
            )
        );
        $data['module_helper'] = getModuleHelper('create-quiz');
        $data['edit_or_add'] = 'edit';
        $data['current_category'] = QuizCategory::where('id',$record->category_id)->first();
        return view('exams.quiz.add-edit', $data);
    }

    public function isValidRecord($record)
    {
        if ($record === null) {

            flash(getPhrase('Ooops'), getPhrase("page_not_found"), 'error');
            return $this->getRedirectUrl();
        }

        return false;
    }

    /**
     * Update record based on slug and reuqest
     * @param  Request $request [Request Object]
     * @param  [type]  $slug    [Unique Slug]
     * @return void
     */
    public function update(Request $request, $slug)
    {

        if (!checkRole(getUserGrade(3))) {
            prepareBlockUserMessage();
            return back();
        }

        $record = Quiz::getRecordWithSlug($slug);
        $rules = [
            'title' => 'bail|required|max:60',
            'dueration' => 'bail|required|integer',
        ];

        $type = $request->type;

        $instructions_page_id = 0;
        $pass_percentage = 0;
        $record->is_paid = 0;
        $record->negative_mark = 0;
        $record->type = $request->type;
        $record->course_id = $request->course_id;

        $record->instructions_page_id = App\Instruction::first()->id;

        if ($type == 'online') {
            $rules['instructions_page_id'] = 'bail|required|integer';
            $rules['category_id'] = 'bail|required|integer';
            $rules['pass_percentage'] = 'bail|required|numeric|max:100|min:1';
            $instructions_page_id = $request->instructions_page_id;
            $pass_percentage = $request->pass_percentage;
            $record->is_paid = $request->is_paid;
            $record->type = $request->type;
            $record->having_negative_mark = 1;
            $record->negative_mark = $request->negative_mark;
            $record->instructions_page_id = $request->instructions_page_id;


        } else {
            $record->offline_quiz_category_id = $request->offline_quiz_category_id;
        }

        /**
         * Check if the title of the record is changed,
         * if changed update the slug value based on the new title
         */
        $name = $request->title;
        if ($name != $record->title) {
            $record->slug = $record->makeSlug($name, true);
        }

        //Validate the overall request
        $this->validate($request, $rules);
        DB::beginTransaction();

        try {
            $name = $request->title;
            $record->title = $name;
            $record->category_id = $request->category_id;
            $record->dueration = $request->dueration;
            $record->total_marks = $request->total_marks;
            $record->pass_percentage = $request->pass_percentage;
            $record->entry_option = $request->entry_option;
            $record->tags = '';
            $record->cost = 0;
            $record->validity = -1;
            if ($record->is_paid) {
                $record->cost = $request->cost;
                $record->validity = $request->validity;
            }

            $record->publish_results_immediately
                = $request->publish_results_immediately;
            $record->publish_results_immediately
                = 1;
            $record->start_date = date("Y-m-d H:i", strtotime($request->start_date));

            $record->end_date = date("Y-m-d H:i", strtotime($request->end_date));
            if (!$request->negative_mark) {
                $record->having_negative_mark = 0;
            }

            $record->description = $request->description;
            $record->record_updated_by = Auth::user()->id;
            $record->applicable_to_specific = $request->selected_list != null ? 1 : 0;
            $record->subject_id = $request->subject_id;
            $record->update_stamp($request);
            $record->save();
            /*if ($request->applicable_to_specific) {*/
            $quizApplicability = new App\QuizApplicability();
            $quizApplicability->updateApplicability($record->id, $request->selected_list);
            /* }*/

            flash(getPhrase('success'), getPhrase('record_updated_successfully'), 'success');
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            if (getSetting('show_foreign_key_constraint', 'module')) {

                flash(getPhrase('Ooops'), $e->getMessage(), 'error');
            } else {
                flash(getPhrase('Ooops'), getPhrase('improper_data_in_the_question'), 'error');
            }
        }
        if ($type == 'offline') {
            return redirect(URL_OFFLINE_EXAMS);
        } else {
            return redirect()->back();
        }
    }

    /**
     * This method adds record to DB
     * @param  Request $request [Request Object]
     * @return void
     */
    public function store(Request $request)
    {
        if (!checkRole(getUserGrade(3))) {
            prepareBlockUserMessage();
            return back();
        }

        $rules = [
            'title' => 'bail|required|max:60',

            'dueration' => 'bail|required|integer',
        ];
        $record = new Quiz();
        $type = $request->type;

        $instructions_page_id = 0;
        $pass_percentage = 0;
        $record->is_paid = 0;
        $record->course_id = $request->course_id;
        $record->negative_mark = 0;
        $record->type = $request->type;
        $record->instructions_page_id = App\Instruction::first()->id;

        if ($type == 'online') {

            $rules['category_id'] = 'bail|required|integer';
            $rules['instructions_page_id'] = 'bail|required|integer';
            $rules['pass_percentage'] = 'bail|required|integer|max:100|min:1';
            $instructions_page_id = $request->instructions_page_id;
            $pass_percentage = $request->pass_percentage;
            $record->is_paid = $request->is_paid;
            $record->type = $request->type;
            $record->having_negative_mark = 1;
            $record->negative_mark = $request->negative_mark;
            $record->instructions_page_id = $request->instructions_page_id;
            $record->category_id = $request->category_id;


        } else {

            $rules['category_id'] = 'bail|required|integer';
            $record->offline_quiz_category_id = $request->category_id;
        }


        $this->validate($request, $rules);



            $name = $request->title;
            $record->title = $name;
            $record->slug = $record->makeSlug($name, true);

            $record->dueration = $request->dueration;
            $record->total_marks = $request->total_marks;
            $record->pass_percentage = $request->pass_percentage;
            $record->tags = '';

            $record->cost = 0;
            $record->validity = -1;
            if ($record->is_paid) {
                $record->cost = $request->cost;
                $record->validity = $request->validity;
            }

            $record->publish_results_immediately
                = $request->publish_results_immediately;
            $record->publish_results_immediately
                = 1;
            $record->start_date = date("Y-m-d H:i:s", strtotime($request->start_date));
            $record->end_date = date("Y-m-d H:i:s", strtotime($request->end_date));
            if (!$request->negative_mark) {
                $record->having_negative_mark = 0;
            }

            $record->entry_option=$request->entry_option;
            $record->description = $request->description;
            $record->record_updated_by = Auth::user()->id;
            $record->applicable_to_specific = $request->applicable_to_specific;
            $record->subject_id = $request->subject_id;
            $record->user_stamp($request);
            $record->save();

            $quizApplicability = new App\QuizApplicability();
            if ($record->applicable_to_specific) {
                $quizApplicability->updateApplicability($record->id, $request->selected_list);
            }

            flash(getPhrase('success'), getPhrase('record_added_successfully'), 'success');

        if ($type == 'offline') {
            return redirect(URL_OFFLINE_EXAMS);
        } else {
            return redirect(URL_QUIZZES);
        }
    }

    /**
     * Delete Record based on the provided slug
     * @param  [string] $slug [unique slug]
     * @return Boolean
     */
    public function delete($slug)
    {
        if (!checkRole(getUserGrade(3))) {
            prepareBlockUserMessage();
            return back();
        }
        /**
         * Delete the questions associated with this quiz first
         * Delete the quiz
         * @var [type]
         */
        $record = Quiz::where('slug', $slug)->first();
        try {

            if (!env('DEMO_MODE')) {
                $record->delete();
            }
            $response['status'] = 1;
            $response['message'] = getPhrase('record_deleted_successfully');
        } catch (Exception $e) {
            $response['status'] = 0;
            if (getSetting('show_foreign_key_constraint', 'module')) {
                $response['message'] = $e->getMessage();
            } else {
                $response['message'] = getPhrase('this_record_is_in_use_in_other_modules');
            }
        }
        return json_encode($response);

    }

    public function quiz_results()
    {
        if (!checkRole(getUserGrade(19))) {
            prepareBlockUserMessage();
            return back();
        }
        $data['active_class'] = 'exams';
        $user = getUserRecord();
        $data['role_name'] = getRoleData($user->role_id);
        $data['layout'] = getLayout();
        $data['title'] = getPhrase('Report_of_quiz_results');
        $data['academic_years'] = addSelectToList(\App\Academic::pluck('academic_year_title', 'id'));
        return view('exams.quiz_results.list',$data);
    }

    public function report_result(Request $request)
    {
		if(strpos($request->current_quiz_id, 'undefined')){
		flash(getPhrase('Ooops'), getPhrase("page_not_found"), 'error');
		return back()->withInput();
		}
        if (!checkRole(getUserGrade(19))) {
            prepareBlockUserMessage();
            return back();
        }
        $data['title'] = getPhrase('Report_of_quiz_results');
        $subject_id = App\CourseSubject::where('id',$request->course_subject_id)->first()->subject_id;
        $staff_id   = App\CourseSubject::where('id',$request->course_subject_id)->first()->staff_id;
        $students = App\Student::where('course_id', $request->class_id)
                                 ->select('user_id as user')
                                 ->get();

        $result = array();
        foreach ($students as $student){
            $isexist =  App\QuizResult::join('users','users.id','=','quizresults.user_id')
                ->select('users.id','quizresults.id as result_id','users.name as name','quizresults.total_marks','quizresults.marks_obtained')
                ->where('quiz_id',$request->current_quiz_id)
                ->where('quizresults.user_id',$student->user)
                ->orderBy('quizresults.id', 'desc')->first();
                if($isexist) {
                  $result[$student->user] = $isexist;
                } else {
                  $result[$student->user] = App\User::where('id',$student->user)->first()->name;
                }
        }

        $data['results'] = $result;
        //return $request->all();
        $data['print_year']    = App\Academic::where('id',$request->year_id)->first()->academic_year_title;
        $data['print_term']    = SemesterName($request->sem_id);
        $data['print_course']  = App\Course::where('id',$request->course_id)->first()->course_title;
        $data['print_class' ]  = App\Course::where('id',$request->class_id)->first()->course_title;

        $data['print_quiz']    = Quiz::where('id',$request->current_quiz_id)->first()->title;
        $data['print_subject'] = Subject::where('id',$subject_id)->first()->subject_title;
        $data['max_mark']      = Quiz::where('id',$request->current_quiz_id)->first()->total_marks;
		if($staff_id>0)
        $data['teacher_name']  = App\User::where('id',$staff_id)->first()->name;
         else
			 $data['teacher_name'] ='';

        return view('exams.quiz_results.report_view',$data);
    }

    public function getReturnUrl()
    {
        return URL_QUIZZES;
    }


    /**
     * Returns the list of subjects based on the requested subject
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getSubjectData(Request $request)
    {

        $subject_id = $request->subject_id;
        $subject = Subject::where('id', '=', $subject_id)->first();
        $topics = App\Topic::where('subject_id',$subject_id)
            ->where('parent_id', '=', '0')
            ->select(['topic_name', 'id'])
            ->get();
        
        $questions =QuestionBank::join('topics','topics.id','=','questionbank.topic_id')
            ->where('questionbank.subject_id',$subject_id)
            ->where('questionbank.course_id',$request->course_id)
           
            ->where('questionbank.academic_id',$request->academic_id)
            ->where('questionbank.sem_id',$request->sem_id)
            ->select([
            'questionbank.id',
            'questionbank.subject_id',
            'questionbank.topic_id',
            'questionbank.question_type',
            'questionbank.question',
            'questionbank.marks',
            'questionbank.difficulty_level',
            'questionbank.status',
            'topics.topic_name'
        ]);//->get();
        if($request->topic_id!="" && $request->topic_id!=null)
        {
        $questions = $questions->where('questionbank.topic_id',$request->topic_id);
        $parenttopics = App\Topic::where('parent_id',$request->topic_id)->select('id')->get();
        $questions = $questions->orWhereIn('questionbank.topic_id',$parenttopics);
        }

        $result= $questions->get();
        return json_encode(array('topics' => $topics, 'questions' => $result, 'subject' => $subject));
    }

    /**
     * Updates the questions in a selected quiz
     * @param  [type] $slug [description]
     * @return [type]       [description]
     */
    public function updateQuestions($slug)
    {
        if (!checkRole(getUserGrade(3))) {
            prepareBlockUserMessage();
            return back();
        }

        /**
         * Get the Quiz Id with the slug
         * Get the available questions from questionbank_quizzes table
         * Load view with this data
         */
        $record = Quiz::getRecordWithSlug($slug);
        $data['record'] = $record;
        $data['record']['details'] = getSubjectDetails($record->subject_id);

        $data['active_class'] = 'exams';
        $data['right_bar'] = true;
        $data['right_bar_path'] = 'exams.quiz.right-bar-update-questions';

        $data['settings'] = false;
        $previous_questions = array();
        if ($record->total_questions > 0) {
            $questions = DB::table('questionbank_quizzes')
                ->where('quize_id', '=', $record->id)
                ->get();
            foreach ($questions as $question) {
                $temp = array();
                $temp['id'] = $question->subject_id . $question->questionbank_id;
                $temp['subject_id'] = $question->subject_id;
                $temp['question_id'] = $question->questionbank_id;
                $temp['marks'] = $question->marks;

                $question_details = QuestionBank::find($question->questionbank_id);
                $subject = $question_details->subject;

                $temp['topic_id'] = $question_details->topic_id;
                $temp['topic_name'] = App\Topic::where('id',$question_details->topic_id)->first()->topic_name;
                $temp['question'] = $question_details->question;
                $temp['question_type'] = $question_details->question_type;
                $temp['difficulty_level'] = $question_details->difficulty_level;
                $temp['subject_title'] = $subject->subject_title;
                array_push($previous_questions, $temp);
            }
            $settings['questions'] = $previous_questions;
            $settings['total_marks'] = $record->total_marks;
            $data['settings'] = json_encode($settings);
        }


        if(Auth::user()->role_id == 3){
            $subjects = App\Subject::join('subjectpreferences','subjects.id','=','subjectpreferences.subject_id')->select('subjects.id','subjects.subject_title')->where('user_id','=',Auth::user()->id)->get();
            $data['subjects']       	= array_pluck($subjects, 'subject_title', 'id');
        }else{    	$data['subjects']       	= array_pluck(App\Subject::all(), 'subject_title', 'id');  }

        $data['title'] = getPhrase('update_questions_for') . ' ' . $record->title;
        $data['module_helper'] = getModuleHelper('update-questions-in-quiz');
        return view('exams.quiz.update-questions', $data);

    }

    public function storeQuestions(Request $request, $slug)
    {

        if (!checkRole(getUserGrade(3))) {
            prepareBlockUserMessage();
            return back();
        }

        $quiz = Quiz::getRecordWithSlug($slug);

        $quiz_id = $quiz->id;
        $questions = json_decode($request->saved_questions);
        $marks = 0;
        $questions_to_update = array();
        foreach ($questions as $question) {
            $temp = array();
            $temp['subject_id'] = $question->subject_id;
            $temp['questionbank_id'] = $question->question_id;
            $temp['quize_id'] = $quiz_id;
            $temp['marks'] = $question->marks;
            $marks += $question->marks;
            array_push($questions_to_update, $temp);
        }
        $total_questions = count($questions_to_update);
        //Clear all previous questions
        if (!env('DEMO_MODE')) {
            DB::statement("delete from questionbank_quizzes where quize_id = '$quiz_id'");
        }
        //Insert New Questions
        DB::table('questionbank_quizzes')->insert($questions_to_update);
        $quiz->total_questions = $total_questions;
        $quiz->total_marks = $marks;
        $quiz->update_stamp($request);
        $quiz->save();
        flash(getPhrase('success'), getPhrase('record_updated_successfully'), 'success');
        return redirect(URL_QUIZ_UPDATE_QUESTIONS . $slug);
    }




}
