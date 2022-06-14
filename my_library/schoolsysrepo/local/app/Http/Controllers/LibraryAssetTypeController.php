<?php
namespace App\Http\Controllers;
use \App;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\LibraryAssetType;
use Yajra\Datatables\Datatables;
use DB;
use Auth;
use Exception;
use Illuminate\Support\Facades\Redirect;

class LibraryAssetTypeController extends Controller
{

    public function __construct()
    {
    	$this->middleware('auth');
        if(!Module_state('library_Management')){
            prepareBlockUserMessage();
            return Redirect::to('/')->send();
        }
    }


    /**
     * Course listing method
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
         if(!checkRole(getUserGrade(8)))
      {
        prepareBlockUserMessage();
        return back();
      }

        $data['active_class']       = 'library';
        $data['title']              = getPhrase('library_assets');
        $data['layout']             = getLayout();
        $data['module_helper']      = getModuleHelper('assettype_list');
    	return view('library.libraryassets.list', $data);
    }

    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatable()
    {


         $records = LibraryAssetType::select([
         	'asset_type', 'description', 'id','slug','created_by_user','updated_by_user','created_by_ip','updated_by_ip','created_at','updated_at']);

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
                            <li><a href="'.URL_LIBRARY_ASSETS_EDIT.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>

                            <li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->slug.'\');"><i class="fa fa-trash"></i>'. getPhrase("delete").'</a></li>
                             '.$view.'
                        </ul>
                    </div>';
            })
        ->editColumn('asset_type',function($records){
            return $records->asset_type.' ('.$records->id.')';
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
         if(!checkRole(getUserGrade(8)))
      {
        prepareBlockUserMessage();
        return back();
      }

    	$data['record']         	= FALSE;
    	$data['active_class']       = 'library';
    	$data['title']              = getPhrase('create_asset');
      $data['layout']             = getLayout();
      $data['module_helper']      = getModuleHelper('assettype_create');
    	return view('library.libraryassets.add-edit', $data);
    }

    /**
     * This method loads the edit view based on unique slug provided by user
     * @param  [string] $slug [unique slug of the record]
     * @return [view with record]
     */
    public function edit($slug)
    {
         if(!checkRole(getUserGrade(8)))
      {
        prepareBlockUserMessage();
        return back();
      }

    	$record = LibraryAssetType::getRecordWithSlug($slug);
    	if($isValid = $this->isValidRecord($record))
    		return redirect($isValid);

    	$data['record']       		= $record;
    	$data['active_class']       = 'library';
    	$settings['is_eligible_for_fine'] = 0;//$record->is_eligible_for_fine;
    	$settings['is_having_max_fine_limit'] = 0;$record->is_having_max_fine_limit;
       	$data['settings']			= json_encode($settings);
    	$data['title']              = getPhrase('edit_asset');
        $data['layout']             = getLayout();
    	return view('library.libraryassets.add-edit', $data);
    }

