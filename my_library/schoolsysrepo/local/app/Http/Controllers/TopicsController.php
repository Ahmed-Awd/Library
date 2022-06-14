<?php

namespace App\Http\Controllers;

use App;
use App\Subject;
use App\Topic;
use DB;
use Excel;
use Auth;
use Illuminate\Http\Request;
use Input;
use Yajra\Datatables\Datatables;

class TopicsController extends Controller
{
    public $excel_data = '';

    public function __construct()
    {
        $this->middleware('auth');
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
        $user_record = Auth::user();
        $staff=$user_record->staff()->first();


        $data['role'] ='';
        if ($user_record->role_id == getRoleData('staff')) {
            $data['role'] ='staff';
            $data['course_id'] =$staff->course_id;
            $data['course_parent_id'] =$staff->course_parent_id;
        }
        //dd($staff->course_parent_id);
        $data['active_class'] = 'master_settings';
        $data['title'] = getPhrase('topics_list');
        $data['module_helper'] = getModuleHelper('topics-list');
        return view('mastersettings.topics.main_list', $data);
    }

    public function indexList($year,$sem,$course,$subject)
    {
        if (!checkRole(getUserGrade(3))) {
            prepareBlockUserMessage();
            return back();
        }
        $user_record = Auth::user();
        $staff=$user_record->staff()->first();

        $data['role'] ='';
        if ($user_record->role_id == getRoleData('staff')) {
            $data['role'] ='staff';
            $data['course_id'] =$staff->course_id;
            $data['course_parent_id'] =$staff->course_parent_id;
        }
        $data['active_class'] = 'master_settings';
        $data['title'] = getPhrase('topics_list');
        $data['module_helper'] = getModuleHelper('topics-list');
        $data['year'] = $year;
        $data['sem'] = $sem;
        $data['course'] = $course;
        $data['subject'] = $subject;
        return view('mastersettings.topics.list', $data);
    }

    public function staffindex()
    {
        if (!checkRole(getUserGrade(3))) {
            prepareBlockUserMessage();
            return back();
        }
        $user_record = Auth::user();
        $staff=$user_record->staff()->first();

        $data['layout'] = getLayout();
        $data['role'] ='';
        if ($user_record->role_id == getRoleData('staff')) {
            $data['role'] ='staff';
            $data['course_id'] =$staff->course_id;
            $data['course_parent_id'] =$staff->course_parent_id;
        }
        //dd($staff->course_parent_id);
        $data['active_class'] = 'master_settings';
        $data['title'] = getPhrase('topics_list');
        $data['module_helper'] = getModuleHelper('topics-list');
        return view('staff.topics.main_list', $data);
    }

