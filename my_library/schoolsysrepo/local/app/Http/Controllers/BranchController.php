<?php

namespace App\Http\Controllers;

use App\Branch;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;
use App\User;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
        if(!Module_state('branches')){
            pageNotFound();
            return back();
        }
        $data['title'] = getPhrase('branches_list');
        $data['active_class'] = 'master_settings';
        return view('branch.list',$data);

    }

    public function getDatatable(){

        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
        $records = Branch::select(['id','name','slug','created_at','updated_at','updated_by_ip','created_by_ip','created_by_user','updated_by_user']);
        return Datatables::of($records)
            ->addColumn('action', function ($records) {
                $records->created_by_user_name = User::get_user_name($records->created_by_user);
                $records->updated_by_user_name = User::get_user_name($records->updated_by_user);
                $view = "<li><a onclick='pop_it($records)'><i class=\"fa fa-eye\"></i>".getPhrase('view_record_history')."</a></li>";

                $extra = '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                                                    <li><a href="'.URL_BRANCHES_EDIT.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>
                                                    '.$view.'
                                                    <li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->slug.'\');"><i class="fa fa-trash"></i>'. getPhrase("delete").'</a></li>';
                $temp = "";
                $extra .= $temp.'</ul></div>';
                return $extra;
            })
            ->removeColumn('created_by_user')
            ->removeColumn('updated_by_user')
            ->removeColumn('created_by_ip')
            ->removeColumn('updated_by_ip')
            ->removeColumn('created_at')
            ->removeColumn('updated_at')
            ->removeColumn('slug')
            ->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
        if(!Module_state('branches')){
            pageNotFound();
            return back();
        }
        $data['record']         	= FALSE;
        $data['active_class']       = 'master_settings';
        $data['layout']             = getLayout();
        $data['title']              = getPhrase('create_branch');
        $data['button_name']        = getPhrase('create');
        return view('branch.add-edit',$data);
    }

    public function edit($slug)
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
        if(!Module_state('branches')){
            pageNotFound();
            return back();
        }
        $record   = Branch::where('slug',$slug)->first();
        if(!$record){
            flash(getPhrase('Ooops'), getPhrase("page_not_found"), 'error');
            return back();
        }
        $data['record']         	= $record;
        $data['active_class']       = 'master_settings';
        $data['layout']             = getLayout();
        $data['title']              = getPhrase('update_branch');
        $data['button_name']        = getPhrase('update');
        return view('branch.add-edit',$data);
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'bail|required|max:50|min:3',
        ]);
        $record = new Branch;
        $record->name = $request->name;
        $record->created_by_ip   = $request->ip();
        $record->created_by_user = Auth::user()->id;
        $record->slug = $record->makeSlug($record->name, true);
        $record->save();
        flash(getPhrase('success'), getPhrase('record_added_successfully'), 'success');
        return redirect(URL_BRANCHES_CREATE);
    }



    public function update(Request $request, $slug)
    {
        $record   = Branch::where('slug',$slug)->first();
        if(!$record){
            flash(getPhrase('Ooops'), getPhrase("page_not_found"), 'error');
            return back();
        }
        $this->validate($request, [
            'name' => 'bail|required|max:50|min:3|unique:branch,name,'.$record->id,
        ]);
        $record->name = $request->name;
        $record->update_stamp($request);
        $record->save();
        flash(getPhrase('success'), getPhrase('record_updated_successfully'), 'success');
        return redirect(URL_BRANCHES_EDIT.$slug);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        if(!checkRole(getUserGrade(2)))
        {
            prepareBlockUserMessage();
            return back();
        }
        Branch::where('slug',$slug)->delete();
        $response['status'] = 1;
        $response['message'] = getPhrase('record_deleted_successfully');
        return json_encode($response);
    }
}
