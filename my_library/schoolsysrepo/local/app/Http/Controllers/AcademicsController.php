<?php

namespace App\Http\Controllers;

use App;
use App\Academic;
use DB;
use Exception;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class AcademicsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Fee categories listing method
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        $data['active_class'] = 'master_settings';
        $data['title'] = getPhrase('academic_years');
        $data['layout'] = getLayout();
        $data['module_helper'] = getModuleHelper('academic-year');
        return view('mastersettings.academics.list', $data);
    }

    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatable()
    {
        DB::statement(DB::raw('set @rownum=0'));

        $records = Academic::select(['id', 'academic_year_title', 'academic_start_date', 'academic_end_date','total_semesters','slug','created_by_user','updated_by_user','created_by_ip','updated_by_ip','created_at','updated_at']);

        return Datatables::of($records)
            ->addColumn('action', function ($records) {
                $records->created_by_user_name = App\User::get_user_name($records->created_by_user);
                $records->updated_by_user_name = App\User::get_user_name($records->updated_by_user);
                $view = "<li><a onclick='pop_it($records)'><i class=\"fa fa-eye\"></i>".getPhrase('view_record_history')."</a></li>";
                return '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                        <li><a href="' . URL_MASTERSETTINGS_ACADEMICS_COURSES . $records->slug . '"><i class="fa fa-book"></i>' . getPhrase("allocate_courses") . '</a></li>

                            <li><a href="' . URL_MASTERSETTINGS_ACADEMICS_EDIT . $records->slug . '"><i class="fa fa-pencil"></i>' . getPhrase("edit") . '</a></li>
                            '.$view.'
                            <li><a href="javascript:void(0);" onclick="deleteRecord(\'' . $records->slug . '\');"><i class="fa fa-trash"></i>' . getPhrase("delete") . '</a></li>
                        </ul>
                    </div>';
            })
            ->editColumn('academic_year_title', function ($records) {
                return '<a href="' . URL_MASTERSETTINGS_ACADEMICS_COURSES . $records->slug . '">' . $records->academic_year_title . ' (' . $records->id . ')' . '</a>';
            })
            ->removeColumn('id')
            ->removeColumn('slug')
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
        $data['title'] = getPhrase('add_academic');
        $data['layout'] = getLayout();
        $data['module_helper'] = getModuleHelper('add-academic');
        return view('mastersettings.academics.add-edit', $data);
    }

    /**
     * This method loads the edit view based on unique slug provided by user
     * @param  [string] $slug [unique slug of the record]
     * @return [view with record]
     */
    public function edit($slug)
    {
        
        $record = Academic::where('slug', $slug)->get()->first();
        
        $data['record'] = $record;
        $relatedSemesters = App\AcademicSemester::where('academic_id', $record->id)->get();
        $data['relatedSemesters'] = $relatedSemesters;
        $data['active_class'] = 'master_settings';
        $data['title'] = getPhrase('edit_academic');
        $data['layout'] = getLayout();
        $data['module_helper'] = getModuleHelper('add-academic');
        return view('mastersettings.academics.add-edit', $data);
    }

    /**
     * Update record based on slug and reuqest
     * @param  Request $request [Request Object]
     * @param  [type]  $slug    [Unique Slug]
     * @return void
     */
    public function update(Request $request, $slug)
    {

        $record = Academic::where('slug', $slug)->get()->first();
        if (is_array($request->semester_start_date) and is_array($request->semester_end_date)) {
            $start_end = array_combine($request->semester_start_date, $request->semester_end_date);
        }else
        {
            $start_end=array();
        }
        $this->validate($request, [
            'academic_year_title' => 'bail|required|max:20|unique:academics,academic_year_title,' . $record->id,
            'academic_start_date' => 'required',
            'academic_end_date' => 'required',
            /*'current_semester' => 'required',*/
        ]);
        //set current semester for all child courses of academic year
        $coursesInAcademic = App\AcademicCourse::where('academic_id', $record->id)->select(['course_id'])->get();
        $courses = array();
        foreach ($coursesInAcademic as $item) {
            $courses[] = $item->course_id;
        }
        $coursesToUpdate = App\CourseSemister::whereIn('course_id', $courses)->get();
        foreach ($coursesToUpdate as $item) {
            $item->total_semisters = count($start_end) > 0 ? count($start_end) : 1;
            $item->update_stamp($request);
            $item->save();
        }
        //end
        $name = $request->academic_year_title;

        /**
         * Check if the title of the record is changed,
         * if changed update the slug value based on the new title
         */

        if ($name != $record->academic_year_title) {
            $record->slug = $record->makeSlug($name);
        }

        $record->academic_year_title = $name;
        $record->academic_start_date = $request->academic_start_date;
        $record->academic_end_date = $request->academic_end_date;
        $record->show_in_list = $request->show_in_list;
        /*$record->current_semester = $request->has('current_semester') ? (int)$request->current_semester : 1;*/
        $record->total_semesters = count($start_end) > 0 ? count($start_end) : 1;
        $record->update_stamp($request);
        $record->save();

        $academicToDelete=App\AcademicSemester::where('academic_id',$record->id)->delete();
        if (count($start_end) > 0) {
            $toBeDeleted = DB::delete('Delete from academics_semesters where academic_id = ?', [$record->id]);
            $start_end = array_combine($request->semester_start_date, $request->semester_end_date);
            $i = 1;
            foreach ($start_end as $start => $end) {
                $recordAcadSem = new App\AcademicSemester();
                $recordAcadSem->academic_id = $record->id;
                $recordAcadSem->sem_num = $i;
                $recordAcadSem->sem_start_date = $start;
                $recordAcadSem->sem_end_date = $end;
                $recordAcadSem->user_stamp($request);
                $recordAcadSem->save();
                $i++;
            }
        } else {
            $recordAcadSem = new App\AcademicSemester();
            $recordAcadSem->academic_id = $record->id;
            $recordAcadSem->sem_num = 1;
            $recordAcadSem->sem_start_date = $request->academic_start_date;
            $recordAcadSem->sem_end_date = $request->academic_end_date;
            $recordAcadSem->user_stamp($request);
            $recordAcadSem->save();
        }
        flash(getPhrase('success'), getPhrase('record_updated_successfully'), 'success');
        return redirect(URL_MASTERSETTINGS_ACADEMICS_EDIT.$record->slug);
    }

    /**
     * This method adds record to DB
     * @param  Request $request [Request Object]
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'academic_year_title' => 'bail|required|max:40|unique:academics,academic_year_title',
            'academic_start_date' => 'required',
            'academic_end_date' => 'required',
        ]);
        if ($request->semester_start_date != null and $request->semester_end_date != null) {
            $start_end = array_combine($request->semester_start_date, $request->semester_end_date);
        }else
        {
            $start_end=array();
        }
        $record = new Academic();
        $name = $request->academic_year_title;
        $record->academic_year_title = $name;
        $record->academic_start_date = $request->academic_start_date;
        $record->academic_end_date = $request->academic_end_date;
        /*$record->current_semester = (int)$request->current_semester;*/
        $record->total_semesters = count($start_end) > 0 ? count($start_end) : 1;
        $record->slug = $record->makeSlug($name);
        $record->show_in_list = $request->show_in_list;
        $record->user_stamp($request);
        $record->save();

        //academic semester
        if (count($start_end) > 0) {
            $start_end = array_combine($request->semester_start_date, $request->semester_end_date);
            $i = 1;
            foreach ($start_end as $start => $end) {
                $recordAcadSem = new App\AcademicSemester();
                $recordAcadSem->academic_id = $record->id;
                $recordAcadSem->sem_num = $i;
                $recordAcadSem->sem_start_date = $start;
                $recordAcadSem->sem_end_date = $end;
                $recordAcadSem->user_stamp($request);
                $recordAcadSem->save();
                $i++;
            }
        } else {
            $recordAcadSem = new App\AcademicSemester();
            $recordAcadSem->academic_id = $record->id;
            $recordAcadSem->sem_num = 1;
            $recordAcadSem->sem_start_date = $request->academic_start_date;
            $recordAcadSem->sem_end_date = $request->academic_end_date;
            $recordAcadSem->user_stamp($request);
            $recordAcadSem->save();
        }
        flash(getPhrase('success'), getPhrase('record_added_successfully'), 'success');
        return redirect(URL_MASTERSETTINGS_ACADEMICS_ADD);
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
                Academic::where('slug', $slug)->delete();
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

    public function getAcademics()
    {
        return array_pluck(Academic::all(), 'academic_year_title', 'id');
    }
    public function getSemisters(Request $request)
    {
        $instance=App\AcademicSemester::where('academic_id',$request->academic_id)->get(['sem_num']);
        return $instance;
    }
}
