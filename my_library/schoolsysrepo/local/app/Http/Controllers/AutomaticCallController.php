<?php

namespace App\Http\Controllers;


use App;
use App\User;
use App\AutocallRequest;
use App\StudentAttendance;
use App\Student;
use App\SecondaryParentStudent;
use DB;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Scopes\BranchScope;


class AutomaticCallController extends Controller {
  public function __construct()
  {
      $currentUser = \Auth::user();


      $this->middleware('auth');

  }

  public function index()
  {

      $user = getUserWithSlug();

      if (!checkRole(getUserGrade(16))) {
          prepareBlockUserMessage();
          return back();
      }

      if (!isEligible($user->slug)) {
          return back();
      }

      $data['records'] = false;
      $data['user'] = $user;
      $data['title'] = getPhrase('automatic_call');
      $data['active_class'] = 'Automatic_call';
      $data['layout'] = getLayout();
      return view('automaticcall.list-children', $data);
  }

  public function getDatatable($slug)
  {
      $records = array();
      $user = getUserWithSlug($slug);
      $role = getRoleData($user->role_id);
      if($role == "secondary_parent") {
        $records = User::join('secondary_parent_student', 'secondary_parent_student.student_id', '=', 'users.id')
            ->select([
              'users.name as name',
              'users.image as image',
              'users.slug as slug',
              'users.id as id',
              'users.parent_id as parent_id'
          ])->where('secondary_parent_student.secondary_parent_id', Auth::user()->id) ;
          // ->get();
      } else  {
        $records = User::select(['name', 'image', 'slug', 'id', 'parent_id'])->where('parent_id', '=', $user->id);
        // ->get();
     }

      return Datatables::of($records)
          ->addColumn('automatic_call', function ($records){
            return '<button class="fa fa-assistive-listening-systems btn btn-info btn-small" onclick="autocallRequest('.Auth::user()->id.','.$records->id.')">'.getPhrase('automatic_call').'</button>';
          })
          ->addColumn('action', function ($records) {
                  return '<div class="dropdown more">
                      <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="mdi mdi-dots-vertical"></i>
                      </a>
                      <ul class="dropdown-menu" aria-labelledby="dLabel">
                         <li><a href="'.url("parent/autocallrequests").'/'. $records->id . '"><i class="fa fa-pencil"></i>' . getPhrase("automatic_call_requests") . '</a></li>
                      </ul>
                  </div>';
          })
          ->editColumn('name', function ($records) {
              return '<a href="' . URL_USER_DETAILS . $records->slug . '" title="' . $records->name . '">' . ucfirst($records->name) . '</a>';
          })
          ->editColumn('image', function ($records) {
              return '<img src="' . getProfilePath(@$records->image) . '"  />';
          })
          ->removeColumn('slug')
          ->removeColumn('id')
          ->removeColumn('parent_id')
          ->make();
  }

