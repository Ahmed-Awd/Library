<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App;
use App\Http\Requests;
use App\Lmscategory;
use App\LmsContent;

use Yajra\Datatables\Datatables;
use DB;
use Auth;
use Image;
use ImageSettings;
use File;
use Exception;

class LmsContentController extends Controller
{

    public function __construct()
    {
    	$this->middleware('auth');
    }

    protected  $examSettings;

    public function setSettings()
    {
        $this->examSettings = getSettings('lms');
    }

    public function getSettings()
    {
        return $this->examSettings;
    }


    public function main()
    {
        if(!checkRole(getUserGrade(3)))
        {
            prepareBlockUserMessage();
            return back();
        }

        $data['active_class']       = 'lms';
        $data['title']              = getPhrase('lms').' - '.getPhrase('content');
        $data['layout']             = getLayout();
        $data['module_helper']      = getModuleHelper('lms-content-list');
        return view('lms.lmscontents.main-list', $data);
    }

    /**
     * Course listing method
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function index($year,$sem,$course,$subject)
    {
       if(!checkRole(getUserGrade(3)))
      {
        prepareBlockUserMessage();
        return back();
      }
        $subject_id = App\Subject::where('slug',$subject)->pluck('id')->first();

        if(!$subject_id)
        {
            pageNotFound();
            return back();
        }
        $data['active_class']       = 'lms';
        $data['title']              = getPhrase('lms').' - '.getPhrase('content');
        $data['layout']             = getLayout();
        $data['module_helper']      = getModuleHelper('lms-content-list');
        $data['year']               = $year;
        $data['sem']                = $sem;
        $data['course']             = $course;
        $data['subject']            = $subject;
        $data['second_title']       = make_title($year,$sem,$course,$subject);

    	return view('lms.lmscontents.list', $data);
    }

    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatable($year,$sem,$course,$subject)
    {
      if(!checkRole(getUserGrade(3)))
      {
        prepareBlockUserMessage();
        return back();
      }

      $subject_id = App\Subject::where('slug',$subject)->pluck('id')->first();
      $year_id    = App\Academic::where('slug',$year)->pluck('id')->first();
      $course_id  = App\Course::where('slug',$course)->pluck('id')->first();


      if(Auth::user()->role_id == 3){
          if(!is_teachers_subject($year_id,$sem,$course_id,$subject_id)){
              prepareBlockUserMessage();
              return back();
          }
          $records = LmsContent::join('subjects', 'lmscontents.subject_id', '=', 'subjects.id')
              ->select(['lmscontents.title','lmscontents.image','lmscontents.content_type','lmscontents.course_id', 'subjects.subject_title','lmscontents.topic_id','lmscontents.slug', 'lmscontents.id','lmscontents.updated_at','lmscontents.created_at','lmscontents.created_by_user','lmscontents.updated_by_user','lmscontents.created_by_ip','lmscontents.updated_by_ip' ])
              ->where('subjects.id','=',$subject_id)
              ->where('lmscontents.academic_id','=',$year_id)
              ->where('lmscontents.sem_id','=',$sem)
              ->where('lmscontents.course_id','=',$course_id);
      }
      else{
          $records = LmsContent::join('subjects', 'lmscontents.subject_id', '=', 'subjects.id')
              ->select(['lmscontents.title','lmscontents.image','lmscontents.content_type','lmscontents.course_id', 'subjects.subject_title','lmscontents.topic_id','lmscontents.slug', 'lmscontents.id','lmscontents.updated_at','lmscontents.created_at','lmscontents.created_by_user','lmscontents.updated_by_user','lmscontents.created_by_ip','lmscontents.updated_by_ip' ])
              ->where('subjects.id','=',$subject_id)
              ->where('lmscontents.academic_id','=',$year_id)
              ->where('lmscontents.sem_id','=',$sem)
              ->where('lmscontents.course_id','=',$course_id);
            //   ->orderBy('updated_at','desc');
      }

        $this->setSettings();
        return Datatables::of($records)
        ->addColumn('action', function ($records) {
            $records->created_by_user_name = App\User::get_user_name($records->created_by_user);
            $records->updated_by_user_name = App\User::get_user_name($records->updated_by_user);
            $view = "<li><a onclick='pop_it($records)'><i class=\"fa fa-eye\"></i>".getPhrase('view_record_history')."</a></li>";

            $extra = '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                            <li><a href="'.URL_LMS_CONTENT_EDIT.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>'.$view;
                            $temp = "";
                             if(checkRole(getUserGrade(1))){
                    $temp = '<li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->slug.'\');"><i class="fa fa-trash"></i>'. getPhrase("delete").'</a></li>';
                        }
                        $extra .= $temp.'</ul></div>';
                    return $extra;
            })
            ->editColumn('topic_id',function ($records){
                return get_topic_name($records->topic_id);
            })
            ->editColumn('course_id',function ($records){
                return getCourseName($records->course_id);
            })
        ->removeColumn('id')
            ->removeColumn('created_by_user')
            ->removeColumn('updated_by_user')
            ->removeColumn('created_by_ip')
            ->removeColumn('updated_by_ip')
            ->removeColumn('created_at')
            ->removeColumn('updated_at')
        ->removeColumn('slug')


        ->editColumn('image', function($records){
            $image_path = IMAGE_PATH_UPLOAD_LMS_DEFAULT;

            if($records->image)
            $image_path = IMAGE_PATH_UPLOAD_LMS_CONTENTS.$records->image;

            return '<img src="'.$image_path.'" height="100" width="100" />';
        })

        ->editColumn('content_type',function($records){
            return str_replace("_"," ",ucfirst($records->content_type));
        })

        ->make();
    }

    /**
     * This method loads the create view
     * @return void
     */
    public function create()
    {
       if(!checkRole(getUserGrade(3)))
      {
        prepareBlockUserMessage();
        return back();
      }
    	$data['record']       = FALSE;
    	$data['active_class'] = 'lms';
        if(Auth::user()->role_id == 3){
            $subjects = App\Subject::join('subjectpreferences','subjects.id','=','subjectpreferences.subject_id')->select('subjects.id','subjects.subject_title')->where('user_id','=',Auth::user()->id)->get();
            $data['subjects']       	= array_pluck($subjects, 'subject_title', 'id');
        }else{    	$data['subjects']       	= array_pluck(App\Subject::all(), 'subject_title', 'id');  }
        $data['title']              = getPhrase('add_content');
    	$data['layout']              = getLayout();
        $data['module_helper']      = getModuleHelper('lms-content-create');
        return view('lms.lmscontents.add-edit', $data);
    }

