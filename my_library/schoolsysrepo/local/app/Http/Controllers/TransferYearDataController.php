<?php

namespace App\Http\Controllers;

use App;
use DB;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\Datatables\Datatables;

class TransferYearDataController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * This method will display the courses dashboard
     */

    public function index()
    {

        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }

        $data['active_class'] = 'master_settings';
        $data['title'] = getPhrase('Transfer_data_to_next_year');
        $data['layout'] = getLayout();
        $data['academic_years'] = addSelectToList(getAcademicYears(1));
        $data['module_helper'] = getModuleHelper('mastersetup-dashboard');
        return view('mastersettings.transfer_data.copy', $data);

    }

    public function copyAcademicCourse(Request $request) {

      if (!checkRole(getUserGrade(2))) {
          prepareBlockUserMessage();
          return back();
      }
      $academic_courses = App\AcademicCourse::where('academic_id', $request->from_year)->get();
      foreach ($academic_courses as $academic_course) {
        $temp = App\AcademicCourse::where('academic_id', $request->to_year)
                                  ->where('course_id', $academic_course->course_id)
                                  ->first();
      if(!$temp) {
        $newRecord = $academic_course->replicate();
        $newRecord->academic_id = $request->to_year;
        $newRecord->save();
      }
      }
    }


    public function copyCourseSubject(Request $request) {
      if (!checkRole(getUserGrade(2))) {
          prepareBlockUserMessage();
          return back();
      }
      $course_subjects = App\CourseSubject::where('academic_id', $request->from_year)
                                          ->withoutGlobalScope('App\Scopes\BranchScope')
                                          ->get();

      foreach ($course_subjects as $course_subject) {
        $temp = App\CourseSubject::where('academic_id', $request->to_year)
                                  ->where('branch_id', $course_subject->branch_id)
                                  ->where('course_id', $course_subject->course_id)
                                  ->where('subject_id', $course_subject->subject_id)
                                  ->where('semister', $course_subject->semister)
                                  ->withoutGlobalScope('App\Scopes\BranchScope')
                                  ->first();
      if(!$temp) {
        $newRecord = $course_subject->replicate();
        $newRecord->academic_id = $request->to_year;
        $newRecord->slug = $newRecord->makeSlug(getHashCode());
        $newRecord->save();
      }
      }
    }

    public function copyTopics(Request $request)
    {
      if (!checkRole(getUserGrade(2))) {
          prepareBlockUserMessage();
          return back();
      }
      $topics = App\Topic::where('academic_id', $request->from_year)->get();

      foreach ($topics as $topic) {
        $slug = 'C'.$request->to_year.substr($topic->slug, 0, 25);
        $temp = App\Topic::whereRaw("SUBSTRING(slug, 1,  27)='".$slug."'")
                         ->where('topics.academic_id',  $request->to_year)->first();
                         // dd($temp);
      if(!$temp) {
        $newRecord = $topic->replicate();
        $newRecord->academic_id = $request->to_year;
        $newRecord->slug = 'C'.$request->to_year.$topic->slug;
        $newRecord->save();
      }
      }
    }

    public function copyQuestionBank(Request $request)
    {
      if (!checkRole(getUserGrade(2))) {
          prepareBlockUserMessage();
          return back();
      }
      $questions = App\QuestionBank::where('academic_id', $request->from_year)->get();

      foreach ($questions as $question) {
        $slug = 'C'.$request->to_year.substr($question->slug, 0, 25);
        $temp = App\QuestionBank::whereRaw("SUBSTRING(slug, 1,  27)='".$slug."'")
                         ->where('academic_id',  $request->to_year)->first();

      if(!$temp) {
        $newRecord = $question->replicate();
        $newRecord->academic_id = $request->to_year;
        $newRecord->slug = 'C'.$request->to_year.$question->slug;
        $newRecord->save();
      }
      }
    }
    public function copyLmsContent(Request $request)
    {
      if (!checkRole(getUserGrade(2))) {
          prepareBlockUserMessage();
          return back();
      }
      $lmsContents = App\LmsContent::where('academic_id', $request->from_year)->get();

      foreach ($lmsContents as $lmsContent) {
        $slug = 'C'.$request->to_year.substr($lmsContent->slug, 0, 25);
        $temp = App\LmsContent::whereRaw("SUBSTRING(slug, 1,  27)='".$slug."'")
                         ->where('academic_id',  $request->to_year)->first();

      if(!$temp) {
        $newRecord = $lmsContent->replicate();
        $newRecord->academic_id = $request->to_year;
        $newRecord->slug = 'C'.$request->to_year.$lmsContent->slug;
        $newRecord->save();
      }
      }
    }
  }
