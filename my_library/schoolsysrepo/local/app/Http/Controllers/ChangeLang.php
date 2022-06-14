<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use \App;
use App\Http\Requests;
use App\User;
use App\Language;
use App\Student;
use App\GeneralSettings as Settings;
use Image;
use ImageSettings;
use Yajra\Datatables\Datatables;
use DB;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Hash;
use Input;
use Auth;

class ChangeLang extends Controller
{
    //

    public function __construct(){
        $currentUser = \Auth::user();

        $this->middleware('auth');

    }

    /**
     * load the language view with the languages data
     */

    public function index(){

        $data['active_class']       = 'languages';
        $data['title']              = getPhrase('languages');
        $data['layout']             = getLayout();
        $data['module_helper']      = getModuleHelper('languages-list');
        $data['languages']          = Language::all();
        $data['default_lang']       = $this->get_user_default_lang();
        return view('languages.change_lang', $data);

    }

    /**
     * get the user default language if exist if not return the default one
     */
    public function get_user_default_lang(){
        return  User::where('id',Auth::user()->id)->first()->default_lang;
    }

    /**
     * change the user default language
     */

    public function make_default($id){

        $record = Language::where('id',$id)->first();
        if($isValid = $this->isValidRecord($record)){
            return redirect($isValid);
        }
        User::where('id',Auth::user()->id)->update(['default_lang' => $id]);
        Language::resetLanguage();

        flash(getPhrase('success'),getPhrase('record_updated_successfully'), 'success');

        return redirect(URL_LANGUAGES_USER_LIST);
    }

    /**
     * check if the record is valid
     */

    public function isValidRecord($record)
    {
        if ($record === null) {

            flash(getPhrase('Ooops'), getPhrase("page_not_found"), 'error');
            return $this->getRedirectUrl();
        }
        return FALSE;
    }
}
