<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App;
use App\Http\Requests;
use App\User;
use App\GeneralSettings as Settings;
use Image;
use ImageSettings;
use Yajra\Datatables\Datatables;
use DB;
use Illuminate\Support\Facades\Hash;
use Excel;
use Input;
use File;
use App\OneSignalApp;
use Exception;
use App\LibraryIssue;
use Form;
class AttendanceOperationsDashboardController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function index()
    {

      if(!checkRole(getUserGrade(17)))
      {
        prepareBlockUserMessage();
        return back();
      }
        $settings = \App\Settings::where('key', 'module')->first(['settings_data']);
        $settings = json_decode($settings->settings_data);
        if($settings->attendance_and_departure->value != 1)
        {
            prepareBlockUserMessage();
            return back();
        }
      
      $data['active_class']       = 'attendance';
      $data['title']              = getPhrase('Attendance_and_departure');
      $data['layout']             = getLayout();
      $data['module_helper']      = getModuleHelper('attendance-dashboard');
      return view('attendance.attendancedashboard.dashboard', $data);
    }
}   