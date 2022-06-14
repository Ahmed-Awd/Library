<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use \App;
use App\Feedback;
use Yajra\Datatables\Datatables;
use DB;
use Auth;

class FeedbackController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');


    }

    /**
     * Course listing method
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {

        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }

        $data['active_class'] = 'feedback';
        $data['layout'] = getLayout();
        $data['title'] = getPhrase('feed_backs');
        return view('feedbacks.list', $data);
    }


    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatable()
    {

        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }

        $records = array();


        $records = Feedback::join('users', 'users.id', '=', 'feedbacks.user_id')
            ->select(['title', 'image', 'name', 'users.username', 'users.role_id', 'subject', 'feedbacks.slug', 'feedbacks.id', 'feedbacks.updated_at', 'feedbacks.created_at', 'feedbacks.created_by_user', 'feedbacks.updated_by_user', 'feedbacks.created_by_ip', 'feedbacks.updated_by_ip']);
        // ->orderBy('updated_at', 'desc');

        foreach ($records as $record) {
            $record->role_id = \App\Role::where('id', $record->role_id)->pluck('name')->first();
        }

        return Datatables::of($records)
            ->addColumn('action', function ($records) {

                $records->created_by_user_name = App\User::get_user_name($records->created_by_user);
                $records->updated_by_user_name = App\User::get_user_name($records->updated_by_user);
                $view = "<li><a onclick='pop_it($records)'><i class=\"fa fa-eye\"></i>" . getPhrase('view_record_history') . "</a></li>";

                $link_data = '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                           <li><a href="' . URL_FEEDBACK_VIEW . $records->slug . '"><i class="fa fa-eye"></i>' . getPhrase("view") . '</a></li>' . $view;


                $temp = '';
                if (checkRole(getUserGrade(1))) {
                    $temp .= ' <li><a href="javascript:void(0);" onclick="deleteRecord(\'' . $records->slug . '\');"><i class="fa fa-trash"></i>' . getPhrase("delete") . '</a></li>';
                }

                $temp .= '</ul></div>';


                $link_data .= $temp;
                return $link_data;
            })
            ->editColumn('title', function ($records) {
                return '<a href="' . URL_FEEDBACK_VIEW . $records->slug . '">' . $records->title . '</a>';
            })
            ->editColumn('role_id', function ($records) {
                $role = App\Role::where('id', $records->role_id)->pluck('name')->first();
                $role = getPhrase($role);
                return $role;
            })
            ->editColumn('image', function ($records) {
                $image = getProfilePath($records->image);

                return '<img src="' . $image . '" height="60" width="60"  />';
            })
            ->removeColumn('id')
            ->removeColumn('slug')
            ->removeColumn('created_by_user')
            ->removeColumn('updated_by_user')
            ->removeColumn('created_by_ip')
            ->removeColumn('updated_by_ip')
            ->removeColumn('updated_at')
            ->make();
    }

    /**
     * This method loads the create view
     * @return void
     */
    public function create()
    {

        if (checkRole(getUserGrade(2))) {
            return back();
        }

        $data['record'] = FALSE;
        $data['layout'] = getLayout();
        $data['active_class'] = 'feedback';
        $data['title'] = getPhrase('give_feedback');
        return view('feedbacks.add-edit', $data);
    }

    /**
     * This method adds record to DB
     * @param  Request $request [Request Object]
     * @return void
     */
    public function store(Request $request)
    {
        $files = json_decode($request->question_file);


        $rules = [
            'title' => 'bail|required|max:40',
            'subject' => 'bail|required|max:40',
            'description' => 'bail|required',
        ];
        $this->validate($request, $rules);
        $admins = App\User::where('role_id', 1)->orWhere('role_id', 2)->get();

        $record = new Feedback();
        $name = $request->title;
        $record->title = $name;
        $record->slug = $record->makeSlug($name);
        $record->subject = $request->subject;
        $record->description = $request->description;
        $record->user_id = Auth::user()->id;
        $record->user_stamp($request);
        $record->save();

        if (count($files) > 0) {
            foreach ($files as $file) {
                $mfile = new App\FeedbackFile();
                $mfile->file_name = $file;
                $mfile->feedback_id = $record->id;
                $mfile->save();
            }
        }

        $insert['feedback_id'] = $record->id;
        foreach ($admins as $admin) {
            $insert['user_id'] = $admin->id;
            App\user_feedback::insert($insert);
        }
        flash(getPhrase('success'), getPhrase('feedback_submitted_successfully'), 'success');
        return redirect(URL_FEEDBACK_SEND);
    }

    public function details($slug)
    {

//    	$record = Feedback::where('slug','=',$slug)->first();
        $record = Feedback::join('users', 'users.id', '=', 'feedbacks.user_id')
            ->select(['title', 'image', 'description', 'name', 'phone', 'email', 'users.username', 'users.role_id', 'subject', 'feedbacks.slug', 'feedbacks.id', 'feedbacks.updated_at'])
            ->where('feedbacks.slug', '=', $slug)
            ->first();

        $record->files = App\FeedbackFile::where('feedback_id',$record->id)->get();

        $record->user_type = \App\Role::where('id', $record->role_id)->pluck('name')->first();

        App\user_feedback::viewed($record->id);

        if ($isValid = $this->isValidRecord($record))
            return redirect($isValid);

        $data['record'] = $record;
        $data['layout'] = getLayout();
        $data['active_class'] = 'feedback';
        $data['title'] = getPhrase('feedback_details');

        return view('feedbacks.details', $data);

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
        if (!checkRole(getUserGrade(2))) {
            return URL_FEEDBACK_SEND;
        }
        return URL_FEEDBACK_VIEW;

    }

    public function delete($slug)
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
        $record = Feedback::where('slug', $slug)->first();
        $files = App\FeedbackFile::where('feedback_id',$record->id)->get();
        foreach ($files as $file){
            $this->deleteFile($file->file_name);
        }
        DB::statement("delete from feedbacks where id = $record->id");


        $response['status'] = 1;
        $response['message'] = getPhrase('deleted_successfully');
        return json_encode($response);
    }


    public function upload(Request $request)
    {
        $destinationPath = "uploads/feedback/";
        $record1 = rand(1, 200000);
        $finalname = [];

        if ($request->hasFile('file')) {

            foreach ($request->file as $file) {
                $filename = $file->getClientOriginalName();
                $name = $record1 . $filename;
                array_push($finalname, $name);
                $file->move($destinationPath, $name);
                $record1++;
            }
            return json_encode(['state' => 'success', 'desc' => getPhrase('upload_success'), 'files' => $finalname]);
        }

        return json_encode(['state' => 'failed', 'desc' => getPhrase('upload_failed')]);
    }

    public function deleteFile($file)
    {

        if (file_exists(getcwd() . "/uploads/feedback/" . $file)) {
            unlink(getcwd() . "/uploads/feedback/" . $file);
        } else {
            return "file not found";
        }

    }

}
