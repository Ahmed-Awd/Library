<?php

namespace App\Http\Controllers;

use App;
use App\Academic;
use App\Course;
use App\User;
use App\YearGrades;
use Auth;
use DB;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Input;
use App\GeneralSettings as Settings;
use Excel;
use DatePeriod;
use DateTime;
use DateInterval;
use Session;
use Redirect;
use Response;


class gradeYearReportController extends Controller 
{


 
    public function __construct()
    {
        $this->middleware('auth');
    }


    

    /**
     * Load the Classes information for the current logged in user (i.e staff)
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function index($slug)
    {
        $userData = App\User::where('slug', '=', $slug)->first();
        $user = getUserRecord();
        $role = getRoleData($user->role_id);
        $data['role']=$role;
        if ($role != 'educational_supervisor') {
            if (!checkRole(getUserGrade(17)) && !checkRole(getUserGrade(3))) {
                prepareBlockUserMessage();
                return back();
            }
        }
        if ($role != 'educational_supervisor') {
            if (!isEligible($slug)) {
                return back();
            }
        }
        if ($role == 'owner' || $role == 'admin' || $role == 'student_guide' || $role == 'educational_supervisor') {
          $teachers = $this->getTeachers();
          $data['teachers'] = $teachers;
        }

        //Prepare the select list in the below format
        // Parent Course Code - Year-Sem - Subject-title

        if (getRoleData(Auth::user()->role_id) == 'educational_supervisor')
        {
            $data['active_class'] = 'teacher-student-attendance';
        } elseif (getRoleData(Auth::user()->role_id) == 'admin' || getRoleData(Auth::user()->role_id) == 'owner' || getRoleData(Auth::user()->role_id) == 'student_guide') {
          $data['active_class'] = 'academic';
        } else {
            $data['active_class'] = 'academic';
        }
        $data['title'] = getPhrase('year_grade');


        $data['role_name'] = getRoleData($user->role_id);
        $data['userdata'] = $user;
        $data['slugData']=$userData;

        $data['layout'] = getLayout();
        return view('reports.year_grade', $data);
    }


    public function getUploadTemplate($slug){

      $userData = App\User::where('slug', '=', $slug)->first();
        $user = getUserRecord();
        $role = getRoleData($user->role_id);
        $data['role']=$role;
        if ($role != 'educational_supervisor') {
            if (!checkRole(getUserGrade(17)) && !checkRole(getUserGrade(3))) {
                prepareBlockUserMessage();
                return back();
            }
        }
        if ($role != 'educational_supervisor') {
            if (!isEligible($slug)) {
                return back();
            }
        }
        if ($role == 'owner' || $role == 'admin' || $role == 'student_guide') {
          $teachers = $this->getTeachers();
          $data['teachers'] = $teachers;
        }

        //Prepare the select list in the below format
        // Parent Course Code - Year-Sem - Subject-title

        if (getRoleData(Auth::user()->role_id) == 'educational_supervisor')
        {
            $data['active_class'] = 'teacher-student-attendance';
        } elseif (getRoleData(Auth::user()->role_id) == 'admin' || getRoleData(Auth::user()->role_id) == 'owner' || getRoleData(Auth::user()->role_id) == 'student_guide') {
          $data['active_class'] = 'academic';
        } else {
            $data['active_class'] = 'academic';
        }
        $data['title'] = getPhrase('upload_year_grade');


        $data['role_name'] = getRoleData($user->role_id);
        $data['userdata'] = $user;
        $data['slugData']=$userData;

        $data['layout'] = getLayout();
        return view('reports.upload_year_grade', $data);

    }
 

 public function saveYear_Grades(Request $request){

     $rules = [
         'excel'               => 'bail|required' ,
            ];

        $this->validate($request, $rules);

     $slug =Auth::user() -> slug;
        $userData = App\User::where('slug', '=', $slug)->first();
        $user = getUserRecord();
        $role = getRoleData($user->role_id);
        $data['role']=$role;
        if ($role != 'educational_supervisor') {
            if (!checkRole(getUserGrade(17)) && !checkRole(getUserGrade(3))) {
                prepareBlockUserMessage();
                return back();
            }
        }
        if ($role != 'educational_supervisor') {
            if (!isEligible($slug)) {
                return back();
            }
        }
        if ($role == 'owner' || $role == 'admin' || $role == 'student_guide') {
          $teachers = $this->getTeachers();
          $data['teachers'] = $teachers;
        }
 

       $success_list = [];
       $failed_list   = [];

 try{
      if(Input::hasFile('excel')){
 
         $path = Input::file('excel')->getRealPath();
        $data = Excel::load($path, function($reader) {
          })->get();


          if (isset($data['is_elective_type']))
          {
              unset($data['is_elective_type']);
          }
          $excel_record = array();
          $final_records =array();
          $isHavingDuplicate = 0;

           if(!empty($data) && $data->count()){

            foreach ($data as $key => $record) {
                          
                unset($excel_record);
                $excel_record['student_id']       = $record-> student_id;
                $excel_record['academic_id']      = $record-> academic_id;
                $excel_record['course_id']        = $record-> course_id;
                $excel_record['year']             = $record-> year;
                $excel_record['semister']         = $record-> semister;
                $excel_record['subject_id']       = $record-> subject_id;
                $excel_record['student_name']     = $record-> student_name;
                $excel_record['quize']            = $record-> quize;
                $excel_record['chapter_test']     = $record-> chapter_test;
                $excel_record['project']          = $record-> project;
                $excel_record['portfolio']        = $record-> portfolio;
                $excel_record['participation']    = $record-> participation;
                $excel_record['total']            = $record-> total;
                $excel_record['letter_grade']     = $record-> letter_grade;
                $excel_record['comment_code']     = $record-> comment_code;


                   $failed_length = count($failed_list); 
                   $excel_record = (object)$excel_record;


                if(!$record->student_id || !$record->academic_id || !$record->course_id || !$record-> year || !$record-> subject_id || !$record-> student_name){

                     $temp = array();
                         $temp['record']    = $excel_record;
                         $temp['type']      = getPhrase('title_or_code_cannot_be_null');
                         $failed_list[$failed_length] = (object)$temp;
     
                      continue;
                }

                   $id =  $this->isRecordExists2($record->student_id,$record->academic_id,$record->course_id,$record-> year,$record-> subject_id);

                if( !empty($id) && $id > 0)
                {
                         
                            $this->updateToDb($excel_record ,$id)  ;      
                }else{

                             $this->pushToDb($excel_record);
                 }
    
  
            }    
          }


        }

 }catch( \Illuminate\Database\QueryException $e)
     {
       if(getSetting('show_foreign_key_constraint','module'))
       {

          flash(getPhrase('Ooops'),$e->errorInfo, 'error');
       }
       else {
          flash(getPhrase('Ooops'),getPhrase('improper_sheet_uploaded'), 'error');
       }

        return redirect(url('student/year_grades/add/'.Auth::user() -> slug.'?'.$request -> args));
     }


       $data['records']      = FALSE;
       $data['layout']       = getLayout();
       $data['active_class'] = 'master_settings';
       $data['heading']      = getPhrase('year_grade');
       $data['title']        = getPhrase('report');
       flash('success','record_added_successfully', 'success');
      // return view('mastersettings.subjects.import.import-result', $data);



       return redirect(url('student/year_grades/add/'.Auth::user() -> slug.'?'.$request -> args));

  
 }

   //check if sheet recode exists befor in db then updated else insert
 public function isRecordExists2($student_id,$academic_id,$course_id,$year,$subject_id)
 {
       $conditions = [
                       'student_id' => $student_id,
                       'academic_id'=> $academic_id,
                       'course_id'  => $course_id,
                       'year'       => $year,
                       'subject_id' => $subject_id,
                    ];

         $check = YearGrades::where($conditions)-> first();

         if($check){
            return $check  -> id;  //returnt record id to use in update statement
         }

      return 0;
 }

       
       //insert excrel sheet recode to database table 
   public function pushToDb($record)
     {
 
            $degrees  = array(
                                'quize'         =>  $record -> quize,
                                'chapter_test'  =>  $record -> chapter_test,
                                'project'       =>  $record -> project,
                                'portfolio'     =>  $record -> portfolio,
                                'participation' =>  $record -> participation,
                                'total'         =>  $record -> total,
                                'letter_grade'  =>  $record -> letter_grade,
                                'comment_code'  =>  $record -> comment_code,
                                'name'          =>  $record -> student_name,


                            );


                       YearGrades::create([
                                                  'student_id'   =>  $record -> student_id,
                                                 'academic_id'   =>  $record -> academic_id,
                                                  'course_id'    =>  $record  -> course_id,
                                                  'year'         =>  $record  -> year,
                                                  'semester'     =>  !empty($record  -> semister) ? : 0,
                                                  'subject_id'   =>  $record  -> subject_id,
                                                  'table_name'   => 'year_grades',
                                                  'record_updated_by'  =>  Auth::id(),
                                                  'degrees'            => json_encode($degrees)  ,
                                          ]);
      
     }



  //update recode based on sheet record 
   public function updateToDb($record,$id)
     { 
      
 
            $degrees  = array(
                                'quize'         =>  $record -> quize,
                                'chapter_test'  =>  $record -> chapter_test,
                                'project'       =>  $record -> project,
                                'portfolio'     =>  $record -> portfolio,
                                'participation' =>  $record -> participation,
                                'total'         =>  $record -> total,
                                'letter_grade'  =>  $record -> letter_grade,
                                'comment_code'  =>  $record -> comment_code,
                                'name'          =>  $record -> student_name,

                            );

 

        YearGrades::whereId($id) -> update([

                                                 'degrees'   => json_encode($degrees)  ,
                                          ]);
       
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
                ->orderBy('users.name', 'desc')->get();
                return $records;
    }


    /**
     * This method loads the create view
     * @return void
     */
    public function create(Request $request, $slug)
    {

        $userData = App\User::where('slug', '=', $slug)->first();
        if(isset($request->teacherSlug)) {
          $userData = App\User::where('slug', '=', $request->teacherSlug)->first();
        }
        $data['slugData']=$userData;
        $user = getUserRecord($userData->id);
        $role = getRoleData($user->role_id);
        $data['role']=$role;
        if ($role != 'educational_supervisor') {
            if (!checkRole(getUserGrade(18))) {

                prepareBlockUserMessage();
                return back();
            }
        }
        if ($request->course_subject_id == ''  ) {

            flash(getPhrase('Ooops'), getPhrase('Please_Select_The_Details'), 'overlay');
            return redirect()->back()->withInput($request->except('_token'));

        }

        $course_subject_record = App\CourseSubject::where('id', '=', $request->course_subject_id)->first();
        $academic_title = Academic::where('id', '=', $course_subject_record->academic_id)->get()->first();
        if (!$course_subject_record) {
            flash(getPhrase('Ooops'), getPhrase('Invalid_details_supplied'), 'overlay');
            return redirect()->back()->withInput($request->except('_token'));
        }

         
        $current_year = $course_subject_record->year;
        $current_semister = $course_subject_record->semister;

        $data['record'] = false;
        if ($role == 'educational_supervisor'){
            $data['active_class'] = 'academic';
        } elseif (getRoleData(Auth::user()->role_id) == 'admin' || getRoleData(Auth::user()->role_id) == 'owner') {
            $data['active_class'] = 'academic';
        }else {
            $data['active_class'] = 'academic';
        }
        $course_record = App\Course::where('id', '=', $course_subject_record->course_id)->first();
		$phase_record = App\Course::where('id', '=', $course_record->parent_id)->first();
		$subject_record = App\Subject::where('id', '=', $course_subject_record->subject_id)->first();


         $submitted_data = array(
             'current_year' => $current_year,
            'current_semister' => $current_semister,
			'phase_title'=>$phase_record->course_title,
			'course_title'=>$course_record->course_title,
			'subject_title'=>$subject_record->subject_title,
			'user_name'=> $userData->name,
            'course_record' => $course_record,
            'subject_id' => $course_subject_record->subject_id,
            'updated_by' => $user->id,
            'academic_id' => $course_subject_record->academic_id,
            'academic_title' => $academic_title
        );

        $studentObject = new App\Student();
         $students = $studentObject->getStudentsWithYearDegree(
            $course_subject_record->academic_id,
            $request->class_id
        /*$current_year,
        $current_semister*/
        );

        $data['submitted_data'] = (object)$submitted_data;

        $data['students'] = $students;
        $data['title'] = getPhrase('year_garde');
        $data['layout'] = getLayout();
        $data['role_name'] = getRoleData($user->role_id);
        $data['userdata'] = $user;
        $data['period']   = $request->total_class;
        session()->put('vrequest',$request->all());
        if (count($students)) {

        	  //return response() -> json($data);

            //return $data;
            
            Session::forget('VTemplatData');
            Session::put('VTemplatData',$data);

          $data['args'] =  substr(url()->full(), strpos(url()->full(), "?") + 1);

  
            if(request() -> get('type') == 'make_report'){

                  $view = \View::make('reports.year-grade-list-print-file', $data);
                  $contents = $view->render();
                  return $contents;
            }
            


            return view('reports.list', $data);
        } else {
            flash(getPhrase('Ooops'), getPhrase('no_students_available'), 'overlay');
        }
     }
    
    
    public function updateYearGrades(Request $request, $slug)
    { 

		 //return response() -> json($request);
 		$vrequest=Session::get('vrequest');
        $user = App\User::where('slug', '=', $slug)->first();
 
        $course_subject_record = App\CourseSubject::where('academic_id', '=', $request->academic_id)
            ->where('course_id', '=', $request->course_id)
            ->where('subject_id', '=', $request->subject_id)
            ->where('staff_id', '=', $user->id)
            ->first();
  
         
        flash(getPhrase('success'), getPhrase('record_updated_successfully'), 'success');
      
  $userData = App\User::where('slug', '=', $slug)->first();
        if(isset($vrequest['teacherSlug'])) {
          $userData = App\User::where('slug', '=', $vrequest['teacherSlug'])->first();
        }
        $data['slugData']=$userData;
        $user = getUserRecord($userData->id);
        $role = getRoleData($user->role_id);
        $data['role']=$role;
        if ($role != 'educational_supervisor') {
            if (!checkRole(getUserGrade(18))) {

                prepareBlockUserMessage();
                return back();
            }
        }
        if ($vrequest['course_subject_id'] == ''  ) {

            flash(getPhrase('Ooops'), getPhrase('Please_Select_The_Details'), 'overlay');
            return redirect()->back()->withInput($vrequest->except('_token'));

        }

        $course_subject_record = App\CourseSubject::where('id', '=', $vrequest['course_subject_id'])->first();
        $academic_title = Academic::where('id', '=', $course_subject_record->academic_id)->get()->first();
        if (!$course_subject_record) {
            flash(getPhrase('Ooops'), getPhrase('Invalid_details_supplied'), 'overlay');
            return redirect()->back()->withInput($vrequest->except('_token'));
        }
 

        /**
         * Find wether the attendance is already added for the day or not
         * @var [type]
         */

   
        $current_year = $course_subject_record->year;
        $current_semister = $course_subject_record->semister;


        $data['record'] = false;
        if ($role == 'educational_supervisor'){
            $data['active_class'] = 'teacher-student-attendance';
        } elseif (getRoleData(Auth::user()->role_id) == 'admin' || getRoleData(Auth::user()->role_id) == 'owner') {
            $data['active_class'] = 'attendance';
        }else {
            $data['active_class'] = 'academic';
        }
        $course_record = App\Course::where('id', '=', $course_subject_record->course_id)->first();
		$phase_record = App\Course::where('id', '=', $course_record->parent_id)->first();
		$subject_record = App\Subject::where('id', '=', $course_subject_record->subject_id)->first();

  
        $studentObject = new App\Student();
        $students = $studentObject->getStudents(
            $course_subject_record->academic_id,
            $vrequest['class_id']
        /*$current_year,
        $current_semister*/
        );


                      //$key = student_id  
		 foreach ($request -> students as $key => $student) {

		 	       
		 	        $savedBefore = $this -> checkSavedBefore($key,$request -> academic_id,$request  -> current_year,$request  -> subject_id , $request  -> current_semister ,$request  -> course_id);

                   
                     if(!$savedBefore){
 
		                YearGrades::create([
		                          
		                          'student_id'   =>  $key,
		                          'degrees'      => json_encode($student),    // json values for degress 
 		                          'academic_id'  =>  $request -> academic_id,
		                          'course_id'    =>  $request  -> course_id,
		                          'year'         =>  $request  -> current_year,
		                          'semester'     =>  $request  -> current_semister,
		                          'subject_id'   =>  $request  -> subject_id,
		                          'table_name'   => 'year_grades',
		                          'record_updated_by'  =>  $request  -> record_updated_by

		                    ]);
		            }else{
 
		            	 YearGrades::whereId($this -> getYearDegreesId($key,$request -> academic_id,$request  -> current_year,$request  -> subject_id , $request  -> current_semister ,$request  -> course_id)) -> update([
		                          
		                          'student_id'   =>  $key,
		                          'degrees'      => json_encode($student),    // json values for degress 
 		                          'academic_id'  =>  $request -> academic_id,
		                          'course_id'    =>  $request  -> course_id,
		                          'year'         =>  $request  -> current_year,
		                          'semester'     =>  $request  -> current_semister,
		                          'subject_id'   =>  $request  -> subject_id,
		                          'table_name'   => 'year_grades',
		                          'record_updated_by'  =>  $request  -> record_updated_by
		                    ]);

		            }
         }

           
       return redirect(url('student/year_grades/add/'.Auth::user() -> slug.'?'.$request -> args)) ;
       // return redirect(url('student/year_grades/'.Auth::user()->slug)) ;

   }