    /**
     * Update record based on slug and reuqest
     * @param  Request $request [Request Object]
     * @param  [type]  $slug    [Unique Slug]
     * @return void
     */
    public function update(Request $request, $slug)
    {
         if(!checkRole(getUserGrade(8)))
      {
        prepareBlockUserMessage();
        return back();
      }

    	$record = LibraryAssetType::getRecordWithSlug($slug);
		$rules = [
         'asset_type'          	   =>
                            'bail|required|max:30|unique:libraryassettypes,asset_type,'.$record->id ,
            ];
         /**
        * Check if the title of the record is changed,
        * if changed update the slug value based on the new title
        */
       $name = $request->asset_type;
        if($name != $record->asset_type)
            $record->slug = $record->makeSlug($name);

       $is_eligible_for_fine  = 0;//$request->is_eligible_for_fine;

       	$is_having_max_fine_limit 	= 0;
   		$maximum_fine_amount 		= 0;
   		$fine_per_day 				= 0;

       if($is_eligible_for_fine)
       {
       		$is_having_max_fine_limit 	= $request->is_having_max_fine_limit;
       		$maximum_fine_amount 		= $request->maximum_fine_amount;
       		$fine_per_day 				= $request->fine_per_day;

			$rules['is_having_max_fine_limit'] 	= 'bail|required|numeric';
			$rules['maximum_fine_amount'] 		= 'bail|required|numeric';
			$rules['fine_per_day'] 				= 'bail|required|numeric';
       }

       //Validate the overall request
       $this->validate($request, $rules);

        $record->asset_type 		= $name;
        $record->slug 			    = $record->makeSlug($name);
        $record->is_eligible_for_fine
        							= 0;
        $record->description		= $request->description;
        $record->maximum_fine_amount= $maximum_fine_amount;
        $record->is_having_max_fine_limit
        							= $is_having_max_fine_limit;
        $record->fine_per_day 		= $fine_per_day;
        $record->maximum_issuable   = 1;
        $record->maximum_days_to_return = 1;
        $record->maximum_advanced_reservations = 0;
        $record->record_updated_by 	= Auth::user()->id;
        $record->update_stamp($request);
        $record->save();

        flash(getPhrase('success'),getPhrase('record_updated_successfully'), 'success');
    	return redirect('library/assets');
    }

    /**
     * This method adds record to DB
     * @param  Request $request [Request Object]
     * @return void
     */
    public function store(Request $request)
    {
         if(!checkRole(getUserGrade(8)))
      {
        prepareBlockUserMessage();
        return back();
      }

	    ////////////////////////////////////////////////////
    	// Split the validation rules as per the request  //
	    ////////////////////////////////////////////////////
    	$rules = [
         'asset_type'              => 'bail|required|max:30|unique:libraryassettypes,asset_type' ,
            ];

       $is_eligible_for_fine  = 0;

       	$is_having_max_fine_limit 	= 0;
   		$maximum_fine_amount 		= 0;
   		$fine_per_day 				= 0;

       if($is_eligible_for_fine)
       {
       		$is_having_max_fine_limit 	= $request->is_having_max_fine_limit;
       		$maximum_fine_amount 		= $request->maximum_fine_amount;
       		$fine_per_day 				= $request->fine_per_day;

			$rules['is_having_max_fine_limit'] 	= 'bail|required|numeric';
			$rules['maximum_fine_amount'] 		= 'bail|required|numeric';
			$rules['fine_per_day'] 				= 'bail|required|numeric';
       }

       //Validate the overall request
       $this->validate($request, $rules);


    	$record 					= new LibraryAssetType();
        $name 		  		  		= $request->asset_type;
        $record->asset_type 		= $name;
        $record->slug 			    = $record->makeSlug($name);
        $record->is_eligible_for_fine
        							= 0;
        $record->description		= $request->description;
        $record->maximum_fine_amount= $maximum_fine_amount;
        $record->is_having_max_fine_limit
        							= $is_having_max_fine_limit;
        $record->fine_per_day 		= $fine_per_day;

        $record->maximum_issuable   = 1;
        $record->maximum_days_to_return = 1;
        $record->maximum_advanced_reservations = 0;
        $record->record_updated_by 	= Auth::user()->id;
        $record->user_stamp($request);
        $record->save();

        flash(getPhrase('success'),getPhrase('record_added_successfully'), 'success');
    	return redirect('library/assets');
    }


    /**
     * Delete Record based on the provided slug
     * @param  [string] $slug [unique slug]
     * @return Boolean
     */
    public function delete($slug)
    {
         if(!checkRole(getUserGrade(8)))
      {
        prepareBlockUserMessage();
        return back();
      }
       try{
          if(!env('DEMO_MODE')) {
        LibraryAssetType::where('slug', $slug)->delete();
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
    	return URL_LIBRARY_ASSETS;
    }

}
