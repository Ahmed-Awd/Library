<?php

namespace App\Http\Controllers;

use App;
use Auth;
use DB;
use Exception;
use Illuminate\Http\Request;
use StudentPromotion;

class StudentPromotionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
        if(!getSetting('transfer_students', 'module'))
        {
            pageNotFound();
            return back();
        }
        $data['active_class']   = 'academic';
        $data['academic_years'] = addSelectToList(getAcademicYears());
        $data['title']          = getPhrase('student_promotions');
        $data['layout']         = getLayout();
        $data['module_helper']  = getModuleHelper('student-transfers');
        $data['branches']       = array_pluck(App\Branch::all(),'name','id');
        $data['default_branch'] = Auth::user()->branch_id;
        return view('student-promotions.selection-view', $data);
    }

    /**
     * This method will transfer the students as per the specifications
     * submitted by admin
     * 1) This method will insert the records to
     * studentpromotions table with clear details like
     * promoted or detained with specific reason submitted by admin
     * 2) It will update the students table with current_year and current_semister
     * columns for the specific user
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function transferStudents(Request $request)
    {

        if ($request->is_completed == 0) {


            $academic_id = $request->academic_id;
            $course_parent_id = $request->course_parent_id;

            $course_id = $request->course_id;
            $from_year = 1;
            $from_semister = 0;

            $to_academic_id = $request->to_academic_id;
            $to_course_id = $request->to_course;
            $to_year = 1;
            $to_semister = 0;

            if (isset($course_id)) {
                $from_course_record = App\Course::where('id', '=', $course_id)->first();
            }
            $has_error = false;
            if (isset($from_course_record)) {
                if ($from_course_record->course_dueration > 1) {
                    $from_year = $request->current_year;
                }
            } else {
                $has_error = true;
            }
            if (in_array('promoted',$request->selected_list)) {
                if (isset($to_course_id) and $to_course_id != '') {
                    $to_course_record = App\Course::where('id', '=', $to_course_id)->first();
                } else {
                    $has_error = true;
                }
                //dd($has_error);
                if (isset($to_course_record)) {
                    if ($to_course_record->course_dueration > 1) {
                        $to_year = $request->to_year;
                    }
                }
            }

            if ($has_error) {
                flash(getPhrase('Ooops'), getPhrase('improper_selection'), 'error');
                return redirect(URL_STUDENT_TRANSFERS);
            }

            $remarks = $request->remarks;


            if ($request->has('current_semister')) {
                $from_semister = $request->current_semister;
            }
            if ($request->has('to_semister')) {
                $to_semister = $request->to_semister;
            }

            $record_updated_by = Auth::user()->id;

            if (!$from_year) {
                $from_year = 1;
            }
            if (!$to_year) {
                $to_year = 1;
            }


                foreach ($request->selected_list as $key => $record) {
                    if ($record == 'nothing') {
                        continue;
                    }
                    $studentObject = App\Student::withoutGlobalScope(App\Scopes\BranchScope::class)->where('user_id', '=', $key)->first();
                    $studentObjectPromotion = App\StudentPromotion::where('student_id', '=',$studentObject->id)->orderBy('id', 'desc')->first();

                    $promotionObject = new App\StudentPromotion();
                    $promotionObject->user_id = $key;
                    $promotionObject->student_id = $studentObject->id;
                    $promotionObject->type = $record;
                    $promotionObject->from_academic_id = $academic_id;
                    $promotionObject->from_course_parent_id = $course_parent_id;
                    $promotionObject->from_course_id = $course_id;
                    $promotionObject->from_year = $from_year;
                    $promotionObject->from_semister = $from_semister;


                    $promotionObject->to_academic_id = $to_academic_id;
                    if (isset($request->to_course_parent_id)) {
                        $promotionObject->to_course_parent_id = $request->to_course_parent_id;
                    }

                    if (isset($to_course_id)) {
                        $promotionObject->to_course_id = $to_course_id;
                    }

                    $promotionObject->to_year = $to_year;
                    $promotionObject->to_semister = $to_semister;
                    //Check if user got promoted or detained
                    //If promoted, the toyear and tosemister should be
                    //current year and semister
                    if ($record == 'detained') {
                        $promotionObject->to_academic_id = $academic_id;
                        if (isset($to_course_id)) {
                            $promotionObject->to_course_id = $to_course_id;
                        }

                        $promotionObject->to_year = $from_year;
                        $promotionObject->to_semister = $from_semister;
                    }

                    $promotionObject->remarks = $remarks[$key];
                    $promotionObject->description = '';
                    $promotionObject->record_updated_by = $record_updated_by;
                    $promotionObject->created_by_ip   = $request->ip();
                    $promotionObject->created_by_user = Auth::user()->id;
                    if(isset($request->branch)){
                        $promotionObject->branch_id = $request->branch;
                        App\User::where('id',$key)->update(['branch_id' =>$request->branch,'category_id' => $request->category_id]);
                    }
                    $promotionObject->save();
                    $studentObject->academic_id = $to_academic_id;
                    $studentObject->category_id = $request->category_id;
                    if (isset($request->to_course_parent_id)) {
                        /*$student_course=App\Course::where('parent_id',$request->to_course_parent_id)->first();*/
                        $studentObject->course_parent_id = $request->to_course_parent_id;
                        /* $studentObject->course_id = $student_course->id;*/
                    }
                    if (isset($to_course_id)) {
                        $studentObject->course_id = $to_course_id;
                    }

                    $studentObject->current_year = $to_year;
                    $studentObject->current_semister = $to_semister;

                    if ($record == 'detained') {
                        $studentObject->academic_id = $academic_id;
                        $studentObject->course_parent_id = $course_parent_id;
                        $studentObject->course_id = $course_id;
                        $studentObject->current_year = $from_year;
                        $studentObject->current_semister = $from_semister;
                    }
                    $studentObject->update_stamp($request);
                    $studentObject->save();
                }

                flash(getPhrase('success'), getPhrase('record_added_successfully'), 'success');
        } else {

            DB::beginTransaction();
            try {
                $academic_id = $request->academic_id;
                $course_parent_id = $request->course_parent_id;
                $course_id = $request->course_id;
                $from_year = 1;
                $from_semister = 0;
                if ($request->has('current_year')) {
                    $from_year = $request->current_year;
                }

                if ($request->has('current_semister')) {
                    $from_semister = $request->current_semister;
                }
                $to_year = -1;
                $to_semister = -1;
                $remarks = $request->remarks;
                $record_updated_by = Auth::user()->id;

                foreach ($request->selected_list as $key => $record) {
                    if ($record == 'nothing') {
                        continue;
                    }
                    $studentObject = App\Student::where('user_id', '=', $key)->first();
                    $studentObjectPromotion = App\StudentPromotion::where('student_id', '=',
                        $studentObject->id)->orderBy('id', 'desc')->first();
                    $promotionObject = new App\StudentPromotion();
                    $promotionObject->user_id = $key;
                    $promotionObject->student_id = $studentObject->id;
                    $promotionObject->type = 'completed';

                    $promotionObject->from_academic_id = $academic_id;
                    $promotionObject->from_course_parent_id = $course_parent_id;
                    $promotionObject->from_course_id = $course_id;
                    $promotionObject->from_year = $from_year;
                    $promotionObject->from_semister = $from_semister;

                    $promotionObject->to_academic_id = $academic_id;
                    if (isset($request->to_course_parent_id)) {
                        $promotionObject->to_course_parent_id = $request->to_course_parent_id;
                    }
                    if (isset($to_course_id)) {
                        $promotionObject->to_course_id = $to_course_id;
                    }

                    $promotionObject->to_year = $from_year;
                    $promotionObject->to_semister = $from_semister;
                    //Check if user got promoted or detained
                    //If promoted, the toyear and tosemister should be
                    //current year and semister
                    if ($record == 'detained') {
                        $promotionObject->type = 'detained';
                    }

                    $promotionObject->remarks = $remarks[$key];
                    $promotionObject->description = '';
                    $promotionObject->record_updated_by = $record_updated_by;
                    $promotionObject->user_stamp($request);
                    $promotionObject->save();

                    $studentObject->academic_id = $academic_id;
                    $studentObject->course_parent_id = $course_parent_id;
                    $studentObject->course_id = $course_id;
                    $studentObject->current_year = '-1';

                    $studentObject->current_semister = '-1';

                    //Check if user got promoted or detained
                    //If promoted, the toyear and tosemister should be
                    //current year and semister
                    if ($record == 'detained') {

                        $studentObject->academic_id = $academic_id;
                        $studentObject->course_parent_id = $course_parent_id;
                        $studentObject->course_id = $course_id;
                        $studentObject->current_year = $from_year;
                        $studentObject->current_semister = $from_semister;

                    }
                    $studentObject->update_stamp($request);
                    $studentObject->save();
                }

                flash(getPhrase('success'), getPhrase('records_updated_successfully'), 'success');
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                if (getSetting('show_foreign_key_constraint', 'module')) {

                    flash(getPhrase('Ooops'), $e->getMessage(), 'error');
                } else {
                    flash(getPhrase('Ooops'), getPhrase('improper_selection'), 'error');
                }
            }

        }
        return redirect(URL_STUDENT_TRANSFERS);

    }

    public function transferSingleStudent(Request $request)
    {
        DB::beginTransaction();
        try {

            $user_id = $request->user_id;
            $studentObject = App\Student::where('user_id', '=', $user_id)->first();
            $academic_id = $request->academic_id;
            $course_id = $request->course_id;
            $course_parent_id = $studentObject->course_parent_id;
            $from_year = $request->current_year;
            $from_semister = $request->current_semister;
            $to_year = -1;
            $to_semister = -1;
            $remarks = '';
            $record_updated_by = Auth::user()->id;


            if ($request->has('from_year')) {
                $from_year = $request->from_year;
            }
            if ($request->has('from_semister')) {
                $from_semister = $request->from_semister;
            }

            if ($request->has('remarks')) {
                $remarks = $request->remarks;
            }


            $promotionObject = new App\StudentPromotion();
            $promotionObject->user_id = $user_id;
            $promotionObject->student_id = $studentObject->id;
            $promotionObject->from_academic_id = $academic_id;
            $promotionObject->from_course_parent_id = $course_parent_id;
            $promotionObject->from_course_id = $course_id;
            $promotionObject->to_academic_id = $academic_id;
            $promotionObject->to_course_parent_id = $course_parent_id;
            $promotionObject->to_course_id = $course_id;
            $promotionObject->type = 'completed';
            $promotionObject->from_year = $from_year;
            $promotionObject->from_semister = $from_semister;
            $promotionObject->to_year = $to_year;
            $promotionObject->to_semister = $to_semister;
            $promotionObject->remarks = $remarks;
            $promotionObject->description = '';
            $promotionObject->record_updated_by = $record_updated_by;
            $promotionObject->user_stamp($request);
            $promotionObject->save();
            $studentObject->academic_id = $academic_id;
            $studentObject->current_year = '-1';

            $studentObject->current_semister = '-1';
            $studentObject->update_stamp($request);
            $studentObject->save();
            flash(getPhrase('success'), getPhrase('records_updated_successfully'), 'success');
            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();

            if (getSetting('show_foreign_key_constraint', 'module')) {

                flash(getPhrase('Ooops'), $e->getMessage(), 'error');
            } else {
                flash(getPhrase('Ooops'), getPhrase('internal_error'), 'error');
            }
        }


        return redirect(URL_STUDENT_TRANSFERS);

    }

    public function rebackDetain(Request $request)
    {
        $toRebackStudents = explode(',', $request->checkedList);
        $studentPromotionDetained = App\StudentPromotion::whereIn('student_id', $toRebackStudents)
            ->where('type', 'detained')
            ->get();
        $toShowerrors = false;
        foreach ($studentPromotionDetained as $student) {
          $student->type = 'admission';
          $student->branch_id = get_user_branch();
            if (!$student->update()) {
                $toShowerrors = true;
            }
        }
        if ($toShowerrors == true) {
            return 'error';
        } else {
            return 'done';
        }

    }

    public function rebackCompleted(Request $request)
    {
        $toRebackStudents = explode(',', $request->checkedList);
        $studentPromotionCompleted = App\StudentPromotion::whereIn('student_id', $toRebackStudents)
            ->where('type', 'completed')
            ->get();
        $toShowerrors = false;
        if (isset($studentPromotionCompleted) and !empty($studentPromotionCompleted) and $studentPromotionCompleted != null) {
            foreach ($studentPromotionCompleted as $student) {
                $student->type = 'admission';
                $student->branch_id = get_user_branch();
                if (!$student->update()) {
                    $toShowerrors = true;
                }
            }
        }
        if ($toShowerrors == true) {
            return 'error';
        } else {
            return 'done';
        }
    }
}
