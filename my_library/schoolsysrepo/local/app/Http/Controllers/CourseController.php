<?php

namespace App\Http\Controllers;

use App;
use App\Course;
use DB;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\Datatables\Datatables;

class CourseController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * This method will display the courses dashboard
     */

    public function coursesdashboard()
    {

        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }

        $data['active_class'] = 'master_settings';
        $data['title'] = getPhrase('master_setup_dashboard');
        $data['layout'] = getLayout();
        $data['module_helper'] = getModuleHelper('mastersetup-dashboard');
        return view('courses.dashboard', $data);

    }

    /**
     * Course listing method
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
       
        $data['active_class'] = 'master_settings';
        $data['title'] = getPhrase('list_of_courses');
        $data['layout'] = getLayout();
        $data['module_helper'] = getModuleHelper('courses-list');
        return view('mastersettings.course.list', $data);
    }

    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatable()
    {
        Session::set('i', 0);
        $records = Course::withoutGlobalScope(\App\Scopes\CategoryScope::class)->where('category_id',Auth::user()->category_id)->select([
            'parent_id',
            'category_id',
            'course_title',
            'slug',
            'id',
            'created_by_user','updated_by_user','created_by_ip','updated_by_ip','created_at','updated_at'])
           ->orderBy('id', 'asc');
        return Datatables::of($records)
            ->addColumn('action', function ($records) {
                $records->created_by_user_name = App\User::get_user_name($records->created_by_user);
                $records->updated_by_user_name = App\User::get_user_name($records->updated_by_user);
                $view = "<li><a onclick='pop_it($records)'><i class=\"fa fa-eye\"></i>".getPhrase('view_record_history')."</a></li>";

                $editSemister = '';
               /* if ($records) {
                    if ($records->is_having_semister) {
                        $editSemister = '<li><a href="' . URL_MASTERSETTINGS_COURSE_EDIT_SEMISTER . '/' . $records->slug . '"><i class="icon-packages"></i>' . getPhrase("edit_semisters") . '</a></li>';
                    }
                }*/
                return '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                            <li><a href="' . URL_MASTERSETTINGS_COURSE_EDIT . $records->slug . '"><i class="fa fa-pencil"></i>' . getPhrase("edit") . '</a></li>
                            ' . $editSemister . '
                            '.$view.'
                            <li><a href="javascript:void(0);" onclick="deleteRecord(\'' . $records->slug . '\');"><i class="fa fa-trash"></i>' . getPhrase("delete") . '</a></li>
                            
                        </ul>
                    </div>';
            })
            ->editColumn('course_title', function ($records) {
                if ($records->parent_id != 0) {
                    return $records->course_title . ' (' . Course::getCourseRecord($records->parent_id)['course_title'] . ')';
                }
                return $records->course_title;
            })
            ->editColumn('category_id', function ($records) {
              return get_category_name($records->category_id);
            })
            ->editColumn('parent_id', function ($records) {
                $val = Session::set('i', Session::get('i') + 1);
                return Session::get('i');

            })

            ->removeColumn('id')
            ->removeColumn('slug')
            ->removeColumn('is_having_elective_subjects')
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
        $data['record'] = false;
        $data['active_class'] = 'master_settings';
        $list = Course::getCourses(0);
        $data['course_parent_list'] = array_pluck($list, 'course_title', 'id');
        $data['course_parent_list'][0] = 'Parent';
        $data['title'] = getPhrase('add_course');
        $data['layout'] = getLayout();
        $data['module_helper'] = getModuleHelper('create-course');

        return view('mastersettings.course.add-edit', $data);
    }

    /**
     * This method loads the edit view based on unique slug provided by user
     * @param  [string] $slug [unique slug of the record]
     * @return [view with record]
     */
    public function edit($slug)
    {
        $record = Course::withoutGlobalScope(\App\Scopes\CategoryScope::class)->where('slug', $slug)->get()->first();
        $data['record'] = $record;
        $list = Course::getCourses(0);
        $data['course_parent_list'] = array_pluck($list, 'course_title', 'id');
        $data['course_parent_list'][0] = 'Parent';
        $data['active_class'] = 'master_settings';
        $data['title'] = getPhrase('edit_course');
        $data['layout'] = getLayout();
        return view('mastersettings.course.add-edit', $data);
    }

    /**
     * Update record based on slug and reuqest
     * @param  Request $request [Request Object]
     * @param  [type]  $slug    [Unique Slug]
     * @return void
     */
    public function update(Request $request, $slug)
    {

        $record = Course::where('slug', $slug)->get()->first();

        $this->validate($request, [
            'course_title' => 'bail|required|max:60',
            'parent_id' => 'bail|required|integer',
            'course_dueration' => 'bail|required_if:parent_id,!= 0',
        ]);

        $name = $request->course_title;
        
        /**
         * Check if the title of the record is changed,
         * if changed update the slug value based on the new title
         */
        if ($name != $record->course_title) {
            $record->slug = $record->makeSlug($name, true);
        }


        /**
         * Check if semisters or years information is changed
         * if changed, modify the coursesemisters table
         */
        if ($record->is_having_semister) {
            if ($request->is_having_semister) {
                if ($request->course_dueration != $record->course_dueration) {
                    //Reset semisters
                    //1 Delete all
                    //2 Create as per the dueration
                    $record->course_dueration = $request->course_dueration;
                    $this->createSemisters($record,$request, true);
                }
            } else {
                /**
                 * Removed semisters option, so delete the records
                 */
                $this->resetSemisters($record);
            }
        } else {
            if ($request->is_having_semister) {
                /**
                 * Currently added semisters option, so crate semister records
                 */
                $record->course_dueration = $request->course_dueration;
                $this->createSemisters($record,$request);
            }
        }

        $record->course_title = $name;
        $record->parent_id = $request->parent_id;
        $record->category_id = $request->category_id;
        if($request->graduated_course=="on")
        $record->graduated_course=1;
        else
        $record->graduated_course=0;
        $record->description = $request->description;
        $record->update_stamp($request);
        $record->save();


        flash(getPhrase('success'), getPhrase('record_updated_successfully'), 'success');
        
        return redirect(URL_MASTERSETTINGS_COURSE_EDIT.$record->slug);
    }

    /**
     * Creates the semisters based on the dueration
     * @param  [type]  $record [request/database row object]
     * @param  boolean $reset [If true, first delets the semisters based on course Id]
     * @return [type]          [description]
     */
    public function createSemisters($record,Request $request, $reset = false)
    {
        if ($reset) {
            $this->resetSemisters($record);
        }

        for ($year = 1; $year <= $record->course_dueration; $year++) {
            $coursesem = new App\CourseSemister();
            $coursesem->course_id = $record->id;
            $coursesem->year = $year;
            $coursesem->total_semisters = 2;
            $coursesem->current_semester = 0;
            $coursesem->user_stamp($request);
            $coursesem->save();
        }
    }

    /**
     * Delets the records with the sent record
     * @param  [type] $record [request/database row object]
     * @return [type]         [description]
     */
    public function resetSemisters($record)
    {
        if (!env('DEMO_MODE')) {
            DB::table('coursesemisters')->where('course_id', '=', $record->id)->delete();
        }
    }

    /**
     * This method adds record to DB
     * @param  Request $request [Request Object]
     * @return void
     */
    public function store(Request $request)
    {
        $rules = [
            'course_title' => 'bail|required|max:60',
            'parent_id' => 'bail|required|integer'
        ];
        $this->validate($request, $rules);

        $flag = false;
        if($request->parent_id != 0){
            $first_child = Course::where('parent_id',$request->parent_id)->first();
            if($first_child){
                $flag = true;
            }
        }

        $record = new Course();
        $name = $request->course_title;
        $record->course_title = $name;
        $record->slug = $record->makeSlug($name, true);
        $record->parent_id = $request->parent_id;
        $record->course_dueration = 1;
        $record->is_having_semister = 1;
        $record->category_id = $request->category_id;
        $record->description = $request->description;

        $current_academic_id = new App\Academic();
        $current_academic_id = $current_academic_id->getCurrentAcademic()->id;

        if($flag == true){
            $subjects =  App\CourseSubject::where('course_id',$first_child->id)->where('academic_id',$current_academic_id)->get();
        }else{
            $subjects = [];
        }
        if ($request->parent_id == 0) {
            $rules = [
                'course_dueration' => 'bail|required|integer'
            ];
        }

        $record->user_stamp($request);
        $record->save();

        foreach ($subjects as $subject){
            $course_subject = new App\CourseSubject();
            $course_subject->slug = $course_subject->makeSlug(getHashCode());
            $course_subject->academic_id = $current_academic_id;
            $course_subject->course_parent_id = $request->parent_id;
            $course_subject->course_id = $record->id;
            $course_subject->year = 1;
            $course_subject->sessions_needed = 0;
            $course_subject->semister = $subject->semister;
            $course_subject->subject_id = $subject->subject_id;
            $course_subject->user_stamp($request);
            $course_subject->save();
        }



        if ($record->is_having_semister) {
            $this->createSemisters($record,$request);
        }

        flash(getPhrase('success'), getPhrase('record_added_successfully'), 'success');
        return redirect(URL_MASTERSETTINGS_COURSE_ADD);
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
                $is_eligible_to_delete = true;
                $record = Course::where('slug', $slug)->first();
                /*if ($record->parent_id == 0) {
                    // This course is a parent course, so check if any
                    // child courses available with this course id.
                    // If available, do not delete this course record
                    $count = Course::where('parent_id', '=', $record->id)->count();
                    if ($count > 0) {
                        $is_eligible_to_delete = false;
                    }
                }*/
                if ($is_eligible_to_delete) {
                    DB::table('courses')->where('parent_id', '=', $record->id)->delete();
                    $record->delete();
                    $response['status'] = 1;
                    $response['message'] = getPhrase('record_deleted_successfully');
                } else {
                    $response['status'] = 0;
                    $response['message'] = getPhrase('this_record_is_in_use_in_other_modules');
                }
            } else {
                $response['status'] = 1;
                $response['message'] = getPhrase('record_deleted_successfully');
            }
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


    /**
     * This method loads the edit view based on unique slug provided by user
     * @param  [string] $slug [unique slug of the record]
     * @return [view with record]
     */
    public function editSemisters($slug)
    {
        $record = Course::where('slug', $slug)->get()->first();
        /*$data['records'] = $record->semisters;*/
        $data['record_semester'] = $record->semisters->first();
        $data['active_class'] = 'master_settings';
        $data['title'] = getPhrase('edit_semister');
        $data['module_helper'] = getModuleHelper('edit-semister');
        return view('mastersettings.course.edit-semisters', $data);
    }

    /**
     * Update semisters information for the selected course
     * @param  Request $request [Contains the list of KEY Value Pairs]
     * @return [type]            [description]
     */
    public function updateSemisters(Request $request)
    {
        /*$input = $request->all();
        foreach ($input as $key => $val) {
            if (is_numeric($key)) {
                $rec = App\CourseSemister::find($key);
                $rec->total_semisters = $val;
                $rec->current_semester = $input['current_semester'];
                $rec->save();
            }
        }*/
        $rec = App\CourseSemister::find($request->course_semester);
        $rec->current_semester = $request->current_semester;
        $rec->update_stamp($request);
        $rec->save();
        flash(getPhrase('success'), getPhrase('record_updated_successfully'), 'success');
        return redirect('mastersettings/course');
    }
}
