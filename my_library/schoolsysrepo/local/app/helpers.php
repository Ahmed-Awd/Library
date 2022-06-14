<?php

/**absent*/

use Illuminate\Support\Facades\Auth;
use App\Student;
use App\User;
use App\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Academic;

function flash($title = null, $text = null, $type = 'info')
{
    $flash = app('App\Http\Flash');

    if (func_num_args() == 0) {
        return $flash;
    }
    return $flash->$type($title, $text);
}

/**
 * Language Helper
 * @param  string|null $phrase
 * @return string
 */
function getPhrase($key = null)
{

    $phrase = app('App\Language');

    if (func_num_args() == 0) {
        return '';
    }

    return $phrase::getPhrase($key);
}

/**
 * This method fetches the specified key in the type of setting
 * @param  [type] $key          [description]
 * @param  [type] $setting_type [description]
 * @return [type]               [description]
 */
function getSetting($key, $setting_type)
{
    return App\Settings::getSetting($key, $setting_type);
}

/**
 * Language Helper
 * @param  string|null $phrase
 * @return string
 */
function isActive($active_class = '', $value = '')
{
    $value = isset($active_class) ? ($active_class == $value) ? 'active' : '' : '';
    if ($value) {
        return "class = " . $value;
    }
    return $value;
}

/**
 * This method returns the path of the user image based on the type
 * It verifies wether the image is exists or not,
 * if not available it returns the default image based on type
 * @param  string $image [Image name present in DB]
 * @param  string $type [Type of the image, the type may be thumb or profile,
 *                       by default it is thumb]
 * @return [string]      [returns the full qualified path of the image]
 */
function getProfilePath($image = '', $type = 'thumb')
{

    $obj = app('App\ImageSettings');
    $path = '';

    if ($image == '') {
        if ($type == 'profile') {
            return PREFIX . $obj->getDefaultProfilePicPath();
        }
        return PREFIX . $obj->getDefaultprofilePicsThumbnailpath();
    }


    if ($type == 'profile') {
        $path = $obj->getProfilePicsPath();
    } else {
        $path = $obj->getProfilePicsThumbnailpath();
    }
    $imageFile = $path . $image;

    if (File::exists($imageFile)) {
        return PREFIX . $imageFile;
    }

    if ($type == 'profile') {
        return PREFIX . $obj->getDefaultProfilePicPath();
    }
    return PREFIX . $obj->getDefaultprofilePicsThumbnailpath();

}

/**
 * This method returns the standard date format set by admin
 * @return [string] [description]
 */
function getDateFormat()
{
    $obj = app('App\GeneralSettings');
    return $obj->getDateFormat();
}


function getBloodGroups()
{
    return array(
        'A +ve' => 'A +ve',
        'A -ve' => 'A -ve',
        'B +ve' => 'B +ve',
        'B -ve' => 'B -ve',
        'O +ve' => 'O +ve',
        'O -ve' => 'O -ve',
        'AB +ve' => 'AB +ve',
        'AB -ve' => 'AB -ve',
    );
}

function getAge($date)
{


    // return Carbon::createFromDate(1984, 7, 17)->diff(Carbon::now())->format('%y years, %m months and %d days');
}

function getLibrarySettings()
{
    return json_decode((new App\LibrarySettings())->getSettings());
}

function getExamSettings()
{
    return json_decode((new App\ExamSettings())->getSettings());
}

/**
 * This method is used to generate the formatted number based
 * on requirement with the follwoing formatting options
 * @param  [type]  $sno    [description]
 * @param  integer $length [description]
 * @param  string $token [description]
 * @param  string $type [description]
 * @return [type]          [description]
 */
function makeNumber($sno, $length = 2, $token = '0', $type = 'left')
{

    if ($type == 'right') {
        return str_pad($sno, $length, $token, STR_PAD_RIGHT);
    }

    return str_pad($sno, $length, $token, STR_PAD_LEFT);

}

/**
 * This method returns the settings for the selected key
 * @param  string $type [description]
 * @return [type]       [description]
 */
function getSettings($type = '')
{
    if ($type == 'lms') {
        return json_decode((new App\LmsSettings())->getSettings());
    }

    if ($type == 'subscription') {
        return json_decode((new App\SubscriptionSettings())->getSettings());
    }

    if ($type == 'general') {
        return json_decode((new App\GeneralSettings())->getSettings());
    }

    if ($type == 'email') {

        $dta = json_decode((new App\EmailSettings())->getSettings());
        return $dta;
    }

    if ($type == 'attendance') {
        return json_decode((new App\AttendanceSettings())->getSettings());
    }

}

/**
 * This method returns the role of the currently logged in user
 * @return [type] [description]
 */
function getRole($user_id = 0)
{
    if ($user_id) {
        return getUserRecord($user_id)->roles()->first()->name;
    }
    return Auth::user()->roles()->first()->name;
}

/**
 * This is a common method to send emails based on the requirement
 * The template is the key for template which is available in db
 * The data part contains the key=>value pairs
 * That would be replaced in the extracted content from db
 * @param  [type] $template [description]
 * @param  [type] $data     [description]
 * @return [type]           [description]
 */
function sendEmail($template, $data)
{
    return (new App\EmailTemplate())->sendEmail($template, $data);
}
function sendNotification($template, $data,$parent,$student,Request $request)
{
    return (new App\EmailTemplate())->sendNotification($template, $data,$parent,$student);
}

/**
 * This method returns the formatted by appending the 0's
 * @param  [type] $number [description]
 * @return [type]         [description]
 */
function formatPercentage($number)
{
    return sprintf('%.2f', $number) . ' %';
}


/**
 * This method returns the user based on the sent userId,
 * If no userId is passed returns the current logged in user
 * @param  [type] $user_id [description]
 * @return [type]          [description]
 */
