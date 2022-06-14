<?php

namespace App\Http\Controllers;
use \App;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Timingset;
use App\TimingsetDetails;
use Yajra\Datatables\Datatables;
use DB;
use Auth;
use Exception;
use File;
class TimingsetController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * This method loads the view for list of timings set available
     * @return [type] [description]
     */
    public function index()
    {
        if(!checkRole(getUserGrade(2)))
        {
          prepareBlockUserMessage();
          return back();
        }

        $data['active_class']       = 'academic';
        $data['title']              = getPhrase('timing_sets');
        $data['module_helper']      = getModuleHelper('timetable-set-list');
        return view('timetable.timingset.list', $data);
    }

     /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatable()
    {

        if(!checkRole(getUserGrade(2)))
        {
          prepareBlockUserMessage();
          return back();
        }

         $records = Timingset::select([
            'name',  'description', 'id','slug','created_by_user','updated_by_user','created_by_ip','updated_by_ip','created_at','updated_at']);
        //  ->orderBy('updated_at', 'desc');

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
                            <li><a href="'.URL_TIMINGSET_EDIT.'/'.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>'.$view;


        $temp = '';
        if(checkRole(getUserGrade(1))) {
        //$temp .= '<li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->slug.'\');"><i class="fa fa-trash"></i>'. getPhrase("delete").'</a></li>';
        }
        $temp .='</ul></div>';

        $link_data = $link_data.$temp;
            return $link_data;
            })
        ->editColumn('name',function($records){
            return '<a href="'.URL_TIMINGSET_EDIT.'/'.$records->slug.'">'.$records->name.'</a>';
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
        if(!checkRole(getUserGrade(2)))
        {
          prepareBlockUserMessage();
          return back();
        }

        $data['record']             = FALSE;
        $data['active_class']       = 'academic';
        $data['right_bar']          = TRUE;
        $data['right_bar_path']     = 'timetable.timingset.right-bar';
        $data['title']              = getPhrase('create_timing_set');

        return view('timetable.timingset.add-edit', $data);
    }

    /**
     * This method loads the edit view based on unique slug provided by user
     * @param  [string] $slug [unique slug of the record]
     * @return [view with record]
     */
    public function edit($slug)
    {
         if(!checkRole(getUserGrade(2)))
        {
          prepareBlockUserMessage();
          return back();
        }

        $record = Timingset::where('slug','=',$slug)->first();
        $timingset = TimingsetDetails::where('timingset_id','=',$record->id)->get();

        if($isValid = $this->isValidRecord($record))
            return redirect($isValid);
        // dd($timingset);

        $data['record']             = $record;
        $data['timingset']          = $timingset;
        $data['active_class']       = 'time_table';
        $data['title']              = getPhrase('create_set');
        $data['module_helper']      = getModuleHelper('timingset-add-edit');
        return view('timetable.timingset.add-edit', $data);
    }

    /**
     * Update record based on slug and reuqest
     * @param  Request $request [Request Object]
     * @param  [type]  $slug    [Unique Slug]
     * @return void
     */
   public function update(Request $request, $slug)
    {
        if(count($request->start_time_list)<=0)
        {

            flash(getPhrase('Ooops'),getPhrase('timingsets_cannot_be_empty'), 'overlay');
            return back();

        }
        if(!checkRole(getUserGrade(2)))
        {
          prepareBlockUserMessage();
          return back();
        }

        $record    = Timingset::where('slug','=',$slug)->first();

        if($isValid = $this->isValidRecord($record))
        return redirect($isValid);

        $timingset = TimingsetDetails::where('timingset_id','=',$record->id)->get();

        $existing_timesets = [];
        foreach($timingset as $timeset_variable)
        {
            $existing_timesets[$timeset_variable->id] = $timeset_variable->id;
        }

        $timesetid = $record->id;

        $rules     = ['name' => 'bail|required|max:30'];

         /**
        * Check if the title of the record is changed,
        * if changed update the slug value based on the new title
        */

        $name = $request->name;
        if($name != $record->name)
            $record->slug = $record->makeSlug($name);

       //Validate the overall request
        $this->validate($request, $rules);
        $record->name               = $name;
        $record->description        = $request->description;

        $start_time_list            = $request->start_time_list;
        $end_time_list              = $request->end_time_list;
        $is_break_list              = $request->is_break_list;
        $id_list                    = $request->id_list;
        $name_list                  = $request->name_list;
        $record->update_stamp($request);
        $record->save();
        foreach($request->id_list as $key => $value)
        {

            $timesetdetail_id            = 0;
            if(isset($existing_timesets[$value]))
            $timesetdetail_id            = $existing_timesets[$value];

            if($timesetdetail_id){
             //if id is available than update data

            $timingset  =  App\TimingsetDetails::where('id','=',$timesetdetail_id)
            ->first();
            $timingset->timingset_id   = $record->id;
            $timingset->period_name    = $name_list[$key];
            $timingset->start_time     = $start_time_list[$key];;
            $timingset->end_time       = $end_time_list[$key];
            $timingset->is_break       = $is_break_list[$key];
            $timingset->update_stamp($request);
             $timingset->save();

            }
             else{
                $timingset  = new App\TimingsetDetails();
                $timingset->timingset_id   = $record->id;
                $timingset->period_name    = $name_list[$key];;
                $timingset->start_time     = $start_time_list[$key];;
                $timingset->end_time       = $end_time_list[$key];
                $timingset->is_break       = $is_break_list[$key];
                $timingset->user_stamp($request);
                $timingset->save();
            }
        } 
      flash(getPhrase('success'),getPhrase('record_updated_successfully'), 'success');
        return redirect(URL_TIMINGSET_EDIT.'/'.$record->slug);
    }

    /**
     * This method adds record to DB
     * @param  Request $request [Request Object]
     * @return void
     */
    public function store(Request $request)
    {
        if(count($request->start_time_list)<=0)
        {

            flash(getPhrase('Ooops'),getPhrase('timingsets_cannot_be_empty'), 'overlay');
            return back();

        }

        if(!checkRole(getUserGrade(2)))
        {
          prepareBlockUserMessage();
          return back();
        }

        $rules = ['name'               => 'bail|required|max:30'];
        $this->validate($request, $rules);
         DB::beginTransaction();
      try{

        $record                     = new Timingset();
        $name                       = $request->name;
        $record->name               = $name;
        $record->slug               = $record->makeSlug($name);
        $record->description        = $request->description;
        $start_time_list            = $request->start_time_list;
        $end_time_list              = $request->end_time_list;
        $is_break_list              = $request->is_break_list;
        $details      = [];
        $record->user_stamp($request);
        $record->save();
        foreach($request->name_list as $key => $value)
        {
            $timeDetails                = new App\TimingsetDetails();
            $timeDetails->period_name   = $value;
            $timeDetails->timingset_id  = $record->id;
            $timeDetails->start_time    = $start_time_list[$key];
            $timeDetails->end_time      = $end_time_list[$key];
            $timeDetails->is_break      = $is_break_list[$key];
            $timeDetails->user_stamp($request);
            $timeDetails->save();
        }


        flash(getPhrase('success'),getPhrase('record_added_successfully'), 'success');
         DB::commit();
      }
     catch(Exception $e)
     {
      DB::rollBack();
       if(getSetting('show_foreign_key_constraint','module'))
       {

          flash(getPhrase('Ooops'),$e->getMessage(), 'error');
       }
       else {
          flash(getPhrase('Ooops'),getPhrase('improper_data_in_the_question'), 'error');
       }
     }

     return redirect()->back();
    }

    /**
     * Delete Record based on the provided slug
     * @param  [string] $slug [unique slug]
     * @return Boolean
     */
    public function delete($slug)
    {
         if(!checkRole(getUserGrade(2)))
        {
          prepareBlockUserMessage();
          return back();
        }

        $record = Timingset::where('slug', $slug)->first();
            try{
                if(!env('DEMO_MODE')) {
                    $record->delete();
                }
            $response['status'] = 1;
            $response['message'] = getPhrase('item_deleted_successfully');

       } catch ( \Illuminate\Database\QueryException $e) {
                 $response['status'] = 0;
           if(getSetting('show_foreign_key_constraint','module'))
            $response['message'] =  $e->getMessage();
           else
            $response['message'] =  getPhrase('this_record_is_in_use_in_other_modules');
       }
       return json_encode($response);

    }

    public function isValidRecord($record)
    {
        if ($record === null) {

            flash(getPhrase('Ooops'), getPhrase("page_not_found"), 'error');
            return $this->getRedirectUrl();
        }

        return FALSE;
    }

    public function getReturnUrl()
    {
        return URL_TIMINGSET;
    }

     /**
     * Delete Record based on the provided slug
     * @param  [string] $slug [unique slug]
     * @return Boolean
     */
    public function deleteTimingsetRecord($slug)
    {
      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }
        $record = App\TimingsetDetails::where('id', $slug)->first();

        /**
         * Check if any topic exists in this subject
         * If topics are available, dont delete this subject
         */
       if(!$record)
       {
            $response['status'] = 1;
            $response['message'] = getPhrase('record_deleted_successfully');
       }
       else{
           try {
                    if(!env('DEMO_MODE')) {
                        DB::statement("delete from timingsetdetails where id = '$record->id'");
                    }
                $response['status'] = 1;
                $response['message'] = getPhrase('record_deleted_successfully');
                }
                catch ( Exception $e) {
                     $response['status'] = 0;
               if(getSetting('show_foreign_key_constraint','module'))
                $response['message'] =  $e->getMessage();
               else
                $response['message'] =  getPhrase('this_record_is_in_use_in_other_modules');
           }
        }
            return json_encode($response);

    }
}