  public function AutomaticCallRequest(Request $request) {
    $response = new Response();
    $requestIsExist = AutocallRequest::where('student_id', $request->student_id)
                                         ->where('request_date', $request->request_date)->first();

   $studentID = Student::where('user_id', $request->student_id)->select('id')->first()->id;
   $studentAttendance = StudentAttendance::where('student_id', $studentID)
                                         ->where('record_status', 1)
                                         ->where('attendance_date', $request->request_date)
                                         ->orderBy('total_class', 'desc')->first();

    if($requestIsExist){
      if($requestIsExist->leave_status == 0) {
        return response("already called", 406);
      }
      elseif ($requestIsExist->leave_status == 1) {
        return response("student is out", 406);
      }
    }
    else {
      if($studentAttendance) {
        $newRequest = new AutocallRequest();
        $newRequest->student_id = $request->student_id;
        $newRequest->parent_id = $request->parent_id;
        $newRequest->request_date = $request->request_date;
        $newRequest->request_status = 0;
        $newRequest->leave_status = 0;
        $newRequest->user_stamp($request);
        if($studentAttendance->attendance_code == "A") {
          return response("absent", 406);
        } elseif($studentAttendance->attendance_code == "L") {
          return response("left", 406);
        } else {
          $newRequest->save();
          return response(200);
        }
      } else {
        return response("notAttendance", 406);
      }

  }
}
  public function ParentRequests($student_id){
    $user = getUserWithSlug();
    $data['user'] = $user;
    $data['student_id'] = $student_id;
    $data['title'] = getPhrase('automatic_call');
    $data['active_class'] = 'Automatic_call';
    $data['layout'] = getLayout();
    return view('automaticcall.list-requests', $data);
  }
  public function getDatatableRequests($student_id, $slug) {
    $records = array();
    $user = getUserWithSlug($slug);
    $automaticCallRequests = AutocallRequest::select(['request_date', 'leave_time', 'autocall_counter'])->where('student_id', $student_id)
                                            ->where('parent_id', $user->id);
                                            // ->get();

    return Datatables::of($automaticCallRequests)->make();
  }
  public function allRequest($note) {
    $user = getUserWithSlug();
    if (!checkRole(getUserGrade(17))) {
        prepareBlockUserMessage();
        return back();
    }
    if (!isEligible($user->slug)) {
        return back();
    }
    $data['user'] = $user;
    $data['title'] = getPhrase('automatic_call');
    date_default_timezone_set('Africa/Nairobi');
    $date = date("Y-m-d");
    $requestwithStudent = AutocallRequest::join('students','students.user_id', '=','autocall_requests.student_id')
                                         ->join('users', 'users.id', '=', 'autocall_requests.student_id')
                                         ->where('autocall_requests.request_date', $date)
                                         ->where('autocall_requests.leave_status', 0)
                                        ->where('users.branch_id', session()->get('branch_id'))
                                         ->select([
                                           'users.name as name',
                                           'users.id as student_id',
                                           'users.image as image',
                                           'students.course_id as course_id',
                                           'students.course_parent_id as course_parent_id',
                                           'students.student_audio',
                                           'autocall_requests.id as request_id',
                                         ])
                                        ->orderBy('autocall_requests.request_time','asc')->get();

foreach($requestwithStudent as $student) {
    $courseTitle = App\Course::withoutGlobalScope(App\Scopes\CategoryScope::class)->where('id', $student->course_id)->first()->course_title;
    $courseParentTitle = App\Course::withoutGlobalScope(App\Scopes\CategoryScope::class)->where('id', $student->course_parent_id)->first()->course_title;
    $student->course_id = $courseTitle;
    $student->course_parent_id = $courseParentTitle;
}
$data['requests'] = $requestwithStudent;
  if($note == "view") {
    return view('automaticcall.adminautocall.all-request', $data);
  } else {
    return $requestwithStudent;
  }
}

public function leave(Request $request)
{


  $exitRequest = AutocallRequest::where('id', $request->request_id)->first();
  $exitRequest->leave_status = 1;
  $exitRequest->leave_time = $request->leave_time;
  $exitRequest->save();

  $student = User::withoutGlobalScope(App\Scopes\BranchScope::class)->where('id',$exitRequest->student_id)->first();

  $parent    = User::withoutGlobalScope(App\Scopes\BranchScope::class)->where('id',$student->parent_id)->first();
  //makeAbsNotification($parent,$student,$request);
  //$secondary_parent=User::withoutGlobalScope(App\Scopes\BranchScope::class)->where('id',$student->secondary_parent_id)->first();
   $secondary_parent=SecondaryParentStudent::where('secondary_parent_student.student_id', $exitRequest->student_id)->first();

  $message['{$reciver}']         = $parent->name;
  $message['{$student}']           = $student->name;
  $message['to_email']           = $parent->email;
  $message['{$date}']          = $exitRequest->request_date;
  $message['{$time}']     = $request->leave_time;
  sendNotification('exit',$message,$parent,$student,$request);
  sendEmail('exit',$message);

  if($secondary_parent!=null)
  {
    $secondary_parent=User::withoutGlobalScope(App\Scopes\BranchScope::class)->where('id',$secondary_parent->secondary_parent_id)->first();
    //dd($secondary_parent->email);

    $message['{$reciver}']         = $secondary_parent->name;
    $message['{$student}']           = $student->name;
    $message['to_email']           = $secondary_parent->email;
    $message['{$date}']          = $exitRequest->request_date;
    $message['{$time}']     = $request->leave_time;
    sendNotification('exit',$message,$secondary_parent,$student,$request);
    sendEmail('exit',$message);
  }
}

  public function counter(Request $request)
  {
    $request = AutocallRequest::where('id', $request->request_id)->first();
    $request->autocall_counter += 1;
    $request->request_status = 1;
    $request->save();
  }
}

?>