function getUserRecord($user_id = 0)
{
    if ($user_id) {
        return (new App\User())->withoutGlobalScope(\App\Scopes\BranchScope::class)->withoutGlobalScope(\App\Scopes\CategoryScope::class)->where('id', '=', $user_id)->first();
    }
    return Auth::user();
}

/**
 * Returns the user record with the matching slug.
 * If slug is empty, it will return the currently logged in user
 * @param  string $slug [description]
 * @return [type]       [description]
 */
function getUserWithSlug($slug = '')
{
    if ($slug) {
        return App\User::withoutGlobalScope(\App\Scopes\BranchScope::class)->where('slug', $slug)->first();
    }
    return Auth::user();
}

/**
 * This method identifies if the url contains the specific string
 * @param  [type] $str [description]
 * @return [type]      [description]
 */
function urlHasString($str)
{
    $url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    if (strpos($url, $str)) {
        return true;
    }
    return false;

}

/*function checkRole($roles)
{
    if (in_array(Auth::user()->role_id,$roles)) {
        return true;
    }
    return false;
}*/

function checkRole($roles)
{
    if(Entrust::hasRole($roles))
       return TRUE;
   return FALSE;
}

function get_user_branch()
{
    $branch_id = session()->get('branch_id');
    if($branch_id){
        return $branch_id;
    }
    return Auth::user()->branch_id;
}

/*
function getUserGrade($grade = 5)
{
    switch ($grade) {
        case 1:
            return [1];
            break;
        case 2:
            return [1, 2];
            break;
        case 3:
            return [1, 2, 3];
            break;
        case 4:
            return [1, 2, 6];
            break;
        case 5:
            return [5];
            break;
        case 6:
            return [2];
            break;
        case 7:
            return [6];
            break;
        case 8:
            return [7, 1, 2];
            break;
        case 9:
            return [8, 7, 1, 2];
            break;
        case 10:
            return [1, 2, 6, 5];
            break;

        case 11:
            return [3];
            break;
        case 12:
            return [1, 2, 5];
            break;
        case 13:
            return [5, 6];
            break;
        case 14:
            return [1, 2, 5, 3, 6];
            break;
        case 15:
            return [8, 7];
            break;
        case 16:
            return [6,10];
            break;


    }
}*/
function getUserGrade($grade = 5)
{
    switch ($grade) {
        case 1:
            return ['owner'];
            break;
       case 2:
            return ['owner', 'admin'];
            break;
       case 3:
            return ['owner', 'admin', 'staff'];
            break;
       case 4:
            return ['owner', 'admin', 'parent'];
            break;
       case 5:
            return ['student'];
            break;
       case 6:
            return ['admin'];
            break;
       case 7:
            return ['parent'];
            break;
       case 8:
            return ['librarian','owner', 'admin',];
            break;
       case 9:
            return ['assistant_librarian', 'librarian','owner', 'admin',];
            break;
       case 10:
            return ['owner', 'admin', 'parent','student','student_guide','educational_supervisor'];
            break;

       case 11:
             return ['staff'];
             break;
       case 12:
            return ['owner', 'admin','student'];
            break;
       case 13:
            return ['student','parent'];
            break;
       case 14:
            return ['owner', 'admin','student','staff','parent'];
            break;
       case 15:
            return ['assistant_librarian', 'librarian'];
            break;
        case 16:
            return ['parent','secondary_parent'];
            break;
        case 17:
            return ['admin','owner','student_guide','educational_supervisor'];
        case 18:
            return ['admin','owner','student_guide','staff'];
        case 19:
        return ['educational_supervisor','owner', 'admin', 'staff'];
        break;

    }
}


function ValidateMail($email){
    $result = filter_var( $email, FILTER_VALIDATE_EMAIL );
    return $result;
}


/**
 * Returns the appropriate layout based on the user logged in
 * @return [type] [description]
 */
function getLayout()
{
    $layout = 'layouts.student.studentlayout';
    if (Is_Owner()) {
        $layout = 'layouts.admin.adminlayout';
    }
    if (is_parent()) {
        $layout = 'layouts.parent.parentlayout';
    }

    if (is_teacher()) {
        $layout = 'layouts.staff.stafflayout';
    }

    if (is_student_guide()) {
        $layout = 'layouts.student_guide.studentGuidelayout';
    }

    if (Is_Librarian()) {
        $layout = 'layouts.librarian.librarianlayout';
    }
    if (Is_Secondary_parent()) {
      $layout = 'users.secondary-parent.dashboard';
    }
    return $layout;
}

function Is_Owner(){
    if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2){
        return true;
    }
    return false;
}
function is_parent(){
    if(Auth::user()->role_id == 6){
        return true;
    }
    return false;
}

function Is_Librarian(){
    if(Auth::user()->role_id == 7 || Auth::user()->role_id == 8){
        return true;
    }
    return false;
}

function Is_Secondary_parent(){
    if(Auth::user()->role_id == 10){
        return true;
    }
    return false;
}


function validateUser($slug)
{
    if ($slug == Auth::user()->slug) {
        return true;
    }
    return false;
}

/**
 * Common method to send user restriction message for invalid attempt
 * @return [type] [description]
 */
function prepareBlockUserMessage()
{
    flash(getPhrase('Ooops'), getPhrase('you_have_no_permission_to_access'), 'error');
    return '';
}

/**
 * Common method to send user restriction message for invalid attempt
 * @return [type] [description]
 */
function pageNotFound()
{
    flash(getPhrase('Ooops'), getPhrase('page_not_found'), 'error');
    return '';
}

function Module_state($key){
    return \App\Settings::Module_state($key);
}

function isEligible($slug)
{

    if (!checkRole(getUserGrade(17))) {

        if (!validateUser($slug)) {
            if (!checkRole(['parent']) || !isActualParent($slug)) {
                prepareBlockUserMessage();
                return false;
            }
        }
    }

    return true;
}

