<?php

namespace App\Http\Middleware;

use App\SupervisorStaff;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class SupervisorLinks
{
    public function handle($request, Closure $next)
    {
        $currentTeacher = explode('/', url()->current());
        $currentTeacher=$currentTeacher[count($currentTeacher)-1];
        $teacherId=User::where('slug',$currentTeacher)->first(['id'])->id;
        $authenticatedUser=Auth::user()->id;
        $relationResult=SupervisorStaff::where('supervisor_id',$authenticatedUser)->where('staff_id',$teacherId)->first();
        if ($relationResult == null && Auth::user()->role_id!=1) {
            flash(getPhrase('not_allowed'), getPhrase('you_are_not_allowed_to_access'), 'error');
            return redirect()->back();
        }else
        {
            return $next($request);
        }
    }
}