    public function staffindexList($year,$sem,$course,$subject)
    {
        if (!checkRole(getUserGrade(3))) {
            prepareBlockUserMessage();
            return back();
        }
        $user_record = Auth::user();
        $staff=$user_record->staff()->first();

        $data['layout'] = getLayout();
        $data['role'] ='';
        if ($user_record->role_id == getRoleData('staff')) {
            $data['role'] ='staff';
            $data['course_id'] =$staff->course_id;
            $data['course_parent_id'] =$staff->course_parent_id;
        }
        $data['active_class'] = 'master_settings';
        $data['title'] = getPhrase('topics_list');
        $data['module_helper'] = getModuleHelper('topics-list');
        $data['year'] = $year;
        $data['sem'] = $sem;
        $data['course'] = $course;
        $data['subject'] = $subject;
        return view('staff.topics.list', $data);
    }
    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatable($year,$sem,$course,$subject)
    {
        if (!checkRole(getUserGrade(3))) {
            prepareBlockUserMessage();
            return back();
        }

        $subject_id = App\Subject::where('slug',$subject)->pluck('id')->first();
        $year_id    = App\Academic::where('slug',$year)->pluck('id')->first();
        $course_id  = App\Course::where('slug',$course)->pluck('id')->first();

        
        $records = Topic::join('subjects', 'topics.subject_id', '=', 'subjects.id')
            ->select([
                'subjects.subject_title',
                'semester_num',
                'course_id',
                'parent_id',
                'topic_name',
                'description',
                'topics.slug',
                'topics.id',
                'topics.created_by_user','topics.updated_by_user','topics.created_by_ip','topics.updated_by_ip','topics.created_at','topics.updated_at'
            ])
            ->where('academic_id',$year_id)
            ->where('semester_num',$sem)
            ->where('course_id',$course_id)
            ->where('subject_id',$subject_id);
            // ->orderBy('updated_at', 'desc');

        return Datatables::of($records)
            ->addColumn('action', function ($records) {

                $records->created_by_user_name = App\User::get_user_name($records->created_by_user);
                $records->updated_by_user_name = App\User::get_user_name($records->updated_by_user);
                $view = "<li><a onclick='pop_it($records)'><i class=\"fa fa-eye\"></i>".getPhrase('view_record_history')."</a></li>";

                $link_data = '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                            <li><a href="' . URL_TOPICS_EDIT  . $records->slug . '"><i class="fa fa-pencil"></i>' . getPhrase("edit") . '</a></li>'.$view;


                $temp = '';
                if (checkRole(getUserGrade(1))) {
                    $temp .= ' <li><a href="javascript:void(0);" onclick="deleteRecord(\'' . $records->slug . '\');"><i class="fa fa-trash"></i>' . getPhrase("delete") . '</a></li>';
                }
                $temp .= '</ul></div>';
                $link_data .= $temp;

                if (checkRole('staff')) {
                      $link_data = '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                            <li><a href="' . URL_STAFF_TOPICS_EDIT . '/' . $records->slug . '"><i class="fa fa-pencil"></i>' . getPhrase("edit") . '</a></li>'.$view;


                $temp = '';

                    $temp .= ' <li><a href="javascript:void(0);" onclick="deleteRecord(\'' . $records->slug . '\');"><i class="fa fa-trash"></i>' . getPhrase("delete") . '</a></li>';

                $temp .= '</ul></div>';
                $link_data .= $temp;
                }
                return $link_data;
            })
            ->editColumn('topic_name', function ($records) {
                return $records->topic_name . ' (' . $records->id . ')';
            })
            ->editColumn('parent_id', function ($records) {
                return ($records->parent_id == 0) ? '<i class="fa fa-check text-success"></i>' :
                    Topic::getParentRecord($records->parent_id)->topic_name;
            })
            ->editColumn('course_id', function ($records) {
                return getCourseName($records->course_id);
            })
            ->removeColumn('id')
            ->removeColumn('slug')
            ->removeColumn('updated_at')
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
        $data['record_course_id'] = "17";
        $data['active_class'] = 'master_settings';
        $data['parent_topics'] = array();
        $list = App\Subject::all();

        $subjects = array_pluck($list, 'subject_title', 'id');
        $data['subjects'] = array('' => getPhrase('select')) + $subjects;
        $data['parent_topics'][0] = getPhrase('select');
        $data['title'] = getPhrase('add_topic');
        $data['module_helper'] = getModuleHelper('create-topics');
        $data['recored_sem']   = 1;
        $data['total_semesters'] = get_sesmters();
        $data['recored_subject']  = 0;
        $data['recored_parent']   = 0;
        $data['layout'] = getLayout();
        return view('staff.topics.add-edit', $data);
        //return redirect(URL_TOPICS_ADD);
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
        $record = Topic::where('slug', $slug)->get()->first();
        if ($isValid = $this->isValidRecord($record)) {
            return redirect($isValid);
        }

        $data['record'] = $record;
        $data['record_course_id'] = $record->course_id;
        $data['recored_sem']      = $record->semester_num;
        $data['recored_subject']  = $record->subject_id;
        $data['recored_parent']   = $record->parent_id;
//        return $data;
        $list = App\Subject::all();
        $data['subjects'] = array_pluck($list, 'subject_title', 'id');
        $data['parent_topics'] = array_pluck(Topic::getTopics($record->subject_id, 0), 'topic_name', 'id');
        $data['parent_topics'][0] = 'Parent';
        $data['active_class'] = 'master_settings';
        $data['title'] = getPhrase('edit_topic');
        $data['module_helper'] = getModuleHelper('create-topics');
        $data['total_semesters'] = get_sesmters();
        $data['layout'] = getLayout();
        if (checkRole('staff')) {
            return view('staff.topics.add-edit', $data);
        }
        return view('mastersettings.topics.add-edit', $data);
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

        $record = Topic::where('slug', $slug)->get()->first();

        if ($isValid = $this->isValidRecord($record)) {
            return redirect($isValid);
        }

        $this->validate($request, [
            'subject_id' => 'bail|required',
            'parent_id' => 'bail|required',
            'topic_name' => 'bail|required',
        ]);

        $name = $request->topic_name;

        /**
         * Check if the title of the record is changed,
         * if changed update the slug value based on the new title
         */
        if ($name != $record->topic_name) {
            $record->slug = $record->makeSlug($name, true);
        }

        $record->topic_name = $name;
        $record->parent_id = $request->parent_id;
        $record->semester_num = $request->semesters;
        $record->academic_id  = $request->year;
        $record->subject_id = $request->subject_id;
        $record->course_id = $request->course_id;
        $record->description = '';
        if (isset($request->description)) {
            $record->description = $request->description;
        }
        $record->update_stamp($request);
        $record->save();

        flash(getPhrase('success'), getPhrase('record_updated_successfully'), 'success');
        if (checkRole('staff')) {
            return redirect(URL_STAFF_TOPICS);
        }
        return redirect(URL_TOPICS_EDIT.$record->slug);

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

        $this->validate($request, [
            'subject_id' => 'bail|required',
            'parent_id' => 'bail|required',
            'topic_name' => 'bail|required',

        ]);

        $record = new Topic();
        $name = $request->topic_name;
        $record->topic_name = $name;
        $record->slug = $record->makeSlug($name, true);
        $record->subject_id = $request->subject_id;
        $record->course_id = $request->course_id;
        $record->academic_id  = $request->year;
        $record->parent_id = $request->parent_id;
        $record->semester_num = $request->semesters;
        $record->description = $request->description;
        $record->user_stamp($request);
        $record->save();

        flash(getPhrase('success'), getPhrase('record_added_successfully'), 'success');
        if (checkRole('staff')) {
            return redirect(URL_STAFF_TOPICS);
        }
        return redirect(URL_TOPICS);
    }