    /**
     * This method loads the edit view based on unique slug provided by user
     * @param  [string] $slug [unique slug of the record]
     * @return [view with record]
     */
    public function edit($slug)
    {
      if(!checkRole(getUserGrade(3)))
      {
        prepareBlockUserMessage();
        return back();
      }
    	$record = LmsContent::getRecordWithSlug($slug);
    	if($isValid = $this->isValidRecord($record))
    		return redirect($isValid);

    	$data['record']         	= $record;
    	$data['title']       		= getPhrase('edit').' '.$record->title;
    	$data['active_class']       = 'lms';
        if(Auth::user()->role_id == 3){
            $subjects = App\Subject::join('subjectpreferences','subjects.id','=','subjectpreferences.subject_id')->select('subjects.id','subjects.subject_title')->where('user_id','=',Auth::user()->id)->get();
            $data['subjects']       	= array_pluck($subjects, 'subject_title', 'id');
        }else{    	$data['subjects']       	= array_pluck(App\Subject::all(), 'subject_title', 'id');  }
    	$data['settings']           = json_encode($record);
        $data['layout']              = getLayout();
    	return view('lms.lmscontents.add-edit', $data);
    }

    /**
     * Update record based on slug and reuqest
     * @param  Request $request [Request Object]
     * @param  [type]  $slug    [Unique Slug]
     * @return void
     */
    public function update(Request $request, $slug)
    {
      if(!checkRole(getUserGrade(3)))
      {
        prepareBlockUserMessage();
        return back();
      }

    	$record = LmsContent::getRecordWithSlug($slug);
		  $rules = [
             'year_id'          	        => 'bail|required|integer' ,
             'sem_id'                       => 'bail|required|integer' ,
             'subject_id'                   => 'bail|required|integer' ,
             'course_id'                    => 'bail|required|integer' ,
             'title'                        => 'bail|required|max:60' ,
             'content_type'                 => 'bail|required',
              'topic_id'                    => 'required|integer'
        ];
        $file_path = $record->file_path;
        switch ($request->content_type) {
            case 'url':
            case 'video_url':
            case 'audio_url':
            case 'iframe':
                    if($request->file_path)
                        $file_path = $request->file_path;
                break;
            case 'file' :
                   if($request->file_path)
                    $file_path = $request->lms_file;
                break;
            case 'video' :
                    if($request->file_path)
                    $file_path = $request->lms_file;
                break;
            case 'audio' :
                    if($request->file_path)
                    $file_path = $request->lms_file;
                break;
        }


        $this->validate($request, $rules);
         DB::beginTransaction();
       try{
       $name = $request->title;
        if($name != $record->title)
            $record->slug = $record->makeSlug($name, TRUE);

    	$name  						=  $request->title;
		$record->title 				= $name;
        $record->title              = $name;

        $record->subject_id         = $request->subject_id;
        $record->course_id          = $request->course_id;
        $record->academic_id        = $request->year_id;
        $record->sem_id             = $request->sem_id;
        $record->content_type       = $request->content_type;
        $record->topic_id           = $request->topic_id;

        $record->file_path          = $file_path;
        $record->description        = $request->description;
        $record->record_updated_by  = Auth::user()->id;
        if(isset($request->image) && !empty($request->image)){
            $record->image      = $request->image;
        }
        if(isset($request->lms_file) && !empty($request->lms_file)){
            $record->file_path      = $request->lms_file;
        }

        $record->update_stamp($request);
        $record->save();

        DB::commit();
        flash(getPhrase('success'),getPhrase('record_updated_successfully'), 'success');

    }  catch(Exception $e)
     {
        DB::rollBack();
       if(getSetting('show_foreign_key_constraint','module'))
       {

          flash(getPhrase('Ooops'),$e->getMessage(), 'error');
       }
       else {
          flash(getPhrase('Ooops'),getPhrase('improper_data_file_submitted'), 'error');
       }
     }
     return redirect(URL_LMS_CONTENT_EDIT.$record->slug);
    }


