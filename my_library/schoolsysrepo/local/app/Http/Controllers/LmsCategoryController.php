<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App;
use App\Http\Requests;
use App\LmsCategory;
use App\LmsSettings;
use Yajra\Datatables\Datatables;
use DB;
use Auth;
use Image;
use ImageSettings;
use File;
use Exception;
class LmsCategoryController extends Controller
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

     /**
      * This method will display the LMS dashboard
      */

     public function lmsdashboard()
    {

      if(!checkRole(getUserGrade(3)))
      {
        prepareBlockUserMessage();
        return back();
      }

      $data['active_class']       = 'lms';
      $data['title']              = getPhrase('lms').' - '.getPhrase('dashboard');
      $data['layout']             = getLayout();
      $data['module_helper']      = getModuleHelper('lms-dashboard');

      return view('lms.dashboard', $data);

    }

    /**
     * Course listing method
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        if(!checkRole(getUserGrade(3)))
        {
            prepareBlockUserMessage();
            return back();
        }
        $data['active_class']       = 'lms';
        $data['title']              = getPhrase('lms').' - '.getPhrase('categories');
        $data['module_helper']      = getModuleHelper('lms-categories-list');
    	return view('lms.lmscategories.list', $data);

    }

    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatable()
    {
        if(!checkRole(getUserGrade(3)))
        {
            prepareBlockUserMessage();
            return back();
        }
        if(isTeacher()){
            $records = LmsCategory::join('courses','lmscategories.course_id','=','courses.id')
                ->join('course_subject','courses.id','=','course_subject.course_parent_id')
                ->select(['lmscategories.category','courses.course_title', 'lmscategories.image', 'lmscategories.description', 'lmscategories.id','lmscategories.slug','lmscategories.created_by_user','lmscategories.updated_by_user','lmscategories.created_by_ip','lmscategories.updated_by_ip','lmscategories.created_at','lmscategories.updated_at'])
                ->where('courses.category_id',Auth::user()->category_id)
                ->where('lmscategories.branch_id',Auth::user()->branch_id)
                ->where('course_subject.staff_id',Auth::user()->id)
                ->groupBy('lmscategories.id');
        }else{
            $records = LmsCategory::join('courses','lmscategories.course_id','=','courses.id')
                ->select(['lmscategories.category','courses.course_title', 'lmscategories.image', 'lmscategories.description', 'lmscategories.id','lmscategories.slug','lmscategories.created_by_user','lmscategories.updated_by_user','lmscategories.created_by_ip','lmscategories.updated_by_ip','lmscategories.created_at','lmscategories.updated_at'])
                ->where('courses.category_id',Auth::user()->category_id)
                ->where('lmscategories.branch_id',Auth::user()->branch_id);
        }
        $this->setSettings();
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
                            <li><a href="'.URL_LMS_CATEGORIES_EDIT.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>
                            '.$view.'
                            <li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->slug.'\');"><i class="fa fa-trash"></i>'. getPhrase("delete").'</a></li>

                        </ul>
                    </div>';
            })

        ->removeColumn('id')
        ->removeColumn('slug')
            ->removeColumn('created_by_user')
            ->removeColumn('updated_by_user')
            ->removeColumn('created_by_ip')
            ->removeColumn('updated_by_ip')
            ->removeColumn('created_at')
            ->removeColumn('updated_at')


            ->editColumn('image', function($records){
            $image = '<img src="'.IMAGE_PATH_UPLOAD_LMS_DEFAULT.'" height="100" width="100" />';
            if($records->image)
            $image = '<img src="'.IMAGE_PATH_UPLOAD_LMS_CATEGORIES.$records->image.'" height="100" width="100" />';
        return $image;
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
    	$data['record']         	= FALSE;
    	$data['active_class']       = 'lms';
    	$data['title']              = getPhrase('create_category');
        $data['module_helper']      = getModuleHelper('lms-categories-create');
        $current_year = default_year();
        $data['classes']            = array_pluck(getCourses($current_year),'course_title','id');
        $data['default_class']      = null;
    	return view('lms.lmscategories.add-edit', $data);
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
    	$record = LmsCategory::getRecordWithSlug($slug);
    	if($isValid = $this->isValidRecord($record))
    		return redirect($isValid);

    	$data['record']       		= $record;
        $current_year = default_year();
        $data['classes']            = array_pluck(getCourses($current_year),'course_title','id');
        $data['default_class']      = $record->course_id;
    	$data['active_class']       = 'lms';
    	$data['title']              = getPhrase('edit_category');
    	return view('lms.lmscategories.add-edit', $data);
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
    	$record = LmsCategory::getRecordWithSlug($slug);


		$rules = [
         'category' => 'bail|required|max:60' ,
          ];
         /**
        * Check if the title of the record is changed,
        * if changed update the slug value based on the new title
        */
       $name = $request->category;
        if($name != $record->category)
            $record->slug = $record->makeSlug($name,TRUE);

       //Validate the overall request
       $this->validate($request, $rules);
    	$record->category 			= $name;
        $record->description		= $request->description;
        $record->course_id          = $request->course_name;
        $record->record_updated_by 	= Auth::user()->id;
        $record->update_stamp($request);
        $record->save();
 		 $file_name = 'catimage';
 		if ($request->hasFile($file_name))
        {

             $rules = array( $file_name => 'mimes:jpeg,jpg,png,gif|max:10000' );
              $this->validate($request, $rules);

              $record->image      = $this->processUpload($request, $record,$file_name);
              $record->update_stamp($request);
              $record->save();
        }

        flash(getPhrase('success'),getPhrase('record_updated_successfully'), 'success');
    	return redirect(URL_LMS_CATEGORIES_EDIT.$record->slug);
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
         'category'          	   => 'bail|required|max:60' ,
            ];
        $this->validate($request, $rules);
        $record = new LmsCategory();
      	$name  						= $request->category;
		$record->category 			= $name;
       	$record->slug 				= $record->makeSlug($name,TRUE);
        $record->description		= $request->description;
        $record->course_id          = $request->course_name;
        $record->record_updated_by 	= Auth::user()->id;
        $record->user_stamp($request);
        $record->save();
 		$file_name = 'catimage';
        if ($request->hasFile($file_name))
        {

            $rules = array( $file_name => 'mimes:jpeg,jpg,png,gif|max:10000' );
            $this->validate($request, $rules);
		    $this->setSettings();
            $examSettings = $this->getSettings();
	        $path = $examSettings->categoryImagepath;
	        $this->deleteFile($record->image, $path);

              $record->image      = $this->processUpload($request, $record,$file_name);
              $record->user_stamp($request);
              $record->save();
        }

        flash(getPhrase('success'),getPhrase('record_added_successfully'), 'success');
    	return redirect(URL_LMS_CATEGORIES_ADD);
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
        $record = LmsCategory::where('slug', $slug)->first();

        try{
        $this->setSettings();

        $examSettings = $this->getSettings();
        $path = IMAGE_PATH_UPLOAD_LMS_CATEGORIES;
        $r =  $record;
         if(!env('DEMO_MODE')) {
            $record->delete();
            $this->deleteFile($r->image, $path);
        }

            $response['status'] = 1;
            $response['message'] = getPhrase('record_deleted_successfully');
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
    	 return URL_LMS_CATEGORIES;
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
     public function processUpload(Request $request, $record, $file_name)
     {
         if(env('DEMO_MODE')) {
            return;
         }
         if ($request->hasFile($file_name)) {
          $settings = json_decode((new LmsSettings())->getSettings());


          $destinationPath      = $settings->categoryImagepath;

          $fileName = $record->id.'-'.$file_name.'.'.$request->$file_name->guessClientExtension();

          $request->file($file_name)->move($destinationPath, $fileName);

         //Save Normal Image with 300x300
          Image::make($destinationPath.$fileName)->fit($settings->imageSize)->save($destinationPath.$fileName);
         return $fileName;
        }
     }
}