    /**
     * Delete Record based on the provided slug
     * @param  [string] $slug [unique slug]
     * @return Boolean
     */
    public function delete($slug)
    {


        try {
            if (!env('DEMO_MODE')) {

                DB::statement("delete from topics where slug = '$slug'");
            }
            $response['status'] = 1;
            $response['message'] = getPhrase('record_deleted_successfully');
        } catch (\Illuminate\Database\QueryException $e) {
            $response['status'] = 0;
            if (getSetting('show_foreign_key_constraint', 'module')) {
                $response['message'] = $e->errorInfo;
            } else {
                $response['message'] = getPhrase('this_record_is_in_use_in_other_modules');
            }
        }
        return json_encode($response);
    }

    /**
     * Returns the parent topics based on selected subject
     * @param  [type] $subject_id [description]
     * @return [type]             [description]
     */
    public function getParentTopics($subject_id, Request $request)
    {
        if (!checkRole(getUserGrade(3))) {
            prepareBlockUserMessage();
            return back();
        }
        $semester = $request->semester;
        $list = Topic::getTopicsInSSpecificSemester($subject_id, $semester, 0);

        $parents = array();
        array_push($parents, array('id' => 0, 'text' => getPhrase('top_level')));

        foreach ($list as $key => $value) {
            $r = array('id' => $value->id, 'text' => $value->topic_name);
            array_push($parents, $r);
        }
        return json_encode($parents);
    }