    public function upload_image(Request $request){
        $file_name = 'file';
        if ($request->hasFile($file_name))
        {

            $rules = array( $file_name => 'mimes:jpeg,jpg,png,gif|max:10000' );
            $this->validate($request, $rules);
            $this->setSettings();

            $value      = $this->processUpload($request, null,$file_name);
            return json_encode(['state' => 'success','desc' => getPhrase('upload_success'),'file' => $value]);
        }
        return json_encode(['state' => 'failed','desc' => getPhrase('upload_failed')]);
    }

    public function upload_lms_file(Request $request){
        $file_name = 'file';
        if ($request->hasFile($file_name))
        {
            $this->setSettings();

            $value      = $this->processUpload($request, null, $file_name, FALSE);
            return json_encode(['state' => 'success','desc' => getPhrase('upload_success'),'file' => $value]);
        }
        return json_encode(['state' => 'failed','desc' => getPhrase('upload_failed')]);
    }
    /**
     * This method adds record to DB
     * @param  Request $request [Request Object]
     * @return void
     */
    public function store(Request $request)
    {


       if(!checkRole(getUserGrade(3)))
      {
        prepareBlockUserMessage();
        return back();
      }

	    $rules = [
             'year_id'          	        => 'bail|required|integer' ,
             'sem_id'                       => 'bail|required|integer' ,
             'subject_id'          	        => 'bail|required|integer' ,
             'course_id'                    => 'bail|required|integer' ,
             'title'          	   			=> 'bail|required|max:60' ,
             'content_type'                 => 'bail|required',
            'topic_id'                    => 'required|integer'


        ];
        $file_path = '';
        switch ($request->content_type) {
            case 'url':
            case 'video_url':
            case 'audio_url':
            case 'iframe':
                    $rules['file_path'] = 'bail|required';
                    $file_path = $request->file_path;
                break;
            case 'file' :
                     $rules['lms_file'] = 'bail|required';
                    $file_path = $request->lms_file;
                break;
            case 'video' :

                      $rules['lms_file'] = 'bail|required';
                    $file_path = $request->lms_file;
                break;
            case 'audio' :
                    $rules['lms_file'] = 'bail|required';
                    $file_path = $request->lms_file;
                break;
            case 'iframe' :
                    $rules['file_path'] = 'bail|required';
                    $file_path = $request->file_path;
        }



        $this->validate($request, $rules);
     DB::beginTransaction();
    try {
        $record = new LmsContent();
        $name = $request->title;
        $record->title = $name;
        $record->slug = $record->makeSlug($name, TRUE);
        $record->subject_id   = $request->subject_id;
        $record->course_id    = $request->course_id;
        $record->academic_id  = $request->year_id;
        $record->sem_id       = $request->sem_id;
        $record->content_type = $request->content_type;
        $record->topic_id     = $request->topic_id;



        $record->file_path = $file_path;
        $record->description = $request->description;
        $record->record_updated_by = Auth::user()->id;
        if (isset($request->image)) {
            $record->image = $request->image;
        }
        if (isset($request->lms_file)) {
            $record->file_path = $request->lms_file;
        }

        $record->user_stamp($request);
        $record->save();

        DB::commit();
        flash(getPhrase('success'), getPhrase('record_added_successfully'), 'success');

    }
     catch( Exception $e)
     {
        DB::rollBack();
       if(getSetting('show_foreign_key_constraint','module'))
       {

          flash(getPhrase('Ooops'),$e->getMessage(), 'error');
       }
       else {
          flash(getPhrase('Ooops'),getPhrase('improper_data_file_submitted'), 'error');
       }
     }

    	return redirect(URL_LMS_CONTENT_ADD);
    }