   protected function  checkSavedBefore($student_id,$academic_id,$year,$subject_id,$current_semister,$course_id){

          $conditions = [
      	                  'student_id'   =>  $student_id,
      	                  'academic_id'  =>  $academic_id,
                          'course_id'    =>  $course_id,
                          'year'         =>  $year,
                          'semester'     =>  $current_semister,
                          'subject_id'   =>  $subject_id,
                         ];
                 $check = YearGrades::where($conditions)  -> first();
                 if($check){

                 	return true;
                 }

                 return false;      
   }  


   protected function  getYearDegreesId($student_id,$academic_id,$year,$subject_id,$current_semister,$course_id){

          $conditions = [
      	                  'student_id'   =>  $student_id,
      	                  'academic_id'  =>  $academic_id,
                          'course_id'    =>  $course_id,
                          'year'         =>  $year,
                          'semester'     =>  $current_semister,
                          'subject_id'   =>  $subject_id,
                         ];
                 $check = YearGrades::where($conditions)  -> first();
                 if($check){

                 	return $check -> id;
                 }

                 return 0;      
   }  



 /**
     * Display a Import year grade page
     *
     * @return Response
     */
    public function importGradeYearTemplate($role = 'student')
    {
        
        $slug =Auth::user() -> slug;
        $userData = App\User::where('slug', '=', $slug)->first();
        $user = getUserRecord();
        $role = getRoleData($user->role_id);
        $data['role']=$role;
        if ($role != 'educational_supervisor') {
            if (!checkRole(getUserGrade(17)) && !checkRole(getUserGrade(3))) {
                prepareBlockUserMessage();
                return back();
            }
        }
        if ($role != 'educational_supervisor') {
            if (!isEligible($slug)) {
                return back();
            }
        }
        if ($role == 'owner' || $role == 'admin' || $role == 'student_guide') {
          $teachers = $this->getTeachers();
          $data['teachers'] = $teachers;
        }


        $data['records'] = false;
        $data['active_class'] = 'academic';
        $data['heading'] = getPhrase('users');
        $data['title']   = getPhrase('import_year_grade');
        $data['layout']  = getLayout();
  
        if(!Session::has('VTemplatData')){
            flash(getPhrase('Ooops'), getPhrase('no_data_available'), 'overlay');
           //flash(getPhrase('success'), getPhrase('record_updated_successfully'), 'success');
            return redirect(url('student/year_grades/'.Auth::user() -> slug));
         }

             
         //$grade_year_data = YearGrades::all();

          $grade_year_data = Session::get('VTemplatData');
  
        $columns[] = array('student_id','academic_id','course_id','year','semister','subject_id','student_name','quize','chapter_test','project','portfolio','participation','total','letter_grade','comment_code') ;
  
        foreach ($grade_year_data['students'] as $key => $student) {

                    $degrees = json_decode($student -> degrees);


                  $columns[] = array(
                                     //'id'              =>  $student    -> id,
                                     
                                     'student_id'      =>  $student        -> id,
                                     'academic_id'     =>  $student    -> academic_id,
                                     'course_id'       =>  $student    -> course_id,
                                     'year'            =>  $student    -> current_year,
                                     'semister'        =>  $grade_year_data['submitted_data'] -> current_semister,
                                     'subject_id'      =>  $grade_year_data['submitted_data'] -> subject_id,
                                     'student_name'    =>  $student        -> name ,
                                     'quize'           =>  !empty($degrees    -> quize) ? $degrees    -> quize : '',
                                     'chapter_test'    =>  !empty($degrees    -> chapter_test) ?$degrees    -> chapter_test : '',
                                     'project'         =>  !empty($degrees    -> project) ? $degrees    -> project: '',
                                     'portfolio'       => !empty($degrees    -> portfolio)? $degrees    -> portfolio: '' ,
                                     'participation'   => !empty($degrees    -> participation) ? $degrees    -> participation : '',
                                     'total'           => !empty($degrees    -> total)? $degrees    -> total : '',
                                     'letter_grade'    => !empty($degrees    -> letter_grade) ? $degrees    -> letter_grade : '' ,
                                     'comment_code'    => !empty($degrees    -> comment_code) ? $degrees    -> comment_code : '',
                                 );

        }
 
          
          Excel::create('year_grade_report', function($excel) use($columns){

               $excel -> setTitle('year_grade_report');
               $excel -> sheet('year_grade_report',function($sheet) use($columns){
                             $sheet -> fromArray($columns,null,'A1',false,false);
                              $sheet->getProtection()->setPassword('ahmedemam');
                             $sheet->getProtection()->setSheet(true);
                             $sheet->getStyle('H2:O1000')->getProtection()->setLocked(\PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);

                       });
                  })->download('xlsx');


        //return view('reports.import_year_grade', $data);
    }



}
