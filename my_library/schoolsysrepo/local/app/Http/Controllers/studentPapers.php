<?php

namespace App\Http\Controllers;

use App\Settings;
use App;
use App\student_documents;
use App\User;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class studentPapers extends Controller
{

    public function __construct()
    {
        $currentUser = \Auth::user();
        $this->middleware('auth');
    }

    public function upload(Request $request){

        $flag = 0;
        $data =  \App\Settings::get_extensions();
        $extn = str_replace(".","",$data->value);
        $extn = explode(",",$extn);
        //$request->$file_name->guessClientExtension()
        if($request->hasFile('file')) {
            $file_extn = $request->file('file')->extension();
            foreach ($extn as $key => $value){
                if($value == $file_extn){
                    $flag = 1;
                }
            }
            if($file_extn == 'mpga' && in_array("mp3", $extn)){
                $flag = 1;
            }
            if($flag == 0){
                return json_encode(['state' => 'failed','desc' => getPhrase('invalid_file_type')]);
            }
            $value = $this->processUpload($request, 'file');
            return json_encode(['state' => 'success','desc' => getPhrase('upload_success'),'file' => $value]);
        }
        return json_encode(['state' => 'failed','desc' => getPhrase('upload_failed')]);
    }


    public function table($slug){

        $data['user'] = User::where('slug',$slug)->first();
        if(!$data['user']){
            flash(getPhrase('Ooops'), getPhrase("page_not_found"), 'error');
            return redirect('users/student');
        }
        $data['records'] = student_documents::where('users_id',$data['user']->id)->get();
        $data['layout'] = getLayout();
        $data['title'] = getPhrase('Achievements');
        $data['active_class'] = 'users';
        return view('student.papers.list',$data);
    }

    public function getDatatable($slug){
        $user    = User::where('slug',$slug)->first();
        $records = student_documents::where('users_id',$user->id)
                                    ->select('id','name','type','file','created_at','updated_at','updated_by_ip','created_by_ip','created_by_user','updated_by_user');
                                    // ->orderBy('created_at', 'desc');
        return Datatables::of($records)
            ->addColumn('action', function ($records) {
                $records->created_by_user_name = App\User::get_user_name($records->created_by_user);
                $records->updated_by_user_name = App\User::get_user_name($records->updated_by_user);
                $view = "<li><a onclick='pop_it($records)'><i class=\"fa fa-eye\"></i>".getPhrase('view_record_history')."</a></li>";
                $link_data = '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">'.$view.'</ul></div>';
                return $link_data;
            })
            ->editColumn('type', function ($records) {
                return getPhrase($records->type);
            })
            ->editColumn('file', function ($records) {
                if($records->type == 'image'){
                    return "<a href=".IMAGE_PATH_UPLOAD_STUDENT_PAPERS.$records->file." download><img src=".IMAGE_PATH_UPLOAD_STUDENT_PAPERS.$records->file." width='200' height='150'></a>";
                }else if ($records->type == 'video'){
                    return "<video width='200' height='150' controls>
                                <source src=".IMAGE_PATH_UPLOAD_STUDENT_PAPERS.$records->file.">
                            </video>";
                }else if ($records->type == 'application'){
                    return "<a href=".IMAGE_PATH_UPLOAD_STUDENT_PAPERS.$records->file." download>
                                <img border='0' src='".PREFIX."images/document.jpg' width=\"200\" height=\"120\">
                            </a>";
                }else if($records->type == 'audio'){
                    return "<audio controls>
                                <source src=".IMAGE_PATH_UPLOAD_STUDENT_PAPERS.$records->file.">
                            </audio>";
                }
                else{
                    return "";
                }
            })
            ->removeColumn('id')
            ->removeColumn('created_by_user')
            ->removeColumn('updated_by_user')
            ->removeColumn('created_by_ip')
            ->removeColumn('updated_by_ip')
            ->removeColumn('created_at')
            ->removeColumn('updated_at')
            ->make();
    }


    public function index($slug){

        $data['user'] = User::where('slug',$slug)->first();
        if(!$data['user']){
            flash(getPhrase('Ooops'), getPhrase("page_not_found"), 'error');
            return redirect('users/student');
        }
        if (!checkRole(getUserGrade(3))) {
            prepareBlockUserMessage();
            return back();
        }
        $data['records'] = student_documents::where('users_id',$data['user']->id)->get();
        $role = 'student';
        $user = getUserRecord();
        $role = getRoleData($user->role_id);
        $data['role']=$role;
        $data['layout'] = getLayout();
        $data['active_class'] = 'users';
        $data['heading'] = getPhrase('users');
        $data['title'] = getPhrase('Achievements');
        $data['module_helper'] = getModuleHelper('users-list');

        return view('student.papers.create',$data);
    }

    public function getData($slug){

        $user = User::where('slug',$slug)->first();
        $records = student_documents::where('users_id',$user->id)->get();
        return json_encode(['records' => $records]);
    }

    public function update(Request $request,$slug){

        $user = User::where('slug', $slug)->get()->first();

        $this->validate($request, [
            'name.*' => 'required|string',
            'type.*' => 'required|string',
            'file.*' => 'required|string'
        ]);

        if(count($request->name) == count($request->type) && count($request->type)  == count($request->file)){
            student_documents::where('users_id',$user->id)->delete();
            for($i=0;$i< count($request->name) ;$i++){
                $record = new student_documents();
                $record->users_id = $user->id;
                $record->name     = $request->name[$i];
                $record->type     = $request->type[$i];
                $record->file     = $request->file[$i];
                $record->user_stamp($request);
                $record->update_stamp($request);
                $record->save();
            }
            flash(getPhrase('success'), getPhrase('record_updated_successfully'), 'success');
            return redirect('student/papers/create/' . $user->slug);
        }
        flash(getPhrase('Ooops'), getPhrase("record_update_failed"), 'error');
        return redirect('student/papers/create/' . $user->slug);
    }

    public function processUpload(Request $request, $file_name)
    {
        if(env('DEMO_MODE')) {
            return;
        }
        $record1 = rand(1, 200000);
        $record2 = rand(1, 200000);
        if ($request->hasFile($file_name)) {
            $imageObject = new App\ImageSettings();
            $destinationPath = "uploads/student_papers/";
            $fileName = $record1 . '-' . $file_name . '-' . $record2 . '.' . $request->file($file_name)->extension();
        }
        $request->file($file_name)->move($destinationPath, $fileName);
        return $fileName;
    }


}
