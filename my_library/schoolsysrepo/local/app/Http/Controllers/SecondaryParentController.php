<?php

namespace App\Http\Controllers;

use App\Student;
use App\User;
use App\SecondaryParentStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Yajra\Datatables\Datatables;

class SecondaryParentController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }

  public function getDatatableOfStudents()
  {
      $records = User::join('secondary_parent_student', 'secondary_parent_student.student_id', '=', 'users.id')
          ->select([
            'users.name',
            'users.slug'
        ])->where('secondary_parent_student.secondary_parent_id', Auth::user()->id);
        // ->get();
      return Datatables::of($records)
          ->removeColumn('id')
          ->removeColumn('username')
          ->removeColumn('slug')
          ->make();
  }

  public function getStudents()
  {
      $roleNameOfAuth = Auth::user()->role_id;
      $data['roleNameOfAuth'] = $roleNameOfAuth;
      $data['layout'] = getLayout();
      $data['active_class'] = 'student';
      $data['title'] = getPhrase('assign_students_to_secondary_parent');

      return view('users.secondary-parent.students', $data);
  }

  public function assignStudent($slug)
  {

      if (!checkRole(getUserGrade(2))) {
          prepareBlockUserMessage();
          return back();
      }
      $record = User::withoutGlobalScope(\App\Scopes\BranchScope::class)->where('slug', '=', $slug)->first();

      if ($isValid = $this->isValidRecord($record)) {
          return redirect($isValid);
      }
      if($record->role_id != 10){
          flash(getPhrase('Ooops'), getPhrase("invalid_user"), 'error');
          return back();
      }

      $students_record = User::withoutGlobalScope(\App\Scopes\BranchScope::class)->where('role_id', '5')->get(['id', 'name', 'username']);
      $allocated_student = User::withoutGlobalScope(\App\Scopes\BranchScope::class)->join('secondary_parent_student', 'secondary_parent_student.student_id', '=', 'users.id')
          ->where('secondary_parent_student.secondary_parent_id', $record->id)->get([
              'users.id',
              'users.name',
              'users.username',
              'users.slug'
          ]);
      $data['record'] = $record;
      $data['active_class'] = 'users';
      $data['title'] = getPhrase('student_to_secondary_parent');
      $data['items'] = json_encode(array('student' => $students_record, 'allocated_student' => $allocated_student));
      $data['right_bar'] = true;
      $data['right_bar_path'] = 'users.secondary-parent.item-view-right-bar';
      $data['right_bar_data'] = array(
          'item' => $students_record,
      );
      $data['layout'] = getLayout();
      return view('users.secondary-parent.add-edit', $data);
  }

  public function isValidRecord($record)
  {
      if ($record === null) {

          flash(getPhrase('Ooops'), getPhrase("page_not_found"), 'error');
          return $this->getRedirectUrl();
      }

      return false;
  }

  public function updateSecondaryParentStudent(Request $request, $slug)
  {
      $record = User::withoutGlobalScope(\App\Scopes\BranchScope::class)->where('slug', '=', $slug)->first(['id']);

      if ($isValid = $this->isValidRecord($record)) {
          return redirect($isValid);
      }
      DB::beginTransaction();
      try {
          if (count($request->selected_list)) {
              $previousRecord = SecondaryParentStudent::where('secondary_parent_id', $record->id)->delete();
              foreach (array_unique($request->selected_list) as $key => $value) {
                  if (SecondaryParentStudent::where('student_id', $value)->where('secondary_parent_id',$record->id)->first() != null) {
                      continue;
                  }
                  $newRecord = new SecondaryParentStudent();
                  $newRecord->secondary_parent_id = $record->id;
                  $newRecord->student_id = $value;
                  $newRecord->save();
              }

          }
          DB::commit();
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
      $studentId = $request->studentId;
      $secondaryParentId = $request->secondaryParentId;
      DB::statement("delete from secondary_parent_student where student_id = $studentId and secondary_parent_id = $secondaryParentId");
      $result = true;
  }
}
