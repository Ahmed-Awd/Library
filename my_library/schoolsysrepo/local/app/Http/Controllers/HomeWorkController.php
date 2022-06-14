<?php

namespace App\Http\Controllers;

use App\HomeWork;
use App\HomeWorkReplay;
use App\HomeworkStudent;
use App\Staff;
use App\Student;
use Illuminate\Http\Request;
use App\Course;
use App\Subject;
use Yajra\Datatables\Datatables;
use App\Academic;
use App\AcademicSemester;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeWorkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!checkRole(getUserGrade(3))) {
            prepareBlockUserMessage();
            return back();
        }
        $data['active_class'] = 'homeworks';
        $data['title'] = getPhrase('HomeWork');
        $user = getUserRecord();
        $role = getRoleData($user->role_id);
        $data['role']=$role;
        if ($role == 'owner' || $role == 'admin') {
            $teachers = $this->getTeachers();
            $data['teachers'] = $teachers;
        }

        return view('home_work.list', $data);
    }

    public function showList($teacher,$course,$subjecti,$year,$sem)
    {
        if (!checkRole(getUserGrade(3))) {
            prepareBlockUserMessage();
            return back();
        }

        $subject = Subject::getRecordWithSlug($subjecti);
        $coursee  = Course::where('slug',$course)->first();

        $data['active_class'] = 'homeworks';

        $data['subject'] = $subject;
        $data['course']  = $coursee;
        $data['year']  = $year;
        $data['sem']  = $sem;
        if($teacher == "null"){
            $teacher = Auth::user()->slug;
        }
        $data['teacher'] = $teacher;
        $teacher_name = User::where('slug',$teacher)->first()->name;
        $data['title'] = $subject->subject_title . ' - ' . $coursee->course_title . ' - ' .$teacher_name .' - ' . getPhrase('HomeWorks');
        $data['module_helper'] = getModuleHelper('view-questions');

        return view('home_work.homework', $data);
    }

    public function getHomeworks($teacher,$course,$subject,$year,$sem)
    {
        $subject_id = Subject::where('slug',$subject)->pluck('id')->first();
        $course_id  = Course::where('slug',$course)->pluck('id')->first();
        $teacher_id = User::where('slug',$teacher)->pluck('id')->first();
        $records    = HomeWork::select(['id','slug','title','subject_id','course_parent_id','course_id','explanation','file','created_by_user','updated_by_user','created_by_ip','updated_by_ip','created_at'])
            ->where('subject_id',$subject_id)
            ->where('course_id',$course_id)
          //->whereRaw("find_in_set($course_id , coursesids)")
            ->where('staff_id',$teacher_id)->where('year',$year)->where('sem',$sem);
        return Datatables::of($records)
            ->addColumn('action', function ($records) {

                $records->created_by_user_name = User::get_user_name($records->created_by_user);
                $records->updated_by_user_name = User::get_user_name($records->updated_by_user);
                $view = "<li><a onclick='pop_it($records)'><i class=\"fa fa-eye\"></i>".getPhrase('view_record_history')."</a></li>";


                $link_data = '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                            <li><a href="'.URL_HOMEWORK_EDIT.'/'.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>';
                        $link_data.= '<li><a href="'.URL_HOMEWORK_STUDENTS.$records->slug.'"><i class="fa fa-child"></i>'.getPhrase('show_students').'</a></li>'.$view;
                $temp = '';
                if(checkRole(getUserGrade(3))) {
                    $temp .= '<li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->slug.'\');"><i class="fa fa-trash"></i>'. getPhrase("delete").'</a></li>';
                }

                $temp .='</ul> </div>';
                $link_data .= $temp;
                return $link_data;
            })
            ->editColumn('title', function ($records) {
                $count= HomeWorkReplay::join('homeworks_student','homeworks_student.id','=','homeworks_student_replay.homeworks_student_id')
                ->where('homeworks_student.homework_id','=',$records->id) 
                ->count();
                return "<a href=".URL_HOMEWORK_STUDENTS.$records->slug."><span style='font-weight:bold;'>".$records->title."</span></a> "." (".$count.")";
            })

            ->editColumn('subject_id', function ($records) {
                return Subject::where('id',$records->subject_id)->pluck('subject_title')->first();
            })

            ->editColumn('course_parent_id', function ($records) {
                return Course::where('id',$records->course_parent_id)->pluck('course_title')->first();
            })
            ->editColumn('course_id', function ($records) {
                return Course::where('id',$records->course_id)->pluck('course_title')->first();
            })
            ->editColumn('explanation', function ($records) {
                return "<a onclick=\"window.open('".PREFIX."homework/explanation/".$records->slug."','name','width=600,height=400')\"  target=\"popup\" >".getPhrase('view')."</a>";
            })
            ->editColumn('file', function ($records) {
                return "<a href='".HOMEWORK_PATH.$records->file."' download>".$records->file."</a>";
            })
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!checkRole(getUserGrade(3))) {
            prepareBlockUserMessage();
            return back();
        }
        $data['active_class'] = 'homeworks';
        $data['title']        = getPhrase('add_homework');
        $user = getUserRecord();
        $role = getRoleData($user->role_id);
        $data['role']=$role;
        if ($role == 'owner' || $role == 'admin') {
            $teachers = $this->getTeachers();
            $data['teachers'] = $teachers;
        }
        return view('home_work.add-edit', $data);

    }

    public function getTeachers()
    {

        $records = User::join('roles', 'users.role_id', '=', 'roles.id')
            ->join('staff', 'staff.user_id', '=', 'users.id')
            ->join('courses', 'courses.id', '=', 'staff.course_parent_id')
            ->where('roles.id', '=', 3)
            ->where('users.status', '!=', 0)
            ->select([
                'users.name',
                'image',
                'id_number',
                'staff.staff_id',
                'staff.job_title',
                'courses.course_title',
                'email',
                'roles.name as role_name',
                'login_enabled',
                'role_id',
                'users.slug as slug',
                'users.created_by_user','users.updated_by_user','users.created_by_ip','users.updated_by_ip','users.created_at','users.updated_at',
                'users.status',
                'staff.user_id'
            ])
            ->orderBy('users.updated_at', 'desc')->get();
        return $records;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (!checkRole(getUserGrade(3))) {
            prepareBlockUserMessage();
            return back();
        }
        $this->validate($request,[
            'title' => 'string|required',
            'course_id' => 'required',
            'course_subject_id' => 'required',
        ]);

       
        foreach($request->class_id as $course)
        {
            //$courses=$courses.$course.",";
        $students = Student::select('user_id')->where('course_id',$course)->get();
       
        $record = new HomeWork();
        $record->title = $request->title;
        $record->slug = $record->makeSlug( $record->title, true);
        if(is_teacher()){
            $record->staff_id = Auth::user()->id;
        }else{
            $staffId = User::where('slug', $request->teacherSlug)->first()->id;
            $record->staff_id = $staffId;

        }
        //dd($students);
       
        //dd($courses);
        $record->course_parent_id = $request->course_id;
        $record->course_id =$course;// $request->class_id;
        $record->subject_id = $request->course_subject_id;
        $record->explanation = $request->explanation;
        $record->year = $request->academic_id;
        $record->sem = $request->current_semister;
        $record->file        = $request->question_file;
        $record->user_stamp($request);
        $record->save();
        
        foreach ($students as $student){
            $homework = new HomeworkStudent();
            $homework->homework_id = $record->id;
            $homework->slug = $homework->makeSlug($record->title.$homework->homework_id,true);
            $homework->student_id  = $student->user_id;
            $homework->save();
        }
    }
        flash(getPhrase('success'), getPhrase('record_added_successfully'), 'success');
        return redirect(URL_HOMEWORK_ADD);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $record     = HomeWork::where('slug', $slug)->get()->first();
        return $record;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        if(!checkRole(getUserGrade(3)))
        {
            prepareBlockUserMessage();
            return back();
        }
        $data['record']     = HomeWork::where('slug', $slug)->first();
        $data['edit'] = "set";
        if($data['record'] == null){
            prepareBlockUserMessage();
            return back();
        }
        $data['active_class'] = 'homeworks';
        $data['title']        = getPhrase('edit_homework');
        $user = getUserRecord();
        $role = getRoleData($user->role_id);
        $data['role']=$role;
        if ($role == 'owner' || $role == 'admin') {
            $teachers = $this->getTeachers();
            $data['teachers'] = $teachers;
        }
        return view('home_work.add-edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        if(!checkRole(getUserGrade(3)))
        {
            prepareBlockUserMessage();
            return back();
        }
        $record                 = HomeWork::where('slug', $slug)->get()->first();

        $this->validate($request, [
            'title'          => 'bail|required|max:60|',
        ]);
        $record->title = $request->title;
        if($request->question_file != ''){
            $record->file        = $request->question_file;
        }
        $record->explanation = $request->explanation;
        $record->update_stamp($request);
        $record->save();
        flash(getPhrase('success'),getPhrase('record_updated_successfully'), 'success');
        return back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        HomeWork::where('slug',$slug)->delete();
        $response['status'] = 1;
        $response['message'] = getPhrase('record_deleted_successfully');
        return json_encode($response);
    }

    public function StudentHW($student = null)
    {

        if (!checkRole(getUserGrade(13))) {
            prepareBlockUserMessage();
            return back();
        }
        if(Auth::user()->role_id == 5){
            $data['student_slug'] = Auth::user()->slug;
        }else{
            $data['student_slug'] = $student;
        }
        $data['active_class'] = 'homeworks';
        $data['title'] = getPhrase('HomeWorks');
        $data['module_helper'] = getModuleHelper('view-questions');
        return view('home_work.student_parent_view', $data);
    }

    public function StudentDatable($student)
    {
        $student_id = User::where('slug',$student)->pluck('id')->first();
        $student_data = Student::where('user_id',$student_id)->first();
        $records    = HomeWork::join('homeworks_student','homeworks_student.homework_id','=','home_works.id')
            ->select(['home_works.id','homeworks_student.slug','home_works.title','home_works.subject_id','home_works.staff_id','home_works.file','home_works.created_at'])
            ->where('home_works.year',default_year())
            ->where('home_works.sem',default_sem(default_year()))
            ->where('home_works.course_id',$student_data->course_id)
            //->whereRaw("find_in_set($student_data->course_id , coursesids)")
            ->where('homeworks_student.student_id',$student_id);
        return Datatables::of($records)
            ->editColumn('subject_id', function ($records) {
                return Subject::where('id',$records->subject_id)->pluck('subject_title')->first();
            })
            ->editColumn('staff_id', function ($records) {
                return User::where('id',$records->staff_id)->pluck('name')->first();
            })
            ->editColumn('file', function ($records) {
                return "<a href='".HOMEWORK_PATH.$records->file."' download>".$records->file."</a>";
            })
            ->addColumn('show',function ($records){
                return "<a href='".URL_HOMEWORK_STUDENT_VIEW.$records->slug."' >".getPhrase('show')."</a>";
            })
            ->removeColumn('slug')
            ->make();
    }

    public function showExplanation($slug)
    {
        $explanation = HomeWork::where('slug',$slug)->first()->explanation;
        return $explanation;
    }

    public function show_student_homework($slug){
        if (!checkRole(getUserGrade(14))) {
            prepareBlockUserMessage();
            return back();
        }
        
        $record = HomeworkStudent::where('slug',$slug)->first();
        if(!$record){
            pageNotFound();
            return back();
        }
        if(is_student() && $record->student_id != Auth::user()->id){
            pageNotFound();
            return back();
        }
        $homework = HomeWork::where('id',$record->homework_id)->first();

        $data['replays'] = HomeWorkReplay::join('users','users.id','=','homeworks_student_replay.sender_id')
                                         ->select('users.name','users.role_id','homeworks_student_replay.id','homeworks_student_replay.created_at','massage','file')
                                         ->where('homeworks_student_id',$record->id)
                                         ->orderBy('id','desc')
                                         ->get();
        if (checkRole(getUserGrade(3))) {
            foreach( $data['replays'] as $replay)
            HomeWorkReplay::where('id',$replay->id)->update(['visited' => 1]);
        }
        $data['homework'] = $homework;
        $data['record'] = $record;
        $data['title'] = $homework->title;
        $data['active_class'] = 'homeworks';
        $data['layout'] = getLayout();
        //return $data;
        return view('home_work.homework_view',$data);
    }

    public function replay($slug,Request $request)
    {
        if (!checkRole(getUserGrade(14))) {
            prepareBlockUserMessage();
            return back();
        }

        $record = HomeworkStudent::where('slug',$slug)->first();

        if(!$record){
            pageNotFound();
            return back();
        }
        if(is_student() && $record->student_id != Auth::user()->id){
            pageNotFound();
            return back();
        }

        $data['slug'] = $slug;
        $data['request'] = $request->all();
        $replay = new HomeWorkReplay();
        $replay->homeworks_student_id = $record->id;
        $replay->sender_id            = Auth::user()->id;
        $replay->massage              = $request->replay;
        $replay->file                 = $request->question_file;
        $replay->save();
        flash(getPhrase('success'), getPhrase('replay_added_successfully'), 'success');
        return back();
    }
    public function Homework_students($slug)
    {
        $homework = HomeWork::where('slug',$slug)->first();
        if(!$homework){
            pageNotFound();
            return back();
        }
        if (!checkRole(getUserGrade(3))) {
            prepareBlockUserMessage();
            return back();
        }
        $data['homework'] = $homework;
        $data['title'] = $homework->title;
        $data['active_class'] = 'homeworks';
        $data['layout'] = getLayout();
        return view('home_work.homework_students',$data);

    }

    public function HW_student_Datable($slug)
    {
        $records  = array();
        $homework = HomeWork::where('slug',$slug)->first();
        $records  = HomeworkStudent::leftjoin('homeworks_student_replay','homeworks_student.id','=','homeworks_student_replay.homeworks_student_id')
       
         ->leftjoin('users','users.id','=','homeworks_student.student_id')
         ->where('homeworks_student.homework_id','=',$homework->id) 
         ->orderBy('homeworks_student_replay.id','desc')
         ->orderBy('homeworks_student_replay.visited','desc')
         ->select('users.slug as uslug','users.name','homeworks_student.student_id','homeworks_student.id','homeworks_student.slug')->distinct();
        return Datatables::of($records)
            ->editColumn('id', function ($records) {
                $link="";
                $last_update = HomeWorkReplay::where('homeworks_student_id',$records->id)->orderBy('id','desc')->first();
                $visited=0;
                if(!$last_update){
                    $last_update = HomeworkStudent::where('id',$records->id)->first()->created_at;
                    $link=$last_update;
                }else { 
                    $visited=     $last_update->visited;
                    $last_update = $last_update->created_at;
                    $link=$visited==0? "<span style='font-weight:bold;color:red'>".$last_update." <span>" :$last_update.' &#10003;';
                    
                    
                }
              
                return $link;
            })
            ->editColumn('name', function ($records) {
                $link="";
                $last_update = HomeWorkReplay::where('homeworks_student_id',$records->id)->orderBy('id','desc')->first();
                $visited=0;
                if(!$last_update){
                    $last_update = HomeworkStudent::where('id',$records->id)->first()->created_at;
                    $link="<a href=" . URL_USER_DETAILS . $records->uslug . ">".$records->name."</a>";
                }else { 
                    $visited=     $last_update->visited;
                    $last_update = $last_update->created_at;
                    $link=$visited==0? "<a href=" . URL_USER_DETAILS . $records->uslug . "><span style='font-weight:bold;color:red'>".$records->name." <span></a>" :"<a href=" . URL_USER_DETAILS . $records->uslug . ">".$records->name."</a>"." &#10003;";
                    
                    
                }
              
                return $link;
            })
            ->editColumn('student_id', function ($records) {
                $link="";
                $last_update = HomeWorkReplay::where('homeworks_student_id',$records->id)->orderBy('id','desc')->first();
                $visited=0;
                if(!$last_update){
                    $last_update = HomeworkStudent::where('id',$records->id)->first()->created_at;
                    $link=getUserName($records->student_id);
                }else { 
                    $visited=     $last_update->visited;
                    $last_update = $last_update->created_at;
                    $link=$visited==0? "<span style='font-weight:bold;color:red'>".getUserName($records->student_id)." <span>" :getUserName($records->student_id).' &#10003;';
                    
                    
                }
              
                return $link;
            })
            
            ->addColumn('show',function ($records){
                return "<a href='".URL_HOMEWORK_STUDENT_VIEW.$records->slug."' >".getPhrase('show')."</a>";
            })
            ->removeColumn('slug')
            ->removeColumn('uslug')
            ->make();
    }

    public function children()
    {

        $user = getUserWithSlug();

        if (!checkRole(getUserGrade(7))) {
            prepareBlockUserMessage();
            return back();
        }

        if (!isEligible($user->slug)) {
            return back();
        }

        $data['records'] = false;
        $data['user'] = $user;
        $data['title'] = getPhrase('children');
        $data['active_class'] = 'homeworks';
        $data['layout'] = getLayout();
        return view('home_work.list-children', $data);
    }

    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */

    public function getDatatable($slug)
    {
        $records = array();
        $user = getUserWithSlug($slug);

        $records = User::select(['name', 'image', 'email', 'slug', 'id'])->where('parent_id', '=', $user->id);
        // ->get();


        return Datatables::of($records)
            ->editColumn('name', function ($records) {
                return '<a href="' . URL_HOMEWORK_STUDENT .'/'. $records->slug . '" title="' . $records->name . '">' . ucfirst($records->name) . '</a>';
            })
            ->editColumn('image', function ($records) {
                return '<img src="' . getProfilePath($records->image) . '"  />';
            })
            ->removeColumn('slug')
            ->removeColumn('id')
            ->make();
    }

    public function processUpload(Request $request, $record = null, $file_name, $type = 'option')
    {
        if (env('DEMO_MODE')) {
            return;
        }
        if ($record == null) {
            $record1 = rand(1, 200000);
            $record2 = rand(1, 200000);
            if ($request->hasFile($file_name)) {
                $destinationPath = "uploads/homeworks/";
                $fileName = $record . '-' . $file_name . '.' . $request->$file_name->guessClientExtension();
                if ($type != 'option') {

                    $fileName = $record1 . $type . $record2 . '.' . $request->$file_name->guessClientExtension();

                }
            }
        } else {
            if ($request->hasFile($file_name)) {
                $destinationPath = "uploads/homeworks/";
                $fileName = $record->id . '-' . $file_name . '.' . $request->$file_name->guessClientExtension();
            }
        }

        $request->file($file_name)->move($destinationPath, $fileName);
        return $fileName;
    }

    public function upload(Request $request)
    {


        if ($request->hasFile('file')) {


            $value = $this->processUpload($request, null, 'file', 'homework');
            return json_encode(['state' => 'success', 'desc' => getPhrase('upload_success'), 'file' => $value]);
        }
        return json_encode(['state' => 'failed', 'desc' => getPhrase('upload_failed')]);

    }

    public function deleteFile($file)
    {

        if(file_exists(getcwd()."/uploads/homeworks/".$file)){
            unlink(getcwd()."/uploads/homeworks/".$file);
            HomeWork::where('file',$file)->update(['file' => null]);
            return $file;
        }else{
            HomeWork::where('file',$file)->update(['file' => null]);
            return "file not found";
        }

    }


    public function deleteReplay($id){
        if (!checkRole(getUserGrade(3))) {
            prepareBlockUserMessage();
            return back();
        }
        $replay = HomeWorkReplay::where('id',$id)->first();
        if(!$replay){
            pageNotFound();
            return back();
        }
        if($replay->file){
            $this->deleteFile($replay->file);
        }
        DB::delete('Delete from homeworks_student_replay where id = ?', [$replay->id]);
        flash(getPhrase('success'), getPhrase('record_deleted_successfully'), 'success');
        return back();
    }
}
