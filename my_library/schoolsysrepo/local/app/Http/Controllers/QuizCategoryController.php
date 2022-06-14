<?php
namespace App\Http\Controllers;
use \App;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\QuizCategory;
use Yajra\Datatables\Datatables;
use DB;
use Auth;
use Image;
use ImageSettings;
use File;
use Exception;
use Illuminate\Support\Facades\Redirect;
class QuizCategoryController extends Controller
{

    public function __construct()
    {
    	$this->middleware('auth');


    }

    protected  $examSettings;

    public function setExamSettings()
    {
        $this->examSettings = getExamSettings();
    }

    public function getExamSettings()
    {
        return $this->examSettings;
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

        $data['active_class']       = 'exams';
        $data['title']              = getPhrase('quiz_categories');
        $data['module_helper']      = getModuleHelper('quiz-category -list');
    	return view('exams.quizcategories.list', $data);
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
            $records = QuizCategory::join('courses','quizcategories.course_id','=','courses.id')
                ->join('course_subject','courses.id','=','course_subject.course_parent_id')
                ->select([  'quizcategories.category',
                            'courses.course_title',
                            'quizcategories.image',
                            'quizcategories.description',
                            'quizcategories.id',
                            'quizcategories.slug',
                            'quizcategories.created_by_user',
                            'quizcategories.updated_by_user',
                            'quizcategories.created_by_ip',
                            'quizcategories.updated_by_ip',
                            'quizcategories.created_at',
                            'quizcategories.updated_at'])
                ->where('courses.category_id',Auth::user()->category_id)
                ->where('course_subject.staff_id',Auth::user()->id)
                ->groupBy('quizcategories.id');
                // ->orderBy('updated_at', 'desc');
        }else{
            $records = QuizCategory::join('courses','quizcategories.course_id','=','courses.id')->select([
                'quizcategories.category','courses.course_title', 'quizcategories.image', 'quizcategories.description', 'quizcategories.id','quizcategories.slug','quizcategories.created_by_user','quizcategories.updated_by_user','quizcategories.created_by_ip','quizcategories.updated_by_ip','quizcategories.created_at','quizcategories.updated_at'])
                ->where('courses.category_id',Auth::user()->category_id);
                // ->orderBy('updated_at', 'desc');
        }

        $this->setExamSettings();
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
                            <li><a href="'.URL_QUIZ_CATEGORY_EDIT.'/'.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>'.$view;


        $temp = '';
        if(checkRole(getUserGrade(1))) {
        $temp .= '<li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->slug.'\');"><i class="fa fa-trash"></i>'. getPhrase("delete").'</a></li>';
        }
        $temp .='</ul></div>';

        $link_data = $link_data.$temp;
            return $link_data;
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
            $settings = $this->getExamSettings();
            $path = $settings->categoryImagepath;
            $image = $path.$settings->defaultCategoryImage;
            if($records->image)
                $image = $path.$records->image;
            return '<img src="'.PREFIX.$image.'" height="100" width="100" />';
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
    	$data['active_class']       = 'exams';
    	$data['title']              = getPhrase('create_category');
        $data['module_helper']      = getModuleHelper('create-quiz-category');
        $current_year = default_year();
        $data['classes']            = array_pluck(getCourses($current_year),'course_title','id');
        $data['default_class']      = null;
    	return view('exams.quizcategories.add-edit', $data);
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

        $record = QuizCategory::getRecordWithSlug($slug);
        
    	if($isValid = $this->isValidRecord($record))
    		return redirect($isValid);

    	$data['record']       		= $record;
        $current_year = default_year();
        $data['classes']            = array_pluck(getCourses($current_year),'course_title','id');
        $data['default_class']      = $record->course_id;
    	$data['active_class']       = 'exams';
    	$data['title']              = getPhrase('edit_category');
    	return view('exams.quizcategories.add-edit', $data);
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

    	$record = QuizCategory::getRecordWithSlug($slug);
		$rules = [
         'category'          => 'bail|required|max:60',
          'catimage'         => 'bail|mimes:png,jpg,jpeg|max:2048',
            'course_name' => 'required'
          ];
         /**
        * Check if the title of the record is changed,
        * if changed update the slug value based on the new title
        */
       $name = $request->category;
        if($name != $record->category)
            $record->slug = $record->makeSlug($name);

       //Validate the overall request
        $this->validate($request, $rules);
    	$record->category 			= $name;
        $record->description		= $request->description;
        $record->record_updated_by 	= Auth::user()->id;
        $record->course_id          = $request->course_name;
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
        
    	return redirect(URL_QUIZ_CATEGORY_EDIT.'/'.$record->slug);
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
// catimage
	    $rules = [
         'category'          	   => 'bail|required|max:60' ,
         'catimage'                => 'bail|mimes:png,jpg,jpeg|max:2048',
            'course_name'    => 'required'
            ];
        $this->validate($request, $rules);
        $record = new QuizCategory();
      	$name  						=  $request->category;
		$record->category 			= $name;
       	$record->slug 				= $record->makeSlug($name);
        $record->description		= $request->description;
        $record->record_updated_by 	= Auth::user()->id;
        $record->course_id          = $request->course_name;
        $record->user_stamp($request);
        $record->save();
 		 $file_name = 'catimage';
        if ($request->hasFile($file_name))
        {

            $rules = array( $file_name => 'mimes:jpeg,jpg,png,gif|max:10000');
            $this->validate($request, $rules);
		    $this->setExamSettings();
            $examSettings = $this->getExamSettings();
	        $path = $examSettings->categoryImagepath;
            $this->deleteFile($record->image, $path); 
            
            $record->image      = $this->processUpload($request, $record,$file_name);
            $record->user_stamp($request);
            $record->save();
        } 
        flash(getPhrase('success'),getPhrase('record_added_successfully'), 'success');
    	return redirect(URL_QUIZ_CATEGORY_ADD);
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

        $record = QuizCategory::where('slug', $slug)->first();
            try{
            $this->setExamSettings();

            $examSettings = $this->getExamSettings();
            $path = $examSettings->categoryImagepath;
            if(!env('DEMO_MODE')) {
                $record->delete();
                $this->deleteFile($record->image, $path);
            }
            $response['status'] = 1;
            $response['message'] = getPhrase('category_deleted_successfully');

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
    	return URL_QUIZ_CATEGORIES;
    }

     public function deleteFile($record, $path, $is_array = FALSE)
    {
        if(env('DEMO_MODE')) {
            return;
        }

        $files = array();
        $files[] = $path.$record;
        File::delete($files);
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
          $examSettings = getExamSettings();

          $destinationPath      = $examSettings->categoryImagepath;

          $fileName = $record->id.'-'.$file_name.'.'.$request->$file_name->guessClientExtension();

          $request->file($file_name)->move($destinationPath, $fileName);

         //Save Normal Image with 300x300
          Image::make($destinationPath.$fileName)->fit($examSettings->imageSize)->save($destinationPath.$fileName);
         return $fileName;
        }
     }
}
