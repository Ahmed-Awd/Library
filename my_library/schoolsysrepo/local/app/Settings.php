<?php

namespace App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Mockery\Exception;
use App\Scopes\DeleteScope;
use App\Scopes\BranchScope;

class Settings extends Model
{
    protected $table = "settings";


    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new DeleteScope);
    }

    public static function getRecordWithSlug($slug)
    {
        return Settings::where('slug', '=', $slug)->first();
    }

    public static function getSocialKeys(){
        $record = Settings::where('slug', 'social-logins')->get()->first();
        $data = getArrayFromJson($record->settings_data);
            //$data['facebook_client_ids']->value = "awd";
            foreach ($data as $key => $value) {
                try {
                    $data[$key]->value = Crypt::decrypt($value->value);
                } catch (DecryptException $e) {
                    return $e;
                }
                // echo json_encode($value)."<br>";
            }

        return $data;
    }
    //allowed_sys_files

    public static function Social_switch(){
        $record = Settings::where('slug', 'module')->first();
        $values = json_decode($record->settings_data);
        $switch['facebook'] = $values->facebook_login->value;
        $switch['google'] = $values->google_plus_login->value;
        return $switch;
    }

    public static function get_extensions(){
        $record = Settings::where('slug', 'allowed_sys_files')->first();
        $values = json_decode($record->settings_data);
        return $values->student_achievement_file_extensions;
    }

    public static function get_HW_extensions(){
        $record = Settings::where('slug', 'allowed_HW_files')->first();
        $values = json_decode($record->settings_data);
        return $values->homework_allowed_files;
    }

    public static function get_message_extensions(){
        $record = Settings::where('slug', 'allowed_messages_files')->first();
        $values = json_decode($record->settings_data);
        return $values->message_allowed_files;
    }
    public static function get_feedback_extensions(){
        $record = Settings::where('slug', 'allowed_feedback_files')->first();
        $values = json_decode($record->settings_data);
        return $values->feedback_allowed_files;
    }

    public static function get_default_theme(){
        $record = Settings::where('slug', 'site-settings')->first();
        $values = json_decode($record->settings_data);
        //dd($values);
        return $values->current_theme->value;
    }

    public static function getMassages(){
        $record    = Settings::where('slug', 'messaging-system')->first();
        $types     = getArrayFromJson($record->settings_data);
        $keys      = array_keys($types);
        $available = [];
        foreach ($keys as $key){
            if($types[$key]->value == "1"){
                $id = Role::where('name',$key)->pluck('id')->first();
                array_push($available,$id);
            }
        }
        return $available;
    }

    public static function Module_state($key){
        $settings = Settings::where('key','module')->first();
        $setting_data = (array) json_decode($settings['settings_data']);
        if(!array_key_exists($key, $setting_data))
        {
            return getPhrase('invalid_setting');
        }
        return $setting_data[$key]->value;
    }

    public static function get_att_logo(){

        $settings = Settings::where('key','site_settings')->first();
        $setting_data = (array) json_decode($settings['settings_data']);
        if(Auth::user()->category_id==1){
            if(!array_key_exists('site_logo_attendance_header', $setting_data))
            {
                return getPhrase('invalid_setting');
            }
            return $setting_data['site_logo_attendance_header']->value;
        }
        else {
            if(!array_key_exists('site_logo_attendance_header2', $setting_data))
            {
                return getPhrase('invalid_setting');
            }
            return $setting_data['site_logo_attendance_header2']->value;
        }
    }
    /**
     * This method validates and sends the setting value
     * @param  [type] $setting_type [description]
     * @param  [type] $key          [description]
     * @return [type]               [description]
     */
    public static function getSetting($key, $setting_module)
    {

       $setting_module     = trim(strtolower($setting_module));
        $key      =  trim(strtolower($key));

        return Settings::isSettingAvailable($key, $setting_module);
    }


    /**
     * This method finds the key is available in module or not
     * If available, It will return the value of that setting_module[key]
     * If not available, it will fetch from db and stores in session and returns the value
     * @param  [type]  $key            [description]
     * @param  [type]  $setting_module [description]
     * @return boolean                 [description]
     */
    public static function isSettingAvailable($key, $setting_module)
    {


        if(!session()->has('settings'))
        {

            if(!Settings::loadSettingsModule($setting_module))
                return getPhrase('invalid_setting');
        }

      $settings =(array) json_decode(session('settings'));

      /**
       * Check if key exists in specified module settings data
       * If not exists return invalid setting
       */
      if(!array_key_exists($setting_module, $settings)) {


            if(!Settings::loadSettingsModule($setting_module))
            {
                return getPhrase('invalid_setting');
            }

         $settings =(array) json_decode(session('settings'));
        }

        $sub_settings = (array) $settings[$setting_module];
        if(!array_key_exists($key, $sub_settings))
        {
            return getPhrase('invalid_setting');
        }
            return $sub_settings[$key]->value;
    }

    /**
     * This method fetches the setting module and
     * Get the record with the sent key from DB
     * Validate the record, if not valid return false
     * Append the record to existing setting varable
     * @param  [type] $setting_module [description]
     * @return [type]                 [description]
     */
    public static function loadSettingsModule($setting_module)
    {

        $setting_record = Settings::where('key','=',$setting_module)->first();

        if(!$setting_record)
            return FALSE;

        $data = json_decode($setting_record->settings_data);
        $global_settings =(array) json_decode(session('settings'));
        unset($global_settings[$setting_module]);
        $global_settings[$setting_module] = $data;


        session()->put('settings', json_encode($global_settings));

        return TRUE;

    }




}