    public function getReturnUrl()
    {
        return URL_TOPICS_LIST;
    }

    /**
     * Display a Import Users page
     *
     * @return Response
     */
    public function import()
    {
        if (!checkRole(getUserGrade(3))) {
            prepareBlockUserMessage();
            return back();
        }

        $data['records'] = false;
        $data['active_class'] = 'master_settings';
        $data['heading'] = getPhrase('topics');
        $data['title'] = getPhrase('import_topics');
        $data['layout'] = getLayout();
        $data['module_helper'] = getModuleHelper('import-topics');
        return view('mastersettings.topics.import.import', $data);
    }

    public function readExcel(Request $request)
    {
        ini_set('max_execution_time', 300);
        $rules = [
            'excel' => 'bail|required',
        ];
        $this->validate($request, $rules);

        if (!checkRole(getUserGrade(3))) {
            prepareBlockUserMessage();
            return back();
        }
        $success_list = [];
        $failed_list = [];
        try {
            if (Input::hasFile('excel')) {
                $path = Input::file('excel')->getRealPath();
                $data = Excel::load($path, function ($reader) {
                })->get();

                $all_records = array();
                $excel_record = array();
                $final_records = array();
                $isHavingDuplicate = 0;
                if (!empty($data) && $data->count()) {
                    foreach ($data as $key => $value) {
                        foreach ($value as $record) {
                            $all_records[] = $record;
                        }

                    }


                    /**
                     * 1) Validate the excel data by verifing the subject id validity and filter only valid recods
                     * 2) First Insert the parent topic records and capture the id of the parent
                     * 3) Get the list of child records, before insert the child record check the parent
                     *   id in the captured array or it exists in db
                     * 4) Insert only valid parent id records.
                     * 5) Maintain Failed and Success List with error messages.
                     *
                     * @var [type]
                     */
                    $processed_records = (object)$this->getParentAndChilds($all_records);


                    $parent_records = $processed_records->parent_records;
                    $child_records = $processed_records->child_records;
                    $failed_list = $processed_records->failed_records;
                    $success_list = $processed_records->success_records;



                    foreach ($child_records as $record) {
                        $record = $record;

                        $temp_parent_id = (int)$record->parent_id;

                        if (!array_key_exists($temp_parent_id, $parent_records) &&
                            !$this->isRecordExists('id', $temp_parent_id)) {
                            $temp['record'] = $record;
                            $temp['type'] = getPhrase('Invalid_Subject_Id');
                            $failed_list[count($failed_list)] = (object)$temp;
                            continue;
                        }
                        $record['parent_id'] = $parent_records[$record->parent_id];

                        if (!env('DEMO_MODE')) {
                            if ($this->pushToDb($record)) {
                                $success_list[] = $record;
                            }
                        }
                    }

                }
            }

            $data['failed_list'] = $failed_list;
            $data['success_list'] = $success_list;
            $this->excel_data['failed'] = $failed_list;
            $this->excel_data['success'] = $success_list;
            flash(getPhrase('success'), getPhrase('record_added_successfully'), 'success');
            $this->downloadExcel();
        } catch (\Illuminate\Database\QueryException $e) {
            if (getSetting('show_foreign_key_constraint', 'module')) {

                flash(getPhrase('Ooops'), $e->getMessage(), 'error');
            } else {
                flash(getPhrase('Ooops'), getPhrase('improper_sheet_uploaded'), 'error');
            }
        }
        // URL_USERS_IMPORT_REPORT
        $data['failed_list'] = $failed_list;
        $data['success_list'] = $success_list;
        $data['records'] = false;
        $data['layout'] = getLayout();
        $data['active_class'] = 'settings';
        $data['heading'] = getPhrase('users');
        $data['title'] = getPhrase('report');
        flash('success','record_added_successfully', 'success');
        return view('mastersettings.topics.import.import-result', $data);

    }