/**
 * This method checks wether the student belongs to the currently loggedin parent or not
 * And returns the boolean value
 * @param  [type]  $slug [description]
 * @return boolean       [description]
 */
function isActualParent($slug)
{
    return (new App\User())
        ->isChildBelongsToThisParent(
            getUserWithSlug($slug)->id,
            Auth::user()->id
        );

}

/**
 * This method returns the role name or role ID based on the type of parameter passed
 * It returns ID if role name is supplied
 * It returns Name if ID is passed
 * @param  [type] $type [description]
 * @return [type]       [description]
 */
function getRoleData($type)
{

    if (is_numeric($type)) {
        /**
         * Return the Role Name as the type is numeric
         */
        return App\Role::where('id', '=', $type)->first()->name;

    }

    //Return Role Id as the type is role name
    return App\Role::where('name', '=', $type)->first()->id;

}

/**
 * Checks the subscription details and returns the boolean value
 * @param  string $type [this is the of package]
 * @return boolean       [description]
 */
function isSubscribed($type = 'main', $user_slug = '')
{
    $user = getUserWithSlug();
    if ($user_slug) {
        $user = getUserWithSlug($user_slug);
    }

    if ($user->subscribed($type)) {
        return true;
    }
    return false;
}

function getUserName($claw){

    $user =  User::withoutGlobalScope(App\Scopes\BranchScope::class)->where('slug',$claw)->orWhere('id',$claw)->first();
    if($user){
        return $user->username;
    }
    return "";
}
function is_student(){
    if(Auth::user()->role_id == 5){
        return true;
    }
    return false;
}

/**
 * This method will send the random color to use in graph
 * The random color generation is based on the number parameter
 * As the border and bgcolor need to be same,
 * We are maintainig number parameter to send the same value for bgcolor and background color
 * @param  string $type [description]
 * @param  integer $number [description]
 * @return [type]          [description]
 */
function getColor($type = 'background', $number = 777)
{

    $hash = md5('color' . $number); // modify 'color' to get a different palette
    $color = array(
        hexdec(substr($hash, 0, 2)), // r
        hexdec(substr($hash, 2, 2)), // g
        hexdec(substr($hash, 4, 2))
    ); //b
    if ($type == 'border') {
        return 'rgba(' . $color[0] . ',' . $color[1] . ',' . $color[2] . ',1)';
    }
    return 'rgba(' . $color[0] . ',' . $color[1] . ',' . $color[2] . ',0.2)';
}


function pushNotification($channels = ['owner', 'admin'], $event = 'newUser', $options)
{

    $pusher = \Illuminate\Support\Facades\App::make('pusher');

    $pusher->trigger($channels,
        $event,
        $options
    );


}

/**
 * This method is used to return the default validation messages
 * @param  string $key [description]
 * @return [type]      [description]
 */
function getValidationMessage($key = 'required')
{
    $message = '<p ng-message="required">' . getPhrase('this_field_is_required') . '</p>';

    if ($key === 'required') {
        return $message;
    }

    switch ($key) {
        case 'minlength' :
            $message = '<p ng-message="minlength">'
                . getPhrase('the_text_is_too_short')
                . '</p>';
            break;
        case 'maxlength' :
            $message = '<p ng-message="maxlength">'
                . getPhrase('the_text_is_too_long')
                . '</p>';
            break;
        case 'pattern' :
            $message = '<p ng-message="pattern">'
                . getPhrase('invalid_input')
                . '</p>';
            break;
        case 'image' :
            $message = '<p ng-message="validImage">'
                . getPhrase('please_upload_valid_image_type')
                . '</p>';
            break;
        case 'email' :
            $message = '<p ng-message="email">'
                . getPhrase('please_enter_valid_email')
                . '</p>';
            break;

        case 'number' :
            $message = '<p ng-message="number">'
                . getPhrase('please_enter_valid_number')
                . '</p>';
            break;

        case 'confirmPassword' :
            $message = '<p ng-message="compareTo">'
                . getPhrase('password_and_confirm_password_does_not_match')
                . '</p>';
            break;
        case 'password' :
            $message = '<p ng-message="minlength">'
                . getPhrase('the_password_is_too_short')
                . '</p>';
            break;
        case 'phone' :
            $message = '<p ng-message="minlength">'
                . getPhrase('please_enter_valid_phone_number')
                . '</p>';
            break;
    }
    return $message;
}

/**
 * Returns the predefined Regular Expressions for validation purpose
 * @param  string $key [description]
 * @return [type]      [description]
 */
function getRegexPattern($key = 'name')
{
    $phone_regx = getSetting('phone_number_expression', 'site_settings');
    $pattern = array(
        'name' => '/^[a-zA-Z0-9_\'.- ]*$/',
        'email' => '/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/',
        'phone' => $phone_regx
    );
    return $pattern[$key];
}

function getPhoneNumberLength()
{
    return getSetting('site_favicon', 'site_settings');
}


function getArrayFromJson($jsonData)
{
    $result = array();
    if ($jsonData) {
        foreach (json_decode($jsonData) as $key => $value) {
            $result[$key] = $value;
        }
    }
    return $result;
}


function prepareArrayFromString($string = '', $delimeter = '|')
{

    return explode($delimeter, $string);
}

/**
 * Returns the random hash unique code
 * @return [type] [description]
 */
function getHashCode()
{
    return bin2hex(openssl_random_pseudo_bytes(20));
}

/**
 * Sends the default Currency set for the project
 * @return [type] [description]
 */
function getCurrencyCode()
{
    /*return getSetting('currency_symbol', 'site_settings');*/
    $currency = \App\Settings::where('key', 'site_settings')->first(['settings_data']);
    /*return typeOf($currency->settings_data);*/
    $currency=(array)json_decode($currency->settings_data,true);
    return $currency['currency_symbol']['value'];

}