    /**
     * Delete Record based on the provided slug
     * @param  [string] $slug [unique slug]
     * @return Boolean
     */
    public function delete($slug)
    {
      if(!checkRole(getUserGrade(3)))
      {
        prepareBlockUserMessage();
        return back();
      }
        $record = LmsContent::where('slug', $slug)->first();
        $this->setSettings();

        try {
            $examSettings = $this->getSettings();
            $path = $examSettings->contentImagepath;
             if(!env('DEMO_MODE')) {

                $record->delete();
                $this->deleteFile($record->image, $path);
                if($record->file_path!='')
                     $this->deleteFile($record->file_path, $path);

            }
            $response['status'] = 1;
            $response['message'] = getPhrase('category_deleted_successfully');
        }
        catch (Exception $e) {
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
    	return URL_LMS_CONTENT;
    }

     public function deleteFile($record, $path, $is_array = FALSE)
    {
         if(!env('DEMO_MODE')) {
            $files = array();
            $files[] = $path.$record;
            File::delete($files);
        }
    }

     /**
     * This method process the image is being refferred
     * by getting the settings from ImageSettings Class
     * @param  Request $request   [Request object from user]
     * @param  [type]  $record    [The saved record which contains the ID]
     * @param  [type]  $file_name [The Name of the file which need to upload]
     * @return [type]             [description]
     */
     public function processUpload(Request $request, $record = null, $file_name, $is_image = TRUE)
     {
         if(env('DEMO_MODE')) {
            return;
         }

         if ($request->hasFile($file_name)) {
          $settings = $this->getSettings();
          $destinationPath      = $settings->contentImagepath;
          $path = $_FILES[$file_name]['name'];
          $ext = pathinfo($path, PATHINFO_EXTENSION);

          if($record == null){
              $record1 = rand(1, 200000);
              $record2 = rand(1, 200000);
              $fileName = $record1.'-'.$file_name.'-'.$record2.'.'.$ext;
              //w8 for it
          }else{
              $fileName = $record->id.'-'.$file_name.'.'.$ext;
          }

          $request->file($file_name)->move($destinationPath, $fileName);
         if($is_image){

         //Save Normal Image with 300x300
          Image::make($destinationPath.$fileName)->fit($settings->imageSize)->save($destinationPath.$fileName);
         }
         return $fileName;
        }

     }
}
