<?php

namespace App\Http\Controllers;

use App;
use Auth;
use DB;
use App\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;


class StudentNamesRecordingController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }

  public function index()
  {

      if (!checkRole(getUserGrade(17))) {
          prepareBlockUserMessage();
          return back();
      }

      $data['active_class']       = 'attendance';
      $data['academic_years'] = addSelectToList(getAcademicYears());
      $data['title']          = getPhrase('voice_records_for_students');
      $data['layout']         = getLayout();
      $data['branches']       = array_pluck(App\Branch::all(),'name','id');
      $data['default_branch'] = Auth::user()->branch_id;
      return view('attendance.studentNamesRecord.selection-view', $data);
  }
  public function Store(Request $request) {
        $destination_path = getcwd().DIRECTORY_SEPARATOR . "uploads/student_names";
        $studentId = $request->file('audio_data')->getClientOriginalName();
        $request->file('audio_data')->move($destination_path, $studentId . '.mp3');
        $student = Student::where('user_id', $studentId)->get()->first();
        if($student->student_audio == null) {
          $student->student_audio = $studentId . '.mp3';
          $student->save();
        }
  }
  public function Delete(Request $request)
  {
    $student = Student::where('user_id', $request->id)->get()->first();
    $path = getcwd().DIRECTORY_SEPARATOR . "uploads/student_names";
    File::delete($path . '/'.$request->id.'.mp3');
    $student->student_audio = null ;
    $student->save();
  }
}