/**
 * Returns the max records per page
 * @return [type] [description]
 */
function getRecordsPerPage()
{
    return RECORDS_PER_PAGE;
}

/**
 * Checks wether the user is eligible to use the current item
 * @param  [type]  $item_id   [description]
 * @param  [type]  $item_type [description]
 * @return boolean            [description]
 */
function isItemPurchased($item_id, $item_type, $user_id = '')
{
    return App\Payment::isItemPurchased($item_id, $item_type, $user_id);
}

function humanizeDate($target_date)
{
    $created = new \Carbon\Carbon($target_date);
    $now = \Carbon\Carbon::now();
    $difference = ($created->diff($now)->days < 1) ? getPhrase('today')
        : $created->diffForHumans($now);
    return $difference;
}


function getTimeFromSeconds($seconds)
{
    return gmdate("H:i:s", $seconds);
}

/**
 * This method appends select option to the dropdown list
 * @param [type] $list [description]
 */
function addSelectToList($list)
{
    return collect(array('' => getPhrase('select')) + collect($list)->all());
}

/**
 * This method returns the day based on the numeric value
 * If the number is -1, it will return the total week records
 *
 * @param      <type>  $dayNumber  The day number
 *
 * @return     <type>  The day.
 */
function getDay($dayNumber = -1)
{
    $days[1] = getPhrase('sun');
    $days[2] = getPhrase('mon');
    $days[3] = getPhrase('tue');
    $days[4] = getPhrase('wed');
    $days[5] = getPhrase('thu');
    $days[6] = getPhrase('fri');
    $days[7] = getPhrase('sat');

    if ($dayNumber == -1) {
        return $days;
    }
    return $days[$dayNumber];
}


function getDashboardBoxColor()
{
    $colors = ['green', 'yellow', 'blue', 'black', 'red'];
    $key = array_rand($colors);
    return 'card-' . $colors[$key];
}


function getUserSession()
{
    return session()->get('user_record');
}

function prepareStudentSessionRecord($slug = '')
{
    if ($slug == '') {
        $slug = Auth::user()->slug;
    }

    $user = App\User::where('slug', '=', $slug)->first();
    $user_record = [];
    $user_record['user'] = $user;
    $user_record['student'] = App\Student::where('user_id', '=', $user->id)->first();

    return (object)$user_record;
}

function getAcademicYears($type = 0)
{
    if (!$type) {
        return \App\Academic::where('show_in_list', '=', '1')->pluck('academic_year_title', 'id');
    }

    return \App\Academic::pluck('academic_year_title', 'id');
}

/**
 * This method will calculate the percentage of the given number
 * @param  [type] $value      [description]
 * @param  [type] $percentage [description]
 * @return [type]             [description]
 */
function calculatePercentage($value, $percentage)
{
    return $value * ($percentage / 100);
}

/**
 * This method will returns the percentage of two numbers
 * @param  [type] $value      [description]
 * @param  [type] $percentage [description]
 * @return [type]             [description]
 */
function getPercentage($value, $total)
{
    return number_format(($value / $total) * 100, 2);
}

function getPeriodNumber($number){
    if($number == 1){
        return getPhrase('First_period');
    }
    if($number == 2){
        return getPhrase('Second_period');
    }
    if($number == 3){
        return getPhrase('Third_period');
    }
    if($number == 4){
        return getPhrase('Forth_period');
    }
    if($number == 5){
        return getPhrase('Fifth_period');
    }
    if($number == 6){
        return getPhrase('Sixth_period');
    }
    if($number == 7){
        return getPhrase('Seventh_period');
    }
    if($number == 8){
        return getPhrase('Eighth_period');
    }

}

function makeAbsNotification($parent,$student,Request $request)
{
    $notifi = new \App\Notification();
    $notifi->title = getPhrase('Absence_status');
    $notifi->slug  = $notifi->makeSlug($notifi->title);
    $notifi->short_description = "dear mr $parent->name <br> your son $student->name is absent";
    $notifi->description       = "dear mr $parent->name <br> your son $student->name is absent from the first period in the day $request->attendance_date";
    $datetime = new DateTime('today');
    $notifi->valid_from =  $datetime->format('Y-m-d H:i:s');
    $datetime = new DateTime('tomorrow');
    $notifi->valid_to =  $datetime->format('Y-m-d H:i:s');
    $notifi->user_stamp($request);
    $notifi->save();
    $parent_notifi = new \App\user_notifications();
    $parent_notifi->user_id = $parent->id;
    $parent_notifi->notification_id = $notifi->id;
    $parent_notifi->user_stamp($request);
    $parent_notifi->save();
    return true;

}
/**
 * This method will return the data for bootstrap tour plugin
 * @param  [type] $key [description]
 * @return [type]      [description]
 */
function getModuleHelper($key)
{
    if (!$key) {
        return false;
    }
    return App\ModuleHelper::getRecord($key);
}


