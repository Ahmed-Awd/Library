<?php

namespace App\Http\Controllers;

use App\UsersLogin;
use Illuminate\Http\Request;
use function Symfony\Component\VarDumper\Tests\Fixtures\bar;
use Yajra\Datatables\Datatables;
use DB;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Session;
class UsersLoginController extends Controller
{
    public function index()
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
        $data['title'] = getPhrase('users_records');
        $data['active_class'] = 'users';
        return view('users-login.list',$data);
    }

    public function getDatatable()
    {
        $records = UsersLogin::join('users','users_login.users_id','=','users.id')->select([
            'users_login.id','users.name','users_login.users_id','users_login.table_name','users_login.created_at','users_login.created_by_ip'
        ]);
        return Datatables::of($records)
        ->addColumn('user_type',function ($records)
        {
            return getPhrase(getRole($records->users_id));
        })
            ->editColumn('id', function ($records) {
             $id= getMaxID($records->table_name);
            return abs($records->id-$id);

            })
            ->removeColumn('table_name')
            ->make();

    }

    public function deleteRecords()
    {
        DB::table('users_login')->truncate();
        return back();
    }
    public function switchUser($slug)
    {
        //dd(Auth::user()->slug);
        if(Auth::user()->role_id==3) {
            session()->put('restore_teacher', Auth::user()->slug);
        } else {
          session()->put('restore_slug', Auth::user()->slug);
        }
        Auth::logout();
        $user = User::withoutGlobalScope(\App\Scopes\BranchScope::class)->where('slug', $slug)->get()->first();
        Auth::login($user);

        //$vrequest=Session::get('restore_slug');
        return redirect('dashboard');
    }
    public function switchAdmin($slug='')
    {

        Auth::logout();
        $user = User::withoutGlobalScope(\App\Scopes\BranchScope::class)->where('slug', Session::get('restore_slug'))->get()->first();
        Auth::login($user);
        session()->put('restore_slug', null);
        return redirect('dashboard');
    }

    public function switchStaff($slug)
    {
        //dd(Auth::user()->slug);
        if(Auth::user()->role_id==9){
        session()->put('restore_staff', Auth::user()->slug);
        Auth::logout();
        $user = User::withoutGlobalScope(\App\Scopes\BranchScope::class)->where('slug', $slug)->get()->first();
        Auth::login($user);

        //$vrequest=Session::get('restore_slug');
        return redirect('dashboard');
    }
    return $this->switchUser($slug);
    }
    public function switchSupervisor($slug='')
    {

        Auth::logout();
        $user = User::withoutGlobalScope(\App\Scopes\BranchScope::class)->where('slug', Session::get('restore_staff'))->get()->first();
        Auth::login($user);
        session()->put('restore_staff', null);
        return redirect('dashboard');
    }
    
    public function switchTeacher($slug='')
    {

        Auth::logout();
        $user = User::withoutGlobalScope(\App\Scopes\BranchScope::class)->where('slug', Session::get('restore_teacher'))->get()->first();
        Auth::login($user);
        session()->put('restore_teacher', null);
        return redirect('dashboard');
    }


}