    /**
     * This method returns the list of childs and parents by filtering on condition
     * It also inserts the parent records to db and collects the ids of the parent
     * To place those for child records
     * @param  string $value [description]
     * @return [type]        [description]
     */
    public function getParentAndChilds($records)
    {

        $parent_records = [];
        $child_records = [];
        $failed_list = [];
        $success_list = [];

        foreach ($records as $record) {
            if (!$this->isValidSubject($record->subject_id)) {
                $temp['record'] = $record;
                $temp['type'] = getPhrase('invalid_subject_id');
                $failed_list[count($failed_list)] = (object)$temp;
                continue;
            }

            if (!$record->parent_id) {

                if (!$parent_records[(int)$record->id] = $this->pushToDb($record)) {
                    $temp['record'] = $record;
                    $temp['type'] = getPhrase('unknown_error_occurred');
                    $failed_list[count($failed_list)] = (object)$temp;
                    continue;
                }

                $success_list[] = $record;
            } else {
                $child_records[] = $record;
            }
        }

        return array(
            'parent_records' => $parent_records,
            'child_records' => $child_records,
            'failed_records' => $failed_list,
            'success_records' => $success_list
        );
    }

    public function isValidSubject($subject_id)
    {
        $subject_id = (int)$subject_id;
        return Subject::where('id', '=', $subject_id)->get()->count();
    }

    /**
     * This method inserts the array of records to db
     * @param  [type] $records [description]
     * @return [type]          [description]
     */
    public function pushToDb($request)
    {

        $topic = new Topic();
        $name = $request->topic_name;
        $topic->topic_name = $name;
        $topic->semester_num = $request->semester_num;
        $topic->course_id    = $request->course_id;
        $topic->academic_id  = $request->academic_id;
        $topic->parent_id = $request->parent_id;
        $topic->slug = $topic->makeSlug(getHashCode());
        $topic->subject_id = $request->subject_id;
        $topic->description = '';
        if ($request->description) {
            $topic->description = $request->description;
        }
        //$topic->user_stamp($request);
        $topic->save();
        return $topic->id;
    }

    /**
     * This method verifies if the record exists with the title or code
     * If Exists it returns true else it returns false
     * @param  [type]  $value [description]
     * @param  string $type [description]
     * @return boolean        [description]
     */
    public function isRecordExists($record_value, $type = 'id')
    {
        return Topic::where($type, '=', $record_value)->get()->count();
    }

    public function downloadExcel()
    {
        Excel::create('topics_report', function ($excel) {
            $excel->sheet('Failed', function ($sheet) {
                $sheet->row(1, array('Reason', 'topic_name','semester_num', 'parent_id', 'subject_id', 'description'));
                $data = $this->getFailedData();
                $cnt = 2;
                foreach ($data['failed'] as $data_item) {
                    $item = $data_item->record;
                    $sheet->appendRow($cnt++, array(
                        $data_item->type,
                        $item->topic_name,
                        $item->semester_num,
                        $item->parent_id,
                        $item->subject_id,
                        $item->description
                    ));
                }
            });

            $excel->sheet('Success', function ($sheet) {
                $sheet->row(1, array('topic_name', 'semester', 'parent_id', 'subject_id', 'description'));
                $data = $this->getFailedData();
                $cnt = 2;
                foreach ($data['success'] as $data_item) {
                    $item = $data_item;
                    $sheet->appendRow($cnt++,
                        array(
                            $item->topic_name,
                            $item->semester_num,
                            $item->parent_id,
                            $item->subject_id,
                            $item->description
                        ));
                }

            });

        })->download('xlsx');
    }

    public function getFailedData()
    {
        return $this->excel_data;
    }

}