function numberToWord($num = '')
{
    $num = ( string )(( int )$num);

    if (( int )($num) && ctype_digit($num)) {
        $words = array();

        $num = str_replace(array(',', ' '), '', trim($num));

        $list1 = array(
            '',
            'one',
            'two',
            'three',
            'four',
            'five',
            'six',
            'seven',
            'eight',
            'nine',
            'ten',
            'eleven',
            'twelve',
            'thirteen',
            'fourteen',
            'fifteen',
            'sixteen',
            'seventeen',
            'eighteen',
            'nineteen'
        );

        $list2 = array(
            '',
            'ten',
            'twenty',
            'thirty',
            'forty',
            'fifty',
            'sixty',
            'seventy',
            'eighty',
            'ninety',
            'hundred'
        );

        $list3 = array(
            '',
            'thousand',
            'million',
            'billion',
            'trillion',
            'quadrillion',
            'quintillion',
            'sextillion',
            'septillion',
            'octillion',
            'nonillion',
            'decillion',
            'undecillion',
            'duodecillion',
            'tredecillion',
            'quattuordecillion',
            'quindecillion',
            'sexdecillion',
            'septendecillion',
            'octodecillion',
            'novemdecillion',
            'vigintillion'
        );

        $num_length = strlen($num);
        $levels = ( int )(($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);

        foreach ($num_levels as $num_part) {
            $levels--;
            $hundreds = ( int )($num_part / 100);
            $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ($hundreds == 1 ? '' : 's') . ' ' : '');
            $tens = ( int )($num_part % 100);
            $singles = '';

            if ($tens < 20) {
                $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '');
            } else {
                $tens = ( int )($tens / 10);
                $tens = ' ' . $list2[$tens] . ' ';
                $singles = ( int )($num_part % 10);
                $singles = ' ' . $list1[$singles] . ' ';
            }
            $words[] = $hundreds . $tens . $singles . (($levels && ( int )($num_part)) ? ' ' . $list3[$levels] . ' ' : '');
        }

        $commas = count($words);

        if ($commas > 1) {
            $commas = $commas - 1;
        }

        $words = implode(', ', $words);

        //Some Finishing Touch
        //Replacing multiples of spaces with one space
        $words = trim(str_replace(' ,', ',', trimAll(ucwords($words))), ', ');
        if ($commas) {
            $words = strReplaceLast(',', ' and', $words);
        }

        return $words;
    } else {
        if (!(( int )$num)) {
            return 'Zero';
        }
    }
    return '';
}

function trimAll($str, $what = null, $with = ' ')
{
    if ($what === null) {
        //  Character      Decimal      Use
        //  "\0"            0           Null Character
        //  "\t"            9           Tab
        //  "\n"           10           New line
        //  "\x0B"         11           Vertical Tab
        //  "\r"           13           New Line in Mac
        //  " "            32           Space

        $what = "\\x00-\\x20";    //all white-spaces and control chars
    }

    return trim(preg_replace("/[" . $what . "]+/", $with, $str), $what);
}


function strReplaceLast($search, $replace, $str)
{
    if (($pos = strrpos($str, $search)) !== false) {
        $search_length = strlen($search);
        $str = substr_replace($str, $replace, $pos, $search_length);
    }
    return $str;
}

/**
 * This menthod returns the academic title passing the academic id as parameter
 */

function getacademictitle($academic_id)
{

    if ($academic_id == 0) {
        return '-';
    }

    return App\Academic::where('id', '=', $academic_id)->first()->academic_year_title;
}

/**
 * This method returns  of course tile and
 * if course is completed returns description
 * @param  [integer] $course_id [description]
 * @param  [integer] $year      [description]
 * @param  [integer] $semister  [description]
 * @return [string]             [description]
 */

function getcoursetitle($course_id, $year, $semister)
{

    if ($course_id == 0) {
        return '-';
    }

    $title = App\Course::where('id', '=', $course_id)->get()->first();

    if ($title->course_dueration > 1 && $title->is_having_semister != 0) {
        $data = '';
        $data = $title->course_title . ' - ' . $year . ' - ' . $semister;
        return $data;
    }
    if ($title->course_dueration > 1 && $title->is_having_semister == 0) {
        $data = '';
        $data = $title->course_title . ' - ' . $year . ' ' . 'Year';
        return $data;
    }

    return $title->course_title;
}

/**
 * This method returns transfer-details of  academic title
 */

function gettransferacademictitle($type, $academic_id, $year, $semister)
{

    if ($academic_id == 0) {
        return '-';
    }
    if ($type == 'detained') {
        return '';
    }
    if ($academic_id != 0 && $year == -1 && $semister == -1) {

        return '';
    }

    return App\Academic::where('id', '=', $academic_id)->first()->academic_year_title;
}

/**
 * This method returns transfer-details of course tile and
 * if course is completed returns description
 * @param  [integer] $course_id [description]
 * @param  [integer] $year      [description]
 * @param  [integer] $semister  [description]
 * @return [string]             [description]
 */
function gettransfercoursetitle($type, $course_id, $year, $semister)
{

    if ($course_id == 0) {
        return '-';
    }
    if ($type == 'detained') {
        return '-';
    }

    $title = App\Course::where('id', '=', $course_id)->get()->first();

    if ($title->course_dueration > 1 && $title->is_having_semister != 0) {

        if ($year == -1 && $semister == -1) {
            return 'Completed';
        }

        $data = '';
        $data = $title->course_title . ' - ' . $year . ' - ' . $semister;
        return $data;
    }
    if ($title->course_dueration > 1 && $title->is_having_semister == 0) {

        if ($year == -1 && $semister == -1) {
            return 'Completed';
        }

        $data = '';
        $data = $title->course_title . ' - ' . $year . ' ' . 'Year';
        return $data;
    }

    if ($title->course_dueration <= 1 && $year == -1 && $semister == -1) {
        return 'Completed';
    }

    return $title->course_title;
}



function number_to_word($number){
    if($number == 1){
        return getPhrase('first');
    }
    if($number == 2){
        return getPhrase('second');
    }
    if($number == 3){
        return getPhrase('third');
    }
    return false;
}

/**
 * This method return the master book asset name
 * @param  [int] $master_assetid [description]
 * @return [string]                 [description]
 */
function getmasterassetname($master_assetid)
{

    $name = App\LibraryMaster::where('id', '=', $master_assetid)->get()->first();
    $data = '';
    $data = $name->title;
    return $data;
}

/**
 * This method will return the default academic id set in site settings
 * @return [type] [description]
 */
function getDefaultAcademicId()
{
    return getSetting('default_academic_year_id', 'site_settings');
}

/**
 * This method will return the default course parent ID set in site settings
 * @return [type] [description]
 */
function getDefaultParentCourseId()
{
    return getSetting('default_parent_course_id', 'site_settings');
}

/**
 * This method will return student info
 * @return [type] [description]
 */

function getStudentInfo($slug){
    $student_id = \App\User::where('slug',$slug)->pluck('id')->first();
    $student    = \App\Student::withoutGlobalScope(App\Scopes\BranchScope::class)->where('user_id',$student_id)->first();
    $academic_id = default_year();
    //get the needed info
    $data['current_academic_year'] = \App\Academic::where('id',$academic_id)->pluck('academic_year_title')->first();
    $data['current_grade']         = \App\Course::withoutGlobalScope(\App\Scopes\CategoryScope::class)->where('id',$student->course_parent_id)->pluck('course_title')->first();
    $data['current_class']         = \App\Course::withoutGlobalScope(\App\Scopes\CategoryScope::class)->where('id',$student->course_id)->pluck('course_title')->first();
    $data['current_semester'] = "";

    $currentSemester = default_sem($academic_id);
        if($currentSemester == 1){
            $data['current_semester']      = "the_first";
        }else{
            $data['current_semester']      = "the_second";
        }
    //return the data
    return $data;
}

//check if the user is a teacher
function is_teacher(){
    if(Auth::user()->role_id == 3 || Auth::user()->role_id == 9){
        return true;
    }
    return false;
}

function is_student_guide(){
    if(Auth::user()->role_id == 11){
        return true;
    }
    return false;
}

//check if this subject is for the teacher
function teacher_subject($slug){
    $subject_id  = \App\Subject::where('slug',$slug)->pluck('id')->first();
    $his_subject = \App\SubjectPreference::where('subject_id',$subject_id)->where('user_id',Auth::user()->id)->first();
    if($his_subject){
        return true;
    }
    return false;
}


function teacher_quizzes(){
    $ids = \App\CourseSubject::where('staff_id',Auth::user()->id)->pluck('id');
    return $ids;
}
//get the main tables in database
function get_main_tables(){
  $TABLES_IN_DB='Tables_in_'.env('DB_DATABASE');
    $tables = DB::select('SHOW TABLES');
    $main_tables = array();
    //course_subject
    $ignored = ['certificatetemplates','parenttimingsetmap','timetable','timingset','homeworks_student'];
    foreach ($tables as $table){
        if(in_array($table->$TABLES_IN_DB,$ignored))
        {
            continue;
        }
        $columns = Schema::getColumnListing($table->$TABLES_IN_DB);
        if(in_array('slug',$columns)) {
            array_push($main_tables,$table->$TABLES_IN_DB);
        }
    }
    return $main_tables;
}

function get_title_column($table){

    if($table == 'languages'){
        return 'language';
    }
    if($table == 'libraryassettypes'){
        return 'asset_type';
    }

    $columns = Schema::getColumnListing($table);
    foreach ($columns as $column){
        if (strpos($column, 'title') !== false || strpos($column, 'name') !== false || strpos($column, 'category') !== false) {
            return $column;
            break;
        }
    }
    return false;

}

function get_courses(){
    $courses = [];

    if(Auth::user()->role_id == 5){
        $student_course = Student::where('user_id',Auth::user()->id)->pluck('course_parent_id')->first();
        array_push($courses,$student_course);
    }
    else if(Auth::user()->role_id == 6){
        $students = User::join('students','users.id','=','students.user_id')
            ->select('students.course_parent_id')
            ->where('users.parent_id','=',Auth::user()->id)
            ->get();
        foreach ($students as $student){
            array_push($courses,$student->course_parent_id);
        }
    }
    else{
        return false;
    }
    return $courses;
}

function subTopics($id)
{
    return Topic::where('parent_id', '=', $id)->get();
}



function getCategory($id,$table){
    return   DB::table($table)->where('course_id',$id)->where('branch_id',Auth::user()->branch_id)->get();
}
function isAdmin(){
    if(Auth::user()->role_id == 2 || Auth::user()->role_id == 1){
        return true;
    }
    return false;
}

function isTeacher(){
    if(Auth::user()->role_id == 3){
        return true;
    }
    return false;
}

function getCourses($year,$sem = null){
    if($sem == null){
        $sem = default_sem($year);
    }
    if(isTeacher()){
        return \App\Course::join('academic_course','courses.id','=','academic_course.course_id')
            ->join('course_subject','courses.id','=','course_subject.course_parent_id')
            ->where('course_subject.academic_id',$year)
            ->where('course_subject.semister',$sem)
            ->where('courses.parent_id',0)
            ->where('courses.category_id',Auth::user()->category_id)
            ->where('course_subject.staff_id',Auth::user()->id)
            ->select(['courses.id','courses.slug','courses.course_title'])
            ->groupBy('courses.id')
            ->get();
    }
    return \App\Course::join('academic_course','courses.id','=','academic_course.course_id')
        ->where('academic_course.academic_id',$year)
        ->where('courses.parent_id',0)
        ->where('courses.category_id',Auth::user()->category_id)
        ->select(['courses.id','courses.slug','courses.course_title','courses.graduated_course'])
        ->get();
}



function getTeacherCourses2($year,$sem = null,$staffSlug = null){

    if($staffSlug != null) {
      $teacher = App\User::where('slug', $staffSlug)->first();
      $staffId = $teacher->id;
    }else{
        $staffId = Auth::user()->id;
    }
    if($sem == null){
        $sem = default_sem(default_year());
    }
    return \App\Course::join('course_subject','courses.id','=','course_subject.course_parent_id')
                      ->join('academic_course','courses.id','=','academic_course.course_id')
                      ->select(['courses.id','courses.slug','courses.course_title'])
                      ->where('courses.parent_id',0)
                      ->where('course_subject.semister',$sem)
                      ->where('course_subject.academic_id',$year)
                      ->where('course_subject.staff_id',$staffId)
                      ->groupBy('course_subject.course_parent_id')
                      ->get();
}

function getTeacherClasses($sem,$year,$staffSlug,$course){

    if($staffSlug != null) {
        $teacher = App\User::where('slug', $staffSlug)->first();
        $staffId = $teacher->id;
    }else{
        $staffId = Auth::user()->id;
    }

    return \App\Course::join('course_subject','courses.id','=','course_subject.course_id')
        ->select(['courses.id','courses.slug','courses.course_title'])
        ->where('course_subject.course_parent_id',$course)
        ->where('course_subject.academic_id',$year)
        ->where('course_subject.semister',$sem)
        ->where('course_subject.staff_id',$staffId)
        ->groupBy('course_subject.course_id')
        ->get();
}



function getSemesters($id){
    return \App\AcademicSemester::where('academic_id',$id)->select(['sem_num'])->get();
}

function SemesterName($number){
    if($number == 1){
        return "first_term";
    }
    if($number == 2){
        return "second_term";
    }
    return "none";
}

function getSubjects($year,$semester,$course){
    return \App\CourseSubject::join('subjects','course_subject.subject_id','=','subjects.id')
                             ->where('academic_id',$year)
                             ->where('semister',$semester)
                             ->where('course_parent_id',$course)
                             ->groupBy('course_subject.subject_id')
                             ->select(['course_subject.id as id','subjects.id as subject_id','subjects.slug','subjects.subject_title'])
                             ->get();
}

function getSubjects2($year,$semester,$course){
    return \App\CourseSubject::join('subjects','course_subject.subject_id','=','subjects.id')
        ->where('academic_id',$year)
        ->where('semister',$semester)
        ->where('course_id',$course)
        ->groupBy('course_subject.subject_id')
        ->select(['course_subject.id as id','subjects.id as subject_id','subjects.slug','subjects.subject_title'])
        ->get();
}

function getTeacherSubjects2($year,$semester,$course,$slug){
  $teacherSlug = Auth::user()->id;
  if($slug != null) {
    $teacher = App\User::where('slug', $slug)->first();
    $teacherSlug = $teacher->id;
  }
    return \App\CourseSubject::join('subjects','course_subject.subject_id','=','subjects.id')
        ->where('academic_id',$year)
        ->where('semister',$semester)
        ->where('course_id',$course)
        ->where('staff_id',$teacherSlug)
        ->groupBy('course_subject.subject_id')
        ->select(['course_subject.id','course_subject.subject_id','course_subject.slug','subjects.slug','subjects.subject_title'])
        ->get();
}

function getTeacherSubjects($year,$semester,$course){
    return \App\CourseSubject::join('subjects','course_subject.subject_id','=','subjects.id')
        ->where('academic_id',$year)
        ->where('semister',$semester)
        ->where('course_parent_id',$course)
        ->where('staff_id',Auth::user()->id)
        ->select(['course_subject.id','course_subject.subject_id','course_subject.slug','subjects.slug','subjects.subject_title'])
        ->groupBy('subjects.subject_title')
        ->get();
}

function getSubjectDetails($id){
    $subject = \App\CourseSubject::where('id',$id)->first();
 //$subject = \App\CourseSubject::where('course_parent_id',$request->parent_id)->groupBy('subject_id')->get();
    $data['year']    = '';
    $data['sem']     = '';
    $data['course']  = '';
    $data['subject'] = '';
    if($subject){
        $data['year']    = \App\Academic::where('id',$subject->academic_id)->select(['id','academic_year_title'])->first();
        $data['sem']     = $subject->semister;
        $data['course']  = \App\Course::where('id',$subject->course_parent_id)->select(['id','course_title'])->first();
        $data['subject'] = \App\Subject::where('id',$subject->subject_id)->select(['id','subject_title'])->first();
        $data['subject_realid'] = $subject->subject_id;
        $data['course_parent_id'] = $subject->course_parent_id;
    }
    return $data;

}

function getCourseName($id){
    return \App\Course::where('id',$id)->pluck('course_title')->first();
}

function getPeriod(){
    $data = \App\TimingsetDetails::where('is_break',0)->get();
    $result = [];
    $i = 1;
    foreach ($data as $item){
        $result[$i] = $item->period_name;
        $i++;
    }
    return $result;
}

function get_user_id_from_slug($slug){
    return User::where('slug',$slug)->pluck('id')->first();
}


function getMaxID($table_name)
{
    $recored =  DB::table($table_name)->orderBy('id', 'desc')->first();
    $id = $recored->id + 1;
    return $id;
}

function get_sesmters(){
    $current_academic_id = new Academic();
    $year = $current_academic_id->getCurrentAcademic()->id;
    $data = \App\AcademicSemester::where('academic_id',$year)->get();
    $new = [];
    foreach ($data as $item){
        $new[$item->sem_num] = numberToWord($item->sem_num);
    }
    return $new;
}

function get_subject_topics($subject,$course,$sem){
    return Topic::where('parent_id',0)->where('course_id',$course)->where('subject_id',$subject)->where('semester_num',$sem)->get();
}

function get_subject_main_topics($subject,$course){
    return Topic::where('parent_id',0)->where('course_id',$course)->where('subject_id',$subject)->get();
}

function icon($TorF){
    if($TorF == true){
        return "<i class=\"fa fa-check text-success\"></i>";
    }
    return "<i class=\"fa fa-close text-danger\"></i>";
}

function get_my_bid()
{
    return Auth::user()->branch_id;
}

function default_year()
{
    $current_academic_id = new Academic();
    return $current_academic_id->getCurrentAcademic()->id;
}

function default_sem($year)
{
    $semister = new App\AcademicSemester();
    $current_semster = $semister->getCurrentSemeterOfAcademicYear($year);
    if($current_semster){
        $current_semster = $current_semster->sem_num;
    }else{$current_semster = 1;}
    return $current_semster;
}

function get_Topics($year,$course,$subject,$sem){
    $data = \App\Topic::where('parent_id',0)->where('academic_id',$year)->where('course_id',$course)->where('subject_id',$subject)->where('semester_num',$sem)->get();
//    $data = \App\Topic::where('parent_id',0)->get();
    $topics = [];
    foreach ($data as $item){
        array_push($topics,$item);
        $subdata = \App\Topic::where('parent_id',$item->id)->get();
        foreach ($subdata as $subtopic){
            array_push($topics,$subtopic);
        }
    }
    return $topics;
}

function get_topic_name($id){
    return Topic::where('id',$id)->pluck('topic_name')->first();
}

function make_title($year,$sem,$course,$subject)
{
    $ytitle = Academic::where('slug',$year)->pluck('academic_year_title')->first();
    $ctitle = \App\Course::where('slug',$course)->pluck('course_title')->first();
    $stitle = \App\Subject::where('slug',$subject)->pluck('subject_title')->first();
    if($sem == 1){
        $semtitle = getPhrase('First_semester');
    }
    else if($sem == 2){
        $semtitle = getPhrase('Second_semester');
    }
    else{
        $semtitle = getPhrase('');
    }
    return $ytitle.'-'.$ctitle.'-'.$semtitle.'-'.$stitle;

}

function is_teachers_subject($year,$sem,$course,$subject)
{
    $flag = \App\CourseSubject::where('year',$year)->where('semister',$sem)->where('course_parent_id',$course)->where('subject_id',$subject)->where('staff_id',Auth::user()->id)->first();
    if($flag){
        return true;
    }
    return false;
}

function get_category_name($id)
{
    return \App\Category::where('id',$id)->pluck('category_name')->first();
}
function get_branch_name($id)
{
    return \App\Branch::where('id',$id)->pluck('name')->first();
}

function get_subject_name_from_course_subject($id)
{
    $sid = \App\CourseSubject::where('id',$id)->pluck('subject_id')->first();
    return get_subject_name($sid);
}

function get_subject_name($id)
{
    return \App\Subject::where('id',$id)->pluck('subject_title')->first();
}

function get_users_nu()
{
    $records = User::join('roles', 'users.role_id', '=', 'roles.id')
        ->select([
            'users.name',
            'image',
            'id_number',
            'email',
            'roles.display_name',
            'roles.name as role_name',
            'login_enabled',
            'role_id',
            'slug',
            'users.id',
            'users.status',
            'users.created_by_user','users.updated_by_user','users.created_by_ip','users.updated_by_ip','users.created_at','users.updated_at'
        ])
        ->where('users.role_id','!=','5')
        ->orderBy('users.updated_at', 'desc');
    $records2 = User::join('roles', 'users.role_id', '=', 'roles.id')
        ->select([
            'users.name',
            'image',
            'id_number',
            'email',
            'roles.display_name',
            'roles.name as role_name',
            'login_enabled',
            'role_id',
            'slug',
            'users.id',
            'users.status',
            'users.created_by_user','users.updated_by_user','users.created_by_ip','users.updated_by_ip','users.created_at','users.updated_at'
        ])
        ->where('users.role_id','=','5')
        ->where('users.category_id','=',Auth::user()->category_id)
        ->orderBy('users.updated_at', 'desc');
    $data = $records->union($records2)->get();
    return count($data);
}

function course_in_year($year,$course){

    $record = App\Course::join('academic_course', 'academic_course.course_id', '=', 'courses.id')
        ->join('academics','academics.id','=','academic_course.academic_id')
        ->select([
            'academics.academic_year_title',
            'academics.id',
            'courses.parent_id as course_parent_id',
            'courses.id as course_id',
            'courses.created_by_user','courses.updated_by_user','courses.created_by_ip','courses.updated_by_ip','courses.created_at','courses.updated_at'
        ])
        ->where('courses.category_id',Auth::user()->category_id)
        ->where('academics.slug',$year)
        ->where('courses.slug',$course)->get()->count();
    if($record == 0){
        return true;
    }
    return false;
}

function change_att_symbols($char){
    if($char == 'A'){
        return "<p style='color:red'>غ</p>";
    }
    if($char == 'L'){
        return "<p style='color:blue'>س</p>";
    }
    if($char == 'P'){
        return "<p style='color:green'>ح</p>";
    }
    else{
        return "<p style='color:black'>-</p>";
    }

}

function get_student_class($id){
    return Student::where('user_id',$id)->first()->course_parent_id;
}


function get_role_name($id){
    return \App\Role::where('id',$id)->first()->name;
}

function Delete_HW_files($slug){
    $homework = \App\HomeWork::withoutGlobalScope(\App\Scopes\DeleteScope::class)->where('slug',$slug)->first();
    if($homework->file){
        $flag = Delete_File($homework->file,'homeworks');
    }
    $student_homeworks = \App\HomeworkStudent::where('homework_id',$homework->id)->get();
    foreach ($student_homeworks as $hw){
        $hw_replays = \App\HomeWorkReplay::where('homeworks_student_id',$hw->id)->get();
        foreach ($hw_replays as $replay){
            if($replay->file){
                $flag = Delete_File($replay->file,'homeworks');
            }
        }
    }
    return $flag;
}

function Delete_File($file,$path){
    if(file_exists(getcwd()."/uploads/".$path."/".$file)){
        unlink(getcwd()."/uploads/".$path."/".$file);
        return $file;
    }else{
        return "file not found";
    }
}

function getMessageFiles($id)
{
    return \App\MessagesFile::where('messages_id',$id)->get();
}
