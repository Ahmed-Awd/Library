<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Scopes\DeleteScope;
use App\QuizCategory;
use App\Academic;
use Illuminate\Support\Facades\Auth;
use App\GeneralSettings as Settings;

Route::get('/', function () {

    if (Auth::check()) {
        return redirect('dashboard');
    }
    return redirect(URL_USERS_LOGIN);
});

if (env('DB_DATABASE') == '') {

    Route::get('/', 'InstallatationController@index');

    Route::get('/install', 'InstallatationController@index');
    Route::post('/update-details', 'InstallatationController@updateDetails');
    Route::post('/install', 'InstallatationController@installProject');
}


if (env('DEMO_MODE')) {
    Event::listen('eloquent.saving: *', function ($model) {
        return false;
    });
    Event::listen('eloquent.deleting: *', function ($model) {
        return false;
    });

}

// Route::get('install/reg', 'InstallatationController@reg');
Route::post('install/register', 'InstallatationController@registerUser');


Route::get('dashboard', 'DashboardController@index');
Route::get('dashboard/testlang', 'DashboardController@testLanguage');




// Authentication Routes...
Route::get('login', 'Auth\AuthController@getLogin');


// Route::get('login', 'Auth\AuthController@getLogin');
Route::post('login', 'Auth\AuthController@postLogin');
Route::post('auth/facebook','socialAuth@facebook');
Route::post('auth/google','socialAuth@google');


Route::get('logout', function () {

    if (Auth::check()) {
        flash(getPhrase('success'), getPhrase('logged_out_successfully'), 'success');
    }
    session()->forget('branch_id');
    Auth::logout();
    \App\Language::resetLanguage();
    return redirect(URL_USERS_LOGIN);
});

Route::get('parent-logout', ['middleware' => 'stopOrOn:parent'], function () {
    if (Auth::check()) {
        flash(getPhrase('Ooops'), getPhrase('parents_module_is_not_available'), 'error');
    }
    Auth::logout();
    return redirect(URL_USERS_LOGIN);
});


// Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration Routes...
// Route::get('register', 'Auth\AuthController@getRegister');
// Route::post('register', 'Auth\AuthController@postRegister');

// Forgot Password Routes...
// Route::get('forgot-password', 'PasswordController@postEmail');
Route::post('users/forgot-password', 'Auth\PasswordController@postEmail');
Route::get('password/reset/{slug?}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');


Route::get('languages/list', 'NativeController@index');
Route::get('languages/getList', [
    'as' => 'languages.dataTable',
    'uses' => 'NativeController@getDatatable'
]);

Route::get('languages/add', 'NativeController@create');
Route::post('languages/add', 'NativeController@store');
Route::get('languages/edit/{slug}', 'NativeController@edit');
Route::patch('languages/edit/{slug}', 'NativeController@update');
Route::delete('languages/delete/{slug}', 'NativeController@delete');

Route::get('languages/make-default/{slug}', 'NativeController@changeDefaultLanguage');
Route::get('languages/update-strings/{slug}', 'NativeController@updateLanguageStrings');
Route::get('languages/remove-string/{slug}&{key}', 'NativeController@removeString');
Route::patch('languages/update-strings/{slug}', 'NativeController@saveLanguageStrings');


Route::get('languages/change', 'ChangeLang@index');
Route::get('languages/change/make-default/{id}', 'ChangeLang@make_default');

//Departments
Route::get('departments', 'DepartmentsController@index');
Route::get('departments/add-department', 'DepartmentsController@createDepartment');
Route::post('departments/add-department', 'DepartmentsController@storeDepartment');
Route::get('departments/edit-department/{slug}', 'DepartmentsController@editDepartment');
Route::patch('departments/edit-department/{slug}', 'DepartmentsController@updateDepartment');
Route::delete('departments/delete/{id}', 'DepartmentsController@delete');
Route::get('departments/getDepartments', [
    'as' => 'departments.dataTable',
    'uses' => 'DepartmentsController@getDatatable'
]);


//Users
//Users ---dashboard
Route::get('users/dashboard', 'UsersDashboardController@index');
Route::get('users/import', 'UsersController@importUsers');
Route::get('teachers/import', 'UsersController@importTeachers');
//importTeachers
Route::post('users/import', 'UsersController@readExcel');
Route::post('teachers/import', 'UsersController@readTeacherExcel');
//readTeacherExcel

Route::get('users/create', 'UsersController@create');
Route::get('users/staff-inactive/{role}', 'UsersController@staff_inactivelist');
Route::delete('users/delete/{slug}', 'UsersController@delete');
Route::post('users/create/{role?}', 'UsersController@store');
Route::get('users/edit/{slug}', 'UsersController@edit');
Route::patch('users/edit/{slug}', 'UsersController@update');
Route::get('users/profile/{slug}', 'UsersController@show');
Route::get('users/{users?}', 'UsersController@index');
Route::get('users/profile/{slug}', 'UsersController@show');
Route::get('users/details/{slug}', 'UsersController@details');
Route::post('users/change-status', 'UsersController@changeStatus');
Route::get('users/details/promotions/{slug}', 'UsersController@transfers');
Route::get('users/details/library/{slug}', 'UsersController@student_librarydetails');
Route::get('users/details/library/get-list/{id}', 'UsersController@getlibraryhistory');
Route::get('staff/details/{slug}', 'UsersController@staffDetails');

Route::get('users/settings/{slug}', 'UsersController@settings');
Route::patch('users/settings/{slug}', 'UsersController@updateSettings');

Route::get('users/change-password/{slug}', 'UsersController@changePassword');
Route::patch('users/change-password/{slug}', 'UsersController@updatePassword');

Route::get('users/import', 'UsersController@importUsers');
Route::post('users/import', 'UsersController@readExcel');

Route::get('users/import-report', 'UsersController@importResult');
Route::get('users/{role?}', 'UsersController@index');

Route::post('users/import/get-excel-information', 'UsersController@getExcelUploadInformation');

//student papers routes

Route::get('student/papers/create/{slug}','studentPapers@index');
Route::post('student/papers/upload','studentPapers@upload');
Route::post('student/papers/update/{slug}','studentPapers@update');
Route::get('student/papers/getData/{slug}','studentPapers@getData');
Route::get('student/papers/list/{slug}','studentPapers@table');
Route::get('student/papers/getList/{slug}', [
    'as' => 'papers.dataTable',
    'uses' => 'studentPapers@getDatatable'
]);
//end


Route::get('users/list/getList/{role_name?}', [
    'as' => 'users.dataTable',
    'uses' => 'UsersController@getDatatable'
]);

Route::get('users/staff-inactive/getList/{slug}', [
    'as' => 'users-staff_inactive.dataTable',
    'uses' => 'UsersController@getStaffInactiveList'
]);
// Route::get('users/list/getList/{role_name?}', 'UsersController@getDatatable');


//Staff
Route::patch('staff/profile/edit/general/{id}', 'StaffController@updateGeneralDetails');
Route::patch('staff/profile/edit/personal/{id}', 'StaffController@updatePersonalDetails');
Route::patch('staff/profile/edit/contact/{id}', 'StaffController@updateContactDetails');
Route::get('staff/profile/edit/{slug}/{tab?}', 'StaffController@edit');
Route::post('staff/profile/edit/status', 'StaffController@editstatus');
Route::get('staff/profile/{slug}', 'StaffController@show');

//Staff subject preference
Route::get('staff/subjects/preferences/{slug}', 'SubjectPreferencesController@subjectPreferences');
Route::post('staff/subjects/preferences/{slug}', 'SubjectPreferencesController@update');

//supervisors
  //from admin side
//Route::get('mastersettings/supervisor/assign-staff', 'SupervisorController@index');
Route::get('mastersettings/supervisor/assign-staff/{slug}', 'SupervisorController@assignStuff');
Route::post('mastersettings/assign-staff/check-status', 'SupervisorController@checkStatus');
/*Route::get('mastersettings/supervisor/getList', [
    'as' => 'supervisors.dataTable',
    'uses' => 'SupervisorController@getDatatable'
]);*/
Route::post('mastersettings/supervisor/assign-staff/{slug}', 'SupervisorController@updateStaffSupervisors');

//from supervisor side
Route::get('supervisor/staff/{slug}', 'SupervisorController@getTeachers');
Route::get('mastersettings/teachers/getList', [
    'as' => 'teachers.dataTable',
    'uses' => 'SupervisorController@getDatatableOfTeachers'
]);
Route::group(['middleware' => 'supervisor'], function () {
    Route::get('supervisor/staff/lesson-plans/{slug}', 'LessionPlansController@index');
    Route::get('supervisor/staff/teacher-timetable/{slug}', 'TimetableController@staffTimetable');
    Route::get('supervisor/staff/assign-subject/{slug}', 'SubjectPreferencesController@subjectPreferences');
    Route::get('supervisor/staff/students-attendance/{slug}', 'StudentAttendanceController@index');
    Route::post('supervisor/staff/students-attendance/{slug}', 'StudentAttendanceController@create');
    Route::get('supervisor/staff/students-marks/{slug}', 'SupervisorController@getStudentsView');

});
Route::post('supervisor/staff/students-marks/{slug}', 'SupervisorController@getClassMarks');
Route::post('supervisor/staff/print-students-marks/{slug}', 'SupervisorController@printClassMarks');

//Second Parent Controller
Route::get('mastersettings/secondary-parent/assign-student/{slug}', 'SecondaryParentController@assignStudent');
Route::post('mastersettings/secondary-parent/assign-student/{slug}', 'SecondaryParentController@updateSecondaryParentStudent');
Route::post('mastersettings/assign-student/check-status', 'SecondaryParentController@checkStatus');

Route::get('secondary-parent/student', 'SecondaryParentController@getStudents');
Route::get('secondary-parent/student/getlist', [
    'as' => 'students.dataTable',
    'uses' => 'SecondaryParentController@getDatatableOfStudents'
]);
Route::group(['middleware' => 'stopOrOn:parent'], function () {
//////////////////////
//Parent Controller //
//////////////////////
    Route::get('parent/children', 'ParentsController@index');
	Route::get('parent/children/lession-plans', 'ParentsController@children');

    Route::get('parent/children/list', 'ParentsController@index');
    Route::get('parent/children/getList/{slug}', 'ParentsController@getDatatable');
	Route::get('parent/children/getLessionChildList/{slug}', 'ParentsController@getLessionChildDatatable');
    Route::get('parent/purchase-expenses/all', 'ParentsController@getUserExpenses');
    Route::get('purchase-expenses/getlist', 'ParentsController@getDatatableExpenses');
    Route::get('children/analysis', 'ParentsController@childrenAnalysis');
/////autocall/////
    Route::get('parent/autocall', 'AutomaticCallController@index');
    Route::get('parent/autocall/getList/{slug}', 'AutomaticCallController@getDatatable');
    Route::post('parent/autocall', 'AutomaticCallController@AutomaticCallRequest');
    Route::get('parent/autocallrequests/{student_id}', 'AutomaticCallController@ParentRequests');
    Route::get('parent/autocallrequests/{student_id}/{slug}', 'AutomaticCallController@getDatatableRequests');
    ////for admin ///////
    Route::get('attendanceoperations/autocall/{note}', 'AutomaticCallController@allRequest');
    Route::post('attendanceoperations/autocall/leave', 'AutomaticCallController@leave');
    Route::post('attendanceoperations/autocall/counter', 'AutomaticCallController@counter');


});




/////////////////////
// Master Settings //
/////////////////////

//skills
Route::get('mastersettings/skills', 'SkillsController@index');
Route::get('getSkills', 'SkillsController@getSkills');
Route::get('mastersettings/skills/create', 'SkillsController@create');
Route::get('mastersettings/skills/edit/{id}', 'SkillsController@edit');
Route::post('mastersettings/skills/update/{id}', 'SkillsController@update');
Route::delete('mastersettings/skills/delete/{slug}', 'SkillsController@delete');
Route::get('mastersettings/skills/getRelatedSubjects', 'SkillsController@getRelatedSubjects');
Route::post('mastersettings/skills/store', 'SkillsController@store');
Route::get('mastersettings/skills/getList', [
    'as' => 'skills.dataTable',
    'uses' => 'SkillsController@getDatatable'
]);
//academic_dues
Route::get('mastersettings/dues','DuesController@index');
Route::get('dues','DuesController@getSelection');
Route::get('mastersettings/dues/create','DuesController@create');

Route::post('mastersettings/dues/store','DuesController@store');
Route::get('mastersettings/dues/edit/{id}', 'DuesController@edit');
Route::post('mastersettings/dues/update/{id}', 'DuesController@update');
/*Route::get('mastersettings/dues/delete/{id}', 'DuesController@delete');*/
Route::get('parent/purchase-expenses/{slug}', 'DuesController@viewParentPurchase');
Route::post('parent/purchase-expenses/pay/{slug}', 'DuesController@payGateway');
Route::post('parent/payments/offline-payment/update/{slug}', ['middleware' => 'stopOrOn:offline_payment','as'=>'payoffline', 'uses'=>'DuesController@updateOfflinePayment']);


Route::get('mastersettings/dues/getList', [
    'as' => 'dues.dataTable',
    'uses' => 'DuesController@getDatatable'
]);

Route::get('mastersettings/dues/rapid_add','DuesController@createRapidExpenses');
Route::get('mastersettings/dues/rapid_edit/{id}','DuesController@editRapidExpenses');
Route::post('mastersettings/dues/rapid_edit/{id}','DuesController@UpdateRapidExpenses');
Route::post('mastersettings/dues/rapid_add','DuesController@storeRapidExpenses');
Route::get('mastersettings/dues/all_expenses','DuesController@getAllRapidExpenses');
Route::delete('mastersettings/dues/delete/{slug}','DuesController@deleteRapidExpenses');
Route::get('mastersettings/dues/get-all-expensesRelated','DuesController@getAllexpensesRelated');
Route::get('mastersettings/dues/get-element-expenses','DuesController@getElementExpenses');
Route::get('mastersettings/dues-expenses-rapid/getList', [
    'as' => 'duesExpensesRapid.dataTable',
    'uses' => 'DuesController@getAllRapidExpensesDatatable'
]);

//branches
Route::get('branches/index', 'BranchController@index');
Route::get('branches/add', 'BranchController@create');
Route::get('branches/edit/{slug}', 'BranchController@edit');
Route::post('branches/store', 'BranchController@store');
Route::patch('branches/update/{slug}', 'BranchController@update');
Route::delete('branches/delete/{slug}', 'BranchController@destroy');


Route::get('branches/datable', 'BranchController@getDatatable');

//Religions
Route::get('mastersettings/religions', 'ReligionsController@index');
Route::get('mastersettings/religions/add', 'ReligionsController@create');
Route::post('mastersettings/religions/add', 'ReligionsController@store');
Route::get('mastersettings/religions/edit/{slug}', 'ReligionsController@edit');
Route::patch('mastersettings/religions/edit/{slug}', 'ReligionsController@update');
Route::delete('mastersettings/religions/delete/{id}', 'ReligionsController@delete');
Route::get('mastersettings/religions/getList', [
    'as' => 'religions.dataTable',
    'uses' => 'ReligionsController@getDatatable'
]);


//Categories
Route::get('mastersettings/categories', 'CategoriesController@index');
Route::get('mastersettings/categories/add', 'CategoriesController@create');
Route::post('mastersettings/categories/add', 'CategoriesController@store');
Route::get('mastersettings/categories/edit/{slug}', 'CategoriesController@edit');
Route::patch('mastersettings/categories/edit/{slug}', 'CategoriesController@update');
Route::delete('mastersettings/categories/delete/{id}', 'CategoriesController@delete');
Route::get('mastersettings/categories/getList', [
    'as' => 'categories.dataTable',
    'uses' => 'CategoriesController@getDatatable'
]);


//Academics
Route::get('mastersettings/academics', 'AcademicsController@index');
Route::get('mastersettings/academics/add', 'AcademicsController@create');
Route::post('mastersettings/academics/add', 'AcademicsController@store');
Route::get('mastersettings/academics/edit/{slug}', 'AcademicsController@edit');
Route::patch('mastersettings/academics/edit/{slug}', 'AcademicsController@update');
Route::delete('mastersettings/academics/delete/{id}', 'AcademicsController@delete');
Route::get('mastersettings/academics/getList', [
    'as' => 'academics.dataTable',
    'uses' => 'AcademicsController@getDatatable'
]);
Route::get('mastersettings/academics/get-academics', 'AcademicsController@getAcademics');
Route::get('mastersettings/academics/get-semisters', 'AcademicsController@getSemisters');


//Academic Courses
Route::post('mastersettings/academic-courses/check-status', 'AcademicCoursesController@checkStatus');


Route::get('mastersettings/academic-courses/{slug}', 'AcademicCoursesController@academicCoursesArrangment');
Route::post('mastersettings/academic-courses/{slug}', 'AcademicCoursesController@updateAcademicCourses');


Route::post('academic-courses/get-parent-courses', 'AcademicCoursesController@getParentCourses');
Route::post('academic-courses/get-academic-semester', 'AcademicCoursesController@getSemisters');
Route::get('academic-courses/get-parent-courses', 'AcademicCoursesController@getParentCourses');
Route::post('academic-courses/get-child-courses', 'AcademicCoursesController@getChildCourses');


//courses

//Courese ---dashboard
Route::get('courses/dashboard', 'CourseController@coursesdashboard');
Route::get('mastersettings/course', 'CourseController@index');
Route::get('mastersettings/course/add', 'CourseController@create');
Route::post('mastersettings/course/add', 'CourseController@store');
Route::get('mastersettings/course/edit/{slug}', 'CourseController@edit');
Route::patch('mastersettings/course/edit/{slug}', 'CourseController@update');
Route::delete('mastersettings/course/delete/{id}', 'CourseController@delete');
Route::get('mastersettings/course/getList', [
    'as' => 'course.dataTable',
    'uses' => 'CourseController@getDatatable'
]);

Route::get('mastersettings/course/editSemisters/{slug}', 'CourseController@editSemisters');
Route::patch('mastersettings/course/editSemisters', 'CourseController@updateSemisters');


//Course Subjects
Route::get('mastersettings/course-subjects/add', 'CourseSubjectsController@create');
Route::get('mastersettings/course-subjects/select', 'CourseSubjectsController@select');

Route::post('mastersettings/course-subjects/add', 'CourseSubjectsController@store');
Route::patch('mastersettings/course-subjectsd/edit/{slug}', 'CourseSubjectsController@update');
Route::delete('mastersettings/course-subjects/delete', 'CourseSubjectsController@delete');
Route::get('mastersettings/course-subjects/getList/{year}/{sem}/{course}/{class}', 'CourseSubjectsController@getDatatable');
Route::get('mastersettings/course-subjects/{year}/{sem}/{course}/{class}', 'CourseSubjectsController@index');

Route::post('mastersettings/course-subjects/getCourseYears', 'CourseSubjectsController@getCourseYears');
Route::post('mastersettings/course-subjects/getSemisters', 'CourseSubjectsController@getSemisters');
Route::post('mastersettings/course-subjects/getSavedSubjects', 'CourseSubjectsController@getSavedSubjects');
Route::get('mastersettings/course-subjects/show/{academic_id}/{course_id}', 'CourseSubjectsController@show');
Route::post('academic-courses/remove-subject', 'CourseSubjectsController@removeSubject');

Route::get('subjects/view-topics/{subject_slug}', 'SubjectsController@viewTopics');

Route::post('mastersettings/course-subjects/load', 'CourseSubjectsController@loadYearDetails');
Route::get('mastersettings/course-subjects/load', 'CourseSubjectsController@create');

Route::get('course-subjects/add-staff/{academic_id}/{course_id}', 'CourseSubjectsController@staffAllotment');
Route::post('course-subjects/update-staff', 'CourseSubjectsController@updateStaffAllotment');
Route::post('course-subjects/is-staff-allocated', 'CourseSubjectsController@isSatffAllocatedToTimetable');

Route::group(['middleware' => 'stopOrOn:certificate'], function () {
//Certificate Templates
    Route::get('mastersettings/certificate_templates', 'CertificateTemplatesController@index');
    Route::get('mastersettings/certificate_templates/add', 'CertificateTemplatesController@create');
    Route::post('mastersettings/certificate_templates/add', 'CertificateTemplatesController@store');
    Route::get('mastersettings/certificate_templates/edit/{slug}', 'CertificateTemplatesController@edit');
    Route::patch('mastersettings/certificate_templates/edit/{slug}', 'CertificateTemplatesController@update');
    Route::delete('mastersettings/certificate_templates/delete/{id}', 'CertificateTemplatesController@delete');
    Route::get('mastersettings/certificate_templates/getList', [
        'as' => 'certificate_templates.dataTable',
        'uses' => 'CertificateTemplatesController@getDatatable'
    ]);

//Certificate Generation links

    Route::get('certificates/dashboard', 'CertificatesController@certificatesDashboard');
    Route::get('certificates/id-cards', 'CertificatesController@idCards');
    Route::post('students/get-users', 'StudentController@getStudents');
    Route::post('students/get-users/completed', 'StudentController@getCompletedStudents');
    Route::post('students/delete-users/completed', 'StudentController@backCompletedStudents');
    Route::post('students/get-users/detained', 'StudentController@getDetainedStudents');
    Route::get('certificates/bonafide-certificates',
        'CertificatesController@bonafideCertificates');

    Route::post('certificates/id-cards', 'CertificatesController@printCards');

    Route::post('certificate-issues/is-issued', 'CertificatesIssuesController@isIssued');
    Route::post('certificate/issue', 'CertificatesIssuesController@issueCertificate');
    Route::post('certificate/issue/tc', 'CertificatesIssuesController@tcDetails');
});

//Academic Holidays
Route::get('mastersettings/holidays', 'AcademicHolidaysController@index');
Route::get('mastersettings/holidays/add', 'AcademicHolidaysController@create');
Route::post('mastersettings/holidays/add', 'AcademicHolidaysController@store');
Route::get('mastersettings/holidays/edit/{slug}', 'AcademicHolidaysController@edit');
Route::patch('mastersettings/holidays/edit/{slug}', 'AcademicHolidaysController@update');
Route::delete('mastersettings/holidays/delete/{id}', 'AcademicHolidaysController@delete');
Route::get('mastersettings/holidays/getList', [
    'as' => 'academicholidays.dataTable',
    'uses' => 'AcademicHolidaysController@getDatatable'
]);

//Student
Route::get('student/profile/edit/{slug}/{tab?}', 'StudentController@edit');
Route::patch('student/profile/edit/general/{slug}', 'StudentController@updateGeneralDetails');
Route::patch('student/profile/edit/personal/{slug}', 'StudentController@updatePersonalDetails');
Route::patch('student/profile/edit/contact/{slug}', 'StudentController@updateContactDetails');
Route::patch('student/profile/edit/parent/{slug}', 'StudentController@updateParentDetails');
Route::get('student/profile/{slug}', 'StudentController@show');
Route::post('student/get-parent-records', 'StudentController@getParentsOnSearch');

Route::post('student/courses', 'StudentController@courses');

Route::get('student/course-semister/{courseId}/{semisterId}', 'StudentController@coursesSemister');


//subjects
Route::get('mastersettings/subjects', 'SubjectsController@index');
Route::get('mastersettings/subjects/add', 'SubjectsController@create');
Route::post('mastersettings/subjects/add', 'SubjectsController@store');
Route::get('mastersettings/subjects/edit/{slug}', 'SubjectsController@edit');
Route::patch('mastersettings/subjects/edit/{slug}', 'SubjectsController@update');
Route::delete('mastersettings/subjects/delete/{id}', 'SubjectsController@delete');
Route::get('mastersettings/subjects/getList', [
    'as' => 'subjects.dataTable',
    'uses' => 'SubjectsController@getDatatable'
]);

Route::get('mastersettings/subjects/import', 'SubjectsController@import');
Route::post('mastersettings/subjects/import', 'SubjectsController@readExcel');

//Topics
Route::get('mastersettings/topics', 'TopicsController@index');
Route::get('mastersettings/topics/view/{year}/{sem}/{course}/{subject}', 'TopicsController@indexList');
Route::get('mastersettings/topics/add', 'TopicsController@create');
Route::post('mastersettings/topics/add', 'TopicsController@store');
Route::get('mastersettings/topics/edit/{slug}', 'TopicsController@edit');
Route::patch('mastersettings/topics/edit/{slug}', 'TopicsController@update');
Route::delete('mastersettings/topics/delete/{id}', 'TopicsController@delete');
Route::get('mastersettings/topics/getList/{year}/{sem}/{course}/{subject}','TopicsController@getDatatable');

Route::get('mastersettings/topics/get-parents-topics/{subject_id}', 'TopicsController@getParentTopics');

Route::get('mastersettings/topics/import', 'TopicsController@import');
Route::post('mastersettings/topics/import', 'TopicsController@readExcel');

///transfer data to new years
Route::get('mastersettings/transfer-data', 'TransferYearDataController@index');
Route::post('mastersettings/transfer-data/academic_course', 'TransferYearDataController@copyAcademicCourse');
Route::post('mastersettings/transfer-data/course_subject', 'TransferYearDataController@copyCourseSubject');
Route::post('mastersettings/transfer-data/topics', 'TransferYearDataController@copyTopics');
Route::post('mastersettings/transfer-data/questionbank', 'TransferYearDataController@copyQuestionBank');
Route::post('mastersettings/transfer-data/lmscontent', 'TransferYearDataController@copyLmsContent');
//Users
Route::get('users/staff/{role?}', 'UsersController@index');
Route::get('users/create', 'UsersController@create');
Route::post('users/create/{role?}', 'UsersController@store');
Route::get('users/edit/{slug}', 'UsersController@edit');
Route::patch('users/edit/{slug}', 'UsersController@update');
Route::get('users/profile/{slug}', 'UsersController@show');
Route::get('users', 'UsersController@index');

Route::get('users/list/{user_type}', 'UsersController@listUsers');

///////////////////////////////
// LIBRARY MANAGEMENT SYSTEM //
///////////////////////////////

//Library dashboard
Route::get('library/librarydashboard', 'LibraryDashboardController@index');
Route::get('library/librarydashboard/books', 'LibraryDashboardController@books');
Route::get('library/librarydashboard/books/staffbooks', 'LibraryDashboardController@staffbooks');

Route::get('library/librarydashboard/getList',
    [
        'as' => 'librarydashboard.datatable',
        'uses' => 'LibraryDashboardController@getDatatable'
    ]);

Route::get('library/librarydashboard/getList/staff',
    [
        'as' => 'librarydashboard.staffdatatable',
        'uses' => 'LibraryDashboardController@getStaffDatatable'
    ]);


//Library Assets
Route::get('library/assets', 'LibraryAssetTypeController@index');
Route::get('library/assets/add', 'LibraryAssetTypeController@create');
Route::get('library/assets/view/{slug}', 'LibraryAssetTypeController@show');

Route::post('library/assets/add', 'LibraryAssetTypeController@store');
Route::get('library/assets/edit/{slug}', 'LibraryAssetTypeController@edit');
Route::patch('library/assets/edit/{slug}', 'LibraryAssetTypeController@update');
Route::delete('library/assets/delete/{id}', 'LibraryAssetTypeController@delete');
Route::get('library/assets/getList', [
    'as' => 'libraryassets.dataTable',
    'uses' => 'LibraryAssetTypeController@getDatatable'
]);


//Library Masters
Route::get('library/master', 'LibraryMastersController@index');
Route::get('library/master/add', 'LibraryMastersController@create');
Route::get('library/master/view/{slug}', 'LibraryMastersController@show');
Route::get('library/master/details/{slug}', 'LibraryMastersController@master_assetDetails');
Route::get('library/master/details/print/{slug}', 'LibraryMastersController@printMasterAssetDetails');

Route::post('library/master/add', 'LibraryMastersController@store');
Route::get('library/master/edit/{slug}', 'LibraryMastersController@edit');
Route::patch('library/master/edit/{slug}', 'LibraryMastersController@update');
Route::delete('library/master/delete/{id}', 'LibraryMastersController@delete');
Route::get('library/master/getList', [
    'as' => 'librarymasters.dataTable',
    'uses' => 'LibraryMastersController@getDatatable'
]);

//Library Collections
Route::get('library/master/collections/{slug}', 'LibraryMastersController@viewCollections');
Route::get('library/master/get-collection-list/{slug}', 'LibraryMastersController@getInstanceDatatable');
Route::get('library/master/collections/add/{slug}', 'LibraryMastersController@addCollection');
Route::post('library/master/collections/add/{slug}', 'LibraryMastersController@storeCollection');
Route::post('library/master/change-status', 'LibraryMastersController@statusChange');
Route::get('library/master/collections/barcode/{asset_no}', 'LibraryMastersController@generateBarCode');
Route::delete('library/master/collections/delete/{id}', 'LibraryMastersController@deleteRecord');


//Authors
Route::get('library/authors', 'AuthorsController@index');
Route::get('library/authors/add', 'AuthorsController@create');
Route::get('library/authors/view/{slug}', 'AuthorsController@show');
Route::post('library/authors/add', 'AuthorsController@store');
Route::get('library/authors/edit/{slug}', 'AuthorsController@edit');
Route::patch('library/authors/edit/{slug}', 'AuthorsController@update');
Route::delete('library/authors/delete/{id}', 'AuthorsController@delete');
Route::get('library/authors/getList', [
    'as' => 'authors.dataTable',
    'uses' => 'AuthorsController@getDatatable'
]);

//Authors
Route::get('library/publishers', 'PublishersController@index');
Route::get('library/publishers/add', 'PublishersController@create');
Route::get('library/publishers/view/{slug}', 'PublishersController@show');
Route::post('library/publishers/add', 'PublishersController@store');
Route::get('library/publishers/edit/{slug}', 'PublishersController@edit');
Route::patch('library/publishers/edit/{slug}', 'PublishersController@update');
Route::delete('library/publishers/delete/{id}', 'PublishersController@delete');
Route::get('library/publishers/getList', [
    'as' => 'publishers.dataTable',
    'uses' => 'PublishersController@getDatatable'
]);


//Asset Issues
Route::get('library/users/{role_name}', 'LibraryIssuesController@users');
Route::get('library/getUsersList/{role_name}', 'LibraryIssuesController@getUsers');
Route::get('library/issue/{slug}', 'LibraryIssuesController@issueAsset');
Route::get('library/issues/get-reference', 'LibraryIssuesController@getReference');
Route::get('library/issues/get-reference/staff', 'LibraryIssuesController@getstaffReference');
Route::post('library/issues/get-master-details', 'LibraryIssuesController@getMasterDetails');
Route::post('library/issues/issue-asset', 'LibraryIssuesController@store');
Route::post('library/returns/return-asset', 'LibraryIssuesController@returnAsset');
Route::post('library/returns/return-asset/staff', 'LibraryIssuesController@returnStaffAsset');


////////////////////////
// EXAMINATION SYSTEM //
////////////////////////

//homework
Route::get('homework/list', 'HomeWorkController@index');
Route::get('homework/add-homework', 'HomeWorkController@create');
Route::get('homework/view/{slug}','HomeWorkController@show_student_homework');
Route::get('homework/edit-homework/{slug}', 'HomeWorkController@edit');
Route::get('homework/get-homework-data/{slug}', 'HomeWorkController@show');
Route::post('homework/add-homework', 'HomeWorkController@store');
Route::patch('homework/edit-homework/{slug}', 'HomeWorkController@update');
Route::get('homework/get-homeworks/{teacher}/{course}/{subject}/{year}/{sem}', 'HomeWorkController@showList');
Route::get('homework/get-homeworks-datable/{teacher}/{course}/{subject}/{year}/{sem}', 'HomeWorkController@getHomeworks');
Route::delete('homework/delete/{slug}', 'HomeWorkController@destroy');
Route::get('homework/{slug?}', 'HomeWorkController@StudentHW');
Route::get('homeworkDatable/{student}', 'HomeWorkController@StudentDatable');
Route::get('homework/parent/children', 'HomeWorkController@children');
Route::get('homework/children/getList/{slug}', 'HomeWorkController@getDatatable');
Route::post('homework/upload', 'HomeWorkController@upload');
Route::delete('homework/file-delete/{file}', 'HomeWorkController@deleteFile');
Route::get('homework/explanation/{slug}','HomeWorkController@showExplanation');
Route::post('homework/replay/{slug}','HomeWorkController@replay');
Route::get('homework/students/{slug}','HomeWorkController@Homework_students');
Route::get('homework/students/datable/{slug}', 'HomeWorkController@HW_student_Datable');

Route::get('homework/replay/delete/{id}', 'HomeWorkController@deleteReplay');
//HW_student_Datable




Route::get('exams/questionbank', 'QuestionBankController@index');
Route::get('exams/questionbank/add-question', 'QuestionBankController@create');
Route::get('exams/questionbank/view/{year}/{sem}/{course}/{subject}', 'QuestionBankController@show');

Route::post('exams/questionbank/add', 'QuestionBankController@store');
Route::get('exams/questionbank/edit-question/{slug}', 'QuestionBankController@edit');
Route::patch('exams/questionbank/edit/{slug}', 'QuestionBankController@update');
Route::delete('exams/questionbank/delete/{id}', 'QuestionBankController@delete');
Route::get('exams/questionbank/getList', 'QuestionBankController@getDatatable');

Route::get('exams/questionbank/getquestionslist/{year}/{sem}/{course}/{subject}',
    'QuestionBankController@getQuestions');
Route::get('exams/questionbank/import', 'QuestionBankController@import');
Route::post('exams/questionbank/import', 'QuestionBankController@readExcel');
Route::get('exams/questionbank/delete/option-file/{id}/{imagename}', 'QuestionBankController@deleteOptionFile');
Route::post('exams/questionbank/delete/question-file', 'QuestionBankController@deleteQuestionFile');

Route::post('exams/questionbank/upload', 'QuestionBankController@upload');


//Quiz Categories
Route::get('exams/categories', 'QuizCategoryController@index');
Route::get('exams/categories/add', 'QuizCategoryController@create');
Route::post('exams/categories/add', 'QuizCategoryController@store');
Route::get('exams/categories/edit/{slug}', 'QuizCategoryController@edit');
Route::patch('exams/categories/edit/{slug}', 'QuizCategoryController@update');
Route::delete('exams/categories/delete/{slug}', 'QuizCategoryController@delete');
Route::get('exams/categories/getList', [
    'as' => 'quizcategories.dataTable',
    'uses' => 'QuizCategoryController@getDatatable'
]);

// Quiz Student Categories
Route::get('exams/student/categories', 'StudentQuizController@index');
Route::get('exams/student/exams/{slug?}', 'StudentQuizController@exams');
Route::get('offline-exams/student/exams/{slug?}', 'StudentQuizController@offline_exams');
Route::get('exams/student/quiz/getList/{slug?}', 'StudentQuizController@getDatatable');
Route::get('exams/student/offline-quiz/getList/{slug?}', 'StudentQuizController@getofflineDatatable');
Route::get('exams/student/quiz/take-exam/{slug?}', 'StudentQuizController@instructions');
Route::post('exams/student/start-exam/{slug}', 'StudentQuizController@startExam');
Route::get('exams/student/start-exam/{slug}', 'StudentQuizController@index');
Route::get('exams/student/offline-quiz-category', 'OfflineQuizCategoriesController@student_index');

Route::get('exams/student/get-scheduled-exams/{slug}', 'StudentQuizController@getScheduledExams');
Route::get('exams/student/load-scheduled-exams/{slug}', 'StudentQuizController@loadScheduledExams');


Route::post('exams/student/finish-exam/{slug}', 'StudentQuizController@finishExam');
Route::get('exams/student/reports/{slug}', 'StudentQuizController@reports');
Route::delete('exams/student/deleteExamAttempts/{slug}', 'StudentQuizController@deleteExamAttempts');


Route::get('exams/student/exam-attempts/{user_slug}/{exam_slug?}', 'StudentQuizController@examAttempts');
Route::get('exams/student/exam-attempts/delete/{exam_slug?}', 'StudentQuizController@delete');
Route::get('exams/student/get-exam-attempts/{user_slug}/{exam_slug?}', 'StudentQuizController@getExamAttemptsData');

Route::get('student/analysis/by-exam/{user_slug}', 'StudentQuizController@examAnalysis');
Route::get('student/analysis/get-by-exam/{user_slug}', 'StudentQuizController@getExamAnalysisData');

Route::get('student/analysis/by-subject/{user_slug}/{exam_slug?}/{results_slug?}',
    'StudentQuizController@subjectAnalysisInExam');
Route::get('student/analysis/subject/{user_slug}', 'StudentQuizController@overallSubjectAnalysis');

//Student Reports
Route::get('student/exam/answers/{quiz_slug}/{result_slug}', 'ReportsController@viewExamAnswers');
Route::get('student/results/{slug}', 'ReportsController@viewAcademicResultsOfStudent');
Route::post('student/results/get-exam-categories', 'ReportsController@getExamCategories');
Route::post('student/results/get-exams', 'ReportsController@getExamsByCategory');


//Quiz

//Exams ---dashboard
Route::get('exams/dashboard', 'QuizController@quizdashboard');
Route::get('exams/quiz/results/view/{slug?}', 'QuizController@quiz_results');
Route::post('exams/quiz/report', 'QuizController@report_result');
//exams/quiz/result/view
Route::get('exams/quizzes', 'QuizController@index');
Route::get('exams/quiz/add', 'QuizController@create');
Route::post('exams/quiz/add', 'QuizController@store');
Route::get('exams/quiz/edit/{slug}', 'QuizController@edit');
Route::patch('exams/quiz/edit/{slug}', 'QuizController@update');
Route::delete('exams/quiz/delete/{slug}', 'QuizController@delete');
Route::get('exams/quiz/getList/{slug?}', 'QuizController@getDatatable');

Route::get('exams/quiz/update-questions/{slug}', 'QuizController@updateQuestions');
Route::post('exams/quiz/update-questions/{slug}', 'QuizController@storeQuestions');


Route::post('exams/quiz/get-questions', 'QuizController@getSubjectData');

//Certificates controller
Route::get('result/generate-certificate/{slug}', 'CertificatesController@getCertificate');


//Exam Series
Route::get('exams/exam-series', 'ExamSeriesController@index');
Route::get('exams/exam-series/add', 'ExamSeriesController@create');
Route::post('exams/exam-series/add', 'ExamSeriesController@store');
Route::get('exams/exam-series/edit/{slug}', 'ExamSeriesController@edit');
Route::patch('exams/exam-series/edit/{slug}', 'ExamSeriesController@update');
Route::delete('exams/exam-series/delete/{slug}', 'ExamSeriesController@delete');
Route::get('exams/exam-series/getList', 'ExamSeriesController@getDatatable');

//EXAM SERIES STUDENT LINKS
Route::get('exams/student-exam-series/list', 'ExamSeriesController@listSeries');
Route::get('exams/student-exam-series/{slug}', 'ExamSeriesController@viewItem');


Route::get('exams/exam-series/update-series/{slug}', 'ExamSeriesController@updateSeries');
Route::post('exams/exam-series/update-series/{slug}', 'ExamSeriesController@storeSeries');
Route::post('exams/exam-series/get-exams', 'ExamSeriesController@getExams');
Route::get('payment/cancel', 'ExamSeriesController@cancel');
Route::post('payment/success', 'ExamSeriesController@success');

/////////////////////
// PAYMENT REPORTS //
/////////////////////
Route::get('payments-report/', 'PaymentsController@overallPayments');

Route::get('payments-report/online/', 'PaymentsController@onlinePaymentsReport');
Route::get('payments-report/online/{slug}', 'PaymentsController@listOnlinePaymentsReport');
Route::get('payments-report/online/getList/{slug}', 'PaymentsController@getOnlinePaymentReportsDatatable');

Route::group(['middleware' => 'stopOrOn:offline_payment'], function () {
    Route::get('payments-report/offline/', 'PaymentsController@offlinePaymentsReport');
    Route::get('payments-report/offline/{slug}', 'PaymentsController@listOfflinePaymentsReport');
    Route::get('payments-report/offline/getList/{slug}', 'PaymentsController@getOfflinePaymentReportsDatatable');
    Route::get('payments-report/export', 'PaymentsController@exportPayments');
});
Route::post('payments-report/export', 'PaymentsController@doExportPayments');

Route::post('payments-report/getRecord', 'PaymentsController@getPaymentRecord');
Route::post('payments/approve-reject-offline-request', ['middleware' => 'stopOrOn:offline_payment','uses' => 'PaymentsController@approveOfflinePayment']);
//////////////////
// INSTRUCTIONS  //
//////////////////

Route::get('exam/instructions/list', 'InstructionsController@index');
Route::get('exam/instructions', 'InstructionsController@index');
Route::get('exams/instructions/add', 'InstructionsController@create');
Route::post('exams/instructions/add', 'InstructionsController@store');
Route::get('exams/instructions/edit/{slug}', 'InstructionsController@edit');
Route::patch('exams/instructions/edit/{slug}', 'InstructionsController@update');
Route::delete('exams/instructions/delete/{slug}', 'InstructionsController@delete');
Route::get('exams/instructions/getList', 'InstructionsController@getDatatable');


//BOOKMARKS MODULE
Route::get('student/bookmarks/{slug}', 'BookmarksController@index');
Route::post('student/bookmarks/add', 'BookmarksController@create');
Route::delete('student/bookmarks/delete/{id}', 'BookmarksController@delete');
Route::delete('student/bookmarks/delete_id/{id}', 'BookmarksController@deleteById');
Route::get('student/bookmarks/getList/{slug}', 'BookmarksController@getDatatable');
Route::post('student/bookmarks/getSavedList', 'BookmarksController@getSavedBookmarks');

Route::group(['middleware' => 'stopOrOn:push_notifications'], function () {
//////////////////////////
// Notifications Module //
/////////////////////////
    Route::get('admin/notifications/list', 'NotificationsController@index');
    Route::get('admin/notifications', 'NotificationsController@index');
    Route::get('admin/notifications/add', 'NotificationsController@create');
    Route::post('admin/notifications/add', 'NotificationsController@store');
    Route::get('admin/notifications/edit/{slug}', 'NotificationsController@edit');
    Route::patch('admin/notifications/edit/{slug}', 'NotificationsController@update');
    Route::delete('admin/notifications/delete/{slug}', 'NotificationsController@delete');
    Route::get('admin/notifications/getList', 'NotificationsController@getDatatable');
    Route::get('admin/notifications/destroy_all','NotificationsController@Destroy_all');

// NOTIFICATIONS FOR STUDENT
    Route::get('notifications/list', 'NotificationsController@usersList');
    Route::get('notifications/show/{slug}', 'NotificationsController@display');
    Route::get('notifications/destroy/{slug}', 'NotificationsController@destroy');
    Route::get('notifications/obliviate', 'NotificationsController@obliviate');
    //obliviate
});

//BOOKMARKS MODULE
Route::get('toppers/compare-with-topper/{user_result_slug}/{compare_slug?}', 'ExamToppersController@compare');


////////////////
// LMS MODULE //
////////////////

//LMS Categories
//Lms ---dashboard
Route::get('lms/dashboard', 'LmsCategoryController@lmsdashboard');
Route::get('lms/categories', 'LmsCategoryController@index');
Route::get('lms/categories/add', 'LmsCategoryController@create');
Route::post('lms/categories/add', 'LmsCategoryController@store');
Route::get('lms/categories/edit/{slug}', 'LmsCategoryController@edit');
Route::patch('lms/categories/edit/{slug}', 'LmsCategoryController@update');
Route::delete('lms/categories/delete/{slug}', 'LmsCategoryController@delete');
Route::get('lms/categories/getList', [
    'as' => 'lmscategories.dataTable',
    'uses' => 'LmsCategoryController@getDatatable'
]);

//LMS Contents
Route::get('lms/content', 'LmsContentController@main');
Route::get('lms/content/view/{year}/{sem}/{course}/{subject}', 'LmsContentController@index');
Route::get('lms/content/add', 'LmsContentController@create');
Route::post('lms/content/add', 'LmsContentController@store');
Route::get('lms/content/edit/{slug}', 'LmsContentController@edit');
Route::patch('lms/content/edit/{slug}', 'LmsContentController@update');
Route::delete('lms/content/delete/{slug}', 'LmsContentController@delete');
Route::get('lms/content/getList/{year}/{sem}/{course}/{subject}', [
    'as' => 'lmscontent.dataTable',
    'uses' => 'LmsContentController@getDatatable'
]);

Route::post('lms/content/upload_image', 'LmsContentController@upload_image');
Route::post('lms/content/upload_lms', 'LmsContentController@upload_lms_file');
Route::post('lms/content/edit/upload_image', 'LmsContentController@upload_image');
Route::post('lms/content/edit/upload_lms', 'LmsContentController@upload_lms_file');


//LMS Series
Route::get('lms/series', 'LmsSeriesController@index');
Route::get('lms/series/add', 'LmsSeriesController@create');
Route::post('lms/series/add', 'LmsSeriesController@store');
Route::get('lms/series/edit/{slug}', 'LmsSeriesController@edit');
Route::patch('lms/series/edit/{slug}', 'LmsSeriesController@update');
Route::delete('lms/series/delete/{slug}', 'LmsSeriesController@delete');
Route::get('lms/series/getList', 'LmsSeriesController@getDatatable');

//LMS SERIES STUDENT LINKS
Route::get('lms/exam-series/list', 'LmsSeriesController@listSeries');
Route::get('lms/exam-series/{slug}', 'LmsSeriesController@viewItem');


Route::get('lms/series/update-series/{slug}', 'LmsSeriesController@updateSeries');
Route::post('lms/series/update-series/{slug}', 'LmsSeriesController@storeSeries');
Route::post('lms/series/get-series', 'LmsSeriesController@getSeries');
Route::get('payment/cancel', 'LmsSeriesController@cancel');
Route::post('payment/success', 'LmsSeriesController@success');


//LMS Student view
Route::get('learning-management/categories', 'StudentLmsController@index');
Route::get('learning-management/view/{slug}', 'StudentLmsController@viewCategoryItems');
Route::get('learning-management/series', 'StudentLmsController@series');
Route::get('learning-management/series/{slug}/{content_slug?}', 'StudentLmsController@viewItem');
Route::get('learning-management/content/{req_content_type}', 'StudentLmsController@content');
Route::get('learning-management/content/show/{slug}', 'StudentLmsController@showContent');
Route::get('user/paid/{slug}/{content_slug}', 'StudentLmsController@verifyPaidItem');


//Payments Controller
Route::get('payments/list/{slug}', 'PaymentsController@index');
Route::get('payments/getList/{slug}', 'PaymentsController@getDatatable');

Route::get('payments/checkout/{type}/{slug}', 'PaymentsController@checkout');

Route::post('payments/paynow/{slug}', 'PaymentsController@paynow');
Route::post('payments/paypal/status-success', 'PaymentsController@paypal_success');
Route::get('payments/paypal/status-cancel', 'PaymentsController@paypal_cancel');

Route::post('payments/payu/status-success', 'PaymentsController@payu_success');
Route::post('payments/payu/status-cancel', 'PaymentsController@payu_cancel');
Route::post('payments/offline-payment/update', ['middleware' => 'stopOrOn:offline_payment','as'=>'payoffline', 'uses'=>'PaymentsController@updateOfflinePayment']);


////////////////////////////
// SETTINGS MODULE //
///////////////////////////


//LMS Categories

//Settings ---dashboard
    Route::get('settings/dashboard', 'SettingsController@settingsDashboard');
Route::get('mastersettings/settings/', 'SettingsController@index');
Route::get('mastersettings/settings/index', 'SettingsController@index');

Route::group(['middleware' => 'stopOrOn:certificate'], function () {
    Route::get('mastersettings/settings/certificates', 'SettingsController@certificatesdashboard');
});
Route::get('mastersettings/settings/timetable', 'SettingsController@timetabledashboard');
Route::get('mastersettings/settings/add', 'SettingsController@create');
Route::post('mastersettings/settings/add', 'SettingsController@store');
Route::get('mastersettings/settings/edit/{slug}', 'SettingsController@edit');
Route::patch('mastersettings/settings/edit/{slug}', 'SettingsController@update');
Route::get('mastersettings/settings/view/{slug}', 'SettingsController@viewSettings');
Route::get('mastersettings/settings/add-sub-settings/{slug}', 'SettingsController@addSubSettings');
Route::post('mastersettings/settings/add-sub-settings/{slug}', 'SettingsController@storeSubSettings');
Route::patch('mastersettings/settings/add-sub-settings/{slug}', 'SettingsController@updateSubSettings');

Route::get('mastersettings/settings/getList', [
    'as' => 'mastersettings.dataTable',
    'uses' => 'SettingsController@getDatatable'
]);


////////////////////////////
// MODULE HELPERS  MODULE //
///////////////////////////

Route::get('mastersettings/module-helpers', 'ModuleHelperController@index');
Route::get('mastersettings/module-helpers/index', 'ModuleHelperController@index');
Route::get('mastersettings/module-helpers/add', 'ModuleHelperController@create');
Route::post('mastersettings/module-helpers/add', 'ModuleHelperController@store');
Route::get('mastersettings/module-helpers/edit/{slug}', 'ModuleHelperController@edit');
Route::patch('mastersettings/module-helpers/edit/{slug}', 'ModuleHelperController@update');
Route::get('mastersettings/module-helpers/view/{slug}', 'ModuleHelperController@viewSettings');
Route::get('mastersettings/module-helpers/add-sub-settings/{slug}', 'ModuleHelperController@addSubSettings');
// Route::post('mastersettings/module-helpers/add-sub-settings/{slug}', 'ModuleHelperController@storeSubSettings');
Route::patch('mastersettings/module-helpers/add-steps/{slug}', 'ModuleHelperController@updateSteps');

Route::get('mastersettings/module-helpers/getList', [
    'as' => 'mastersettings.module-helper.dataTable',
    'uses' => 'ModuleHelperController@getDatatable'
]);

////////////////////////////
// EMAIL TEMPLATES MODULE //
///////////////////////////

//LMS Categories
Route::get('email/templates', 'EmailTemplatesController@index');
Route::get('email/templates/add', 'EmailTemplatesController@create');
Route::post('email/templates/add', 'EmailTemplatesController@store');
Route::get('email/templates/edit/{slug}', 'EmailTemplatesController@edit');
Route::patch('email/templates/edit/{slug}', 'EmailTemplatesController@update');
Route::delete('email/templates/delete/{slug}', 'EmailTemplatesController@delete');
Route::get('email/templates/getList', [
    'as' => 'emailtemplates.dataTable',
    'uses' => 'EmailTemplatesController@getDatatable'
]);

Route::group(['middleware' => 'stopOrOn:coupons'], function () {
//Coupons Module
    Route::get('coupons/list', 'CouponcodesController@index');
    Route::get('coupons/add', 'CouponcodesController@create');
    Route::post('coupons/add', 'CouponcodesController@store');
    Route::get('coupons/edit/{slug}', 'CouponcodesController@edit');
    Route::patch('coupons/edit/{slug}', 'CouponcodesController@update');
    Route::delete('coupons/delete/{slug}', 'CouponcodesController@delete');
    Route::get('coupons/getList/{slug?}', 'CouponcodesController@getDatatable');

    Route::get('coupons/get-usage', 'CouponcodesController@getCouponUsage');
    Route::get('coupons/get-usage-data', 'CouponcodesController@getCouponUsageData');
    Route::post('coupons/update-questions/{slug}', 'CouponcodesController@storeQuestions');


    Route::post('coupons/validate-coupon', 'CouponcodesController@validateCoupon');
    Route::get('coupons/validate-coupon', 'CouponcodesController@validateCoupon');
});

//Feedback Module
Route::get('feedback/list', 'FeedbackController@index');
Route::get('feedback/view-details/{slug}', 'FeedbackController@details');
Route::get('feedback/send', 'FeedbackController@create');
Route::post('feedback/send', 'FeedbackController@store');
Route::delete('feedback/delete/{slug}', 'FeedbackController@delete');
Route::get('feedback/getlist', 'FeedbackController@getDatatable');
Route::post('feedback/upload', 'FeedbackController@upload');
Route::delete('feedback/file-delete/{file}', 'FeedbackController@deleteFile');

//SMS Module

Route::get('sms/index', 'SMSAgentController@index');
Route::post('sms/send', 'SMSAgentController@sendSMS');

/////////////////////
// MESSAGES MODULE //
/////////////////////


Route::group(['prefix' => 'messages', 'middleware' => 'stopOrOn:messaging'], function () {
    Route::get('/', ['as' => 'messages', 'uses' => 'MessagesController@index']);
    Route::get('create', ['as' => 'messages.create', 'uses' => 'MessagesController@create']);
    Route::post('/', ['as' => 'messages.store', 'uses' => 'MessagesController@store']);
    Route::get('{id}', ['as' => 'messages.show', 'uses' => 'MessagesController@show']);
    Route::put('{id}', ['as' => 'messages.update', 'uses' => 'MessagesController@update']);
});

Route::post('messages/upload', 'MessagesController@upload');
Route::delete('message/file-delete/{file}', 'MessagesController@deleteFile');
Route::get('messages/search/{key}', 'MessagesController@search');
Route::get('messages/archive/{id}', 'MessagesController@archive');
Route::get('messages/unArchive/{id}', 'MessagesController@unArchive');
Route::get('messages/destroy/{id}', 'MessagesController@delete');
Route::get('messages/archived/messages','MessagesController@archived_index');


//////////////////////
//Attendance Module //
//////////////////////
//Student attendance
Route::get('student/attendance/report', 'StudentAttendanceReportController@index');
Route::get('student/attendance/{slug}', 'StudentAttendanceController@index');
Route::get('student/attendance/add/{slug}', 'StudentAttendanceController@index');
Route::post('student/attendance/add/{slug}', 'StudentAttendanceController@create');
Route::post('student/attendance/update/{slug}', 'StudentAttendanceController@updateAtt');
Route::get('student/attendance/edit/{slug}', 'StudentAttendanceController@edit');
Route::patch('student/attendance/edit/{slug}', 'StudentAttendanceController@update');
Route::delete('student/attendance/delete/{id}', 'StudentAttendanceController@delete');
Route::get('student/attendance/getList', [
    'as' => 'attendance.dataTable',
    'uses' => 'StudentAttendanceController@getDatatable'
]);

////////////////////////////////
////////year grades report//////
////////////////////////////////

Route::get('student/year_grades/{slug}', 'gradeYearReportController@index');
Route::get('student/year_grades/add/{slug}', 'gradeYearReportController@create');
Route::post('student/year_grades/update/{slug}', 'gradeYearReportController@updateYearGrades');
Route::get('student/year_grades/template/import', 'gradeYearReportController@importGradeYearTemplate');
Route::get('student/year_grades/upload/{slug}', 'gradeYearReportController@getUploadTemplate');
Route::post('student/year_grades/import/file', 'gradeYearReportController@saveYear_Grades');
Route::get('student/year_grades-report/{slug}', 'gradeYearReportController@create');
 


Route::get('student/attendance-report/{slug?}', 'StudentAttendanceController@attendance_report');

Route::post('student/attendance-report', 'StudentAttendanceController@makeReport');
//Student Attendence Report

Route::get('student/attendance/reports/{slug}', 'StudentAttendanceReportController@viewStudentAttendance');
Route::post('student/attendance/reports/get-attendance', 'StudentAttendanceReportController@getAttendance');
Route::post('student/attendance/reports/count-attendance', 'StudentAttendanceReportController@countAttendance');

Route::get('student/class-attendance/{slug?}', 'StudentAttendanceReportController@classAttendance');
Route::post('student/class-attendance', 'StudentAttendanceReportController@getClassAttendance');
Route::post('student/class-attendance/print', 'StudentAttendanceReportController@printClassAttendance');
Route::post('lessionplan/by-attendance', 'StudentAttendanceReportController@getLessionPlansLatestRecords');


//////////////////////////
// STUDENT MARKS MODULE //
//////////////////////////

Route::get('student/class-marks', 'StudentMarksReportController@classMarks');
Route::post('student/class-offline-exams-list', 'StudentMarksReportController@offlineExamsList');
Route::post('student/class-marks', 'StudentMarksReportController@getClassMarks');
Route::post('student/class-marks/offline-exams/print', 'StudentMarksReportController@printClassMarks');

//Student List According to the class

Route::get('student/list', 'StudentListController@index');
Route::post('student/list/classwise/print', 'StudentListController@printStudentList');
Route::post('student/list/classwise/download', 'StudentListController@downloadStudentList');
Route::get('student/completed/list', 'StudentListController@courseCompltedStudentsList');
Route::post('student/completed/list/print', 'StudentListController@printCourseCompltedStudentsList');
Route::get('student/detained/list', 'StudentListController@courseDetainedStudentsList');
Route::post('student/detained/list/print', 'StudentListController@printCourseDetainedStudentsList');
// Route::post('student/class-offline-exams-list', 'StudentMarksReportController@offlineExamsList');
// Route::post('student/class-marks', 'StudentMarksReportController@getClassMarks');


// LESSION PLANS MODULE
Route::get('staff/lession-plans/{slug}', 'LessionPlansController@index');
Route::get('staff/lession-plans/view-topics/{userSlug}/{courseSlug}', 'LessionPlansController@viewTopics');
Route::post('staff/lession-plans/update-topic', 'LessionPlansController@updateTopic');
Route::post('staff/lession-plans/get-last-updated-records', 'LessionPlansController@getLastUpdatedRecords');
Route::get('staff/lession-plans/student-list/{slug}', 'LessionPlansController@studentlist');
Route::post('staff/lession-plans/view-students', 'LessionPlansController@viewStudents');
Route::get('staff/topics', 'TopicsController@staffindex');
Route::get('staff/topics/view/{year}/{sem}/{course}/{subject}', 'TopicsController@staffindexList');

Route::get('staff/topics/add', 'TopicsController@create');
Route::post('staff/topics/add', 'TopicsController@store');
Route::get('staff/topics/edit/{slug}', 'TopicsController@edit');
Route::patch('staff/topics/edit/{slug}', 'TopicsController@update');
Route::delete('staff/topics/delete/{id}', 'TopicsController@delete');
Route::get('staff/topics/import', 'TopicsController@import');
Route::post('staff/topics/import', 'TopicsController@readExcel');

Route::get('staff/lession-plans/view-students/get-list/{academic_id}/{course_parent_id}/{course_id}/{year}/{semister}',
    'LessionPlansController@getDatatable');

Route::get('student/lession-plans/{slug}', 'LessionPlansController@Studentindex');
Route::get('student/lession-plans/view-topics/{userSlug}/{courseSlug}', 'LessionPlansController@StudentviewTopics');


//STUDENT TRANSFERS
Route::get('student/transfers', 'StudentPromotionsController@index');
Route::post('student/transfers', 'StudentPromotionsController@transferStudents');
Route::get('student/reback-detain', 'StudentPromotionsController@rebackDetain');
Route::get('student/reback-completed', 'StudentPromotionsController@rebackCompleted');
Route::post('student/transfer-single-student', 'StudentPromotionsController@transferSingleStudent');

//SEARCH STUDENT
Route::post('student/search', 'StudentController@globalStudentSearch');

//OFFLINE MARKS UPLOAD
Route::get('marks/upload', 'QuizResultsController@index');

//TIMETABLE MODULE
Route::get('timetable', 'TimetableController@index');


//Timingset
Route::get('timetable/timing-set', 'TimingsetController@index');
Route::get('timetable/timing-set/add', 'TimingsetController@create');
Route::post('timetable/timing-set/add', 'TimingsetController@store');
Route::get('timetable/timing-set/edit/{slug}', 'TimingsetController@edit');
Route::patch('timetable/timing-set/edit/{slug}', 'TimingsetController@update');
Route::delete('timetable/timing-set/delete/{slug}', 'TimingsetController@delete');
Route::delete('timetable/timing-set/delete-timingset-record/{slug}', 'TimingsetController@deleteTimingsetRecord');
Route::get('timetable/timing-set/getList', [
    'as' => 'timingset.dataTable',
    'uses' => 'TimingsetController@getDatatable'
]);


//Allot time table
Route::get('timetable/allot-timetable', 'TimetableController@viewTimetable');
// Route::get('timetable/print', 'TimetableController@printTimetable');
Route::post('timetable/print', 'TimetableController@printTimetable');

Route::post('timetable/get-timetable-details', 'TimetableController@getAllocatedStaffAndTimings');

Route::post('timetable/update-timetable', 'TimetableController@updateTimetable');
Route::post('timetable/is-available', 'TimetableController@checkStaffIsAvailable');

Route::get('timetable/user/{slug}', 'TimetableController@staffTimetable');
Route::get('timetable/print/{slug}', 'TimetableController@printStaffStudentTimetable');


//Timing Map
Route::get('timetable/map-timingset/{slug}', 'MapTimingsetController@create');
Route::post('timetable/map-timingset/add/{slug}', 'MapTimingsetController@store');


//Timing Map Parent list
Route::get('maptimingset/parent', 'MapTimingSetParentController@index');
Route::get('maptimingset/parent/add', 'MapTimingSetParentController@create');
Route::post('maptimingset/parent/add', 'MapTimingSetParentController@store');
Route::get('maptimingset/parent/edit/{slug}', 'MapTimingSetParentController@edit');
Route::patch('maptimingset/parent/edit/{slug}', 'MapTimingSetParentController@update');
Route::delete('maptimingset/parent/delete/{id}', 'MapTimingSetParentController@delete');
Route::get('maptimingset/parent/getList', [
    'as' => 'maptimingsetparent.dataTable',
    'uses' => 'MapTimingSetParentController@getDatatable'
]);
Route::get('maptimingset/parent/get-academics', 'MapTimingSetParentController@getAcademics');


//Transportation Vechicles
Route::get('transportation/vehicles', 'TransportationController@index');
Route::get('transportation/vehicles/add', 'TransportationController@create');
Route::post('transportation/vehicles/add', 'TransportationController@store');
Route::get('transportation/vehicles/edit/{slug}', 'TransportationController@edit');
Route::patch('transportation/vehicles/edit/{slug}', 'TransportationController@update');
Route::delete('transportation/vehicles/delete/{id}', 'TransportationController@delete');
Route::get('transportation/vehicles/getlist', [
    'as' => 'transportationvehicles.dataTable',
    'uses' => 'TransportationController@getDatatable'
]);


//Transportation Vechicles types
Route::get('transportation/vehicles/types', 'TransportationVehicletypeController@index');
Route::get('transportation/vehicles/types/add', 'TransportationVehicletypeController@create');
Route::post('transportation/vehicles/types/add', 'TransportationVehicletypeController@store');
Route::get('transportation/vehicles/types/edit/{slug}', 'TransportationVehicletypeController@edit');
Route::patch('transportation/vehicles/types/edit/{slug}', 'TransportationVehicletypeController@update');
Route::delete('transportation/vehicles/types/delete/{id}', 'TransportationVehicletypeController@delete');
Route::get('transportation/vehicles/types/getlist', [
    'as' => 'transportationvehicletypes.dataTable',
    'uses' => 'TransportationVehicletypeController@getDatatable'
]);


//Offline Exams
Route::get('academicoperations/offline-exams', 'OfflineExamsController@index');
Route::get('academicoperations/offline-exams/exams/{slug}', 'OfflineExamsController@selectionview');
Route::post('academicoperations/offline-exams/add', 'OfflineExamsController@entermarks');
Route::post('academicoperations/offline-exams/store', 'OfflineExamsController@store');
Route::get('academicoperations/offline-exams/getlist', [
    'as' => 'offlineexams.dataTable',
    'uses' => 'OfflineExamsController@getDatatable'
]);
Route::delete('academicoperations/offline-exams/delete/{id}', 'OfflineExamsController@delete');


Route::get('academicoperations/offline-exams/import-excel', 'OfflineExamsController@import');
Route::post('academicoperations/offline-exams/import-excel', 'OfflineExamsController@readExcel');
Route::post('academicoperations/offline-exams/get-information', 'OfflineExamsController@getOfflineExamsInformation');


//OfflineExams Quiz Categories
Route::get('offlineexmas/quiz/categories', 'OfflineQuizCategoriesController@index');
Route::get('offlineexmas/quiz/categories/add', 'OfflineQuizCategoriesController@create');
Route::post('offlineexmas/quiz/categories/add', 'OfflineQuizCategoriesController@store');
Route::get('offlineexmas/quiz/categories/edit/{slug}', 'OfflineQuizCategoriesController@edit');
Route::patch('offlineexmas/quiz/categories/edit/{slug}', 'OfflineQuizCategoriesController@update');
Route::delete('offlineexmas/quiz/categories/delete/{id}', 'OfflineQuizCategoriesController@delete');
Route::get('offlineexmas/quiz/categories/getList', [
    'as' => 'offlinequizcategories.dataTable',
    'uses' => 'OfflineQuizCategoriesController@getDatatable'
]);


//academic operations ---dashboard
Route::get('academicoperations/dashboard', 'AcademicOperationsDashboardController@index');

//Attendance operations --Dashboard
Route::get('attendanceoperations/dashboard', 'AttendanceOperationsDashboardController@index');
Route::get('attendanceoperations/record-student', 'StudentNamesRecordingController@index');
Route::post('student/recorder', 'StudentNamesRecordingController@Store');
Route::post('attendanceoperations/recorder/delete', 'StudentNamesRecordingController@Delete');



//Student Quiz dashboard
Route::get('student/quiz/dashboard', 'StudentQuizController@dashboard');

Route::get('user/search', 'SearchController@index');

Route::post('html/print-data', 'PrinterController@printHtml');

////////////////////
// UPDATE PATCHES //
////////////////////
Route::get('updates/patch1', 'UpdatesController@patch1');


//recycle bin routes

Route::get('trashes/list', 'TrashesController@index');
Route::get('trashes/getList', 'TrashesController@getDatatable');
Route::get('trashes/retrieve/{slug}/{table}','TrashesController@retrieve');
Route::get('trashes/destroy/{slug}/{table}','TrashesController@destroy');
Route::get('trashes/destroy_all','TrashesController@Destroy_all');

Route::get('get_categories/{id}/{table}',function ($id,$table){
   return getCategory($id,$table);
});

Route::get('get_school_categories',function (){
    return \App\Category::all();
});

Route::get('get_category_courses/{id}',function ($id){
    return \App\Course::withoutGlobalScope(\App\Scopes\CategoryScope::class)->where('category_id',$id)->where('parent_id',0)->get();
});


Route::get('get_course_data/{slug}',function ($slug){
    return \App\Course::withoutGlobalScope(\App\Scopes\CategoryScope::class)->where('slug',$slug)->first();
});

Route::get('get_sub_courses/{id}',function ($id){
    return \App\Course::withoutGlobalScope(\App\Scopes\CategoryScope::class)->where('parent_id',$id)->get();
});

Route::get('get_cat_year_courses/{id}/{year}',function ($id,$year){
    return \App\Course::withoutGlobalScope(\App\Scopes\CategoryScope::class)
        ->join('academic_course','courses.id','=','academic_course.course_id')
        ->where('category_id',$id)->where('parent_id',0)
        ->where('academic_course.academic_id',$year)
        ->get();
});

Route::get('get_exams/{course}/{subject}',function ($course,$subject){
    return \App\Quiz::join('quizapplicability', 'quizapplicability.quiz_id', '=', 'quizzes.id')
                      ->select(['quizzes.id','quizzes.title','quizzes.slug'])->where('quizapplicability.course_id',$course)->where('subject_id',$subject)->get();
});
//get_exams

// get logged users
Route::get('userslogged/list','UsersLoginController@index');
Route::get('userslogged/getList', 'UsersLoginController@getDatatable');
Route::get('userslogged/destroy','UsersLoginController@deleteRecords');
Route::get('users/switchUser/{slug}','UsersLoginController@switchUser');
Route::get('users/switchAdmin/{slug}','UsersLoginController@switchAdmin');
Route::get('users/switchStaff/{slug}','UsersLoginController@switchStaff');
Route::get('users/switchSupervisor/{slug}','UsersLoginController@switchSupervisor');
Route::get('users/switchTeacher/{slug}','UsersLoginController@switchTeacher');

//get_default_selectors

Route::get('get_default_selectors/{slug}/{table}',function ($slug,$table){
       $quiz             = DB::table($table)->where('slug',$slug)->first();
       $current_category = QuizCategory::where('id',$quiz->category_id)->first();
       //$current_category->type = $quiz->type;
        if($table == 'quizzes'){
            return json_encode($quiz);
        }
       return $current_category;
});

Route::get('get_quiz_data/{slug}',function ($slug){
    $quiz             = \App\Quiz::where('slug',$slug)->first()->subject_id;
    $result = \App\CourseSubject::where('id',$quiz)->first();

    return $result;
});
Route::get('get_series_data/{slug}',function ($slug){
    $lms             = \App\LmsSeries::where('slug',$slug)->first()->lms_category_id;
    $result = \App\LmsCategory::where('id',$lms)->first();
    $course=\App\Course::where('id',$result->course_id)->first();
    return $course;
});

Route::get('get_default_selectors2/{slug}/{table}',function ($slug,$table){
    $quiz             = DB::table($table)->where('slug',$slug)->first();
    $current_category = \App\LmsCategory::where('id',$quiz->lms_category_id)->first();
    return $current_category;
});


Route::get('available_HW_extn',function (){
    $data =  \App\Settings::get_HW_extensions();
    $extn = $data->value;
    return $extn;
});

Route::get('available_Massages_extn',function (){
    $data =  \App\Settings::get_message_extensions();
    $extn = $data->value;
    return $extn;
});

Route::get('available_FeedBack_extn',function (){
    $data =  \App\Settings::get_feedback_extensions();
    $extn = $data->value;
    return $extn;
});

//subject and course routes
Route::get('get_years',function (){
    return \App\Academic::where('show_in_list',1)->get();
});


Route::get('get_courses/{year}/{sem}',function ($year,$sem){
    return getCourses($year,$sem);
});


Route::get('check_if_teacher/{slug}',function($slug){

  $teacher =   \App\User::where('slug',$slug) -> first();

          if(!$teacher ){
               return '0';
          }

          if($teacher -> role_id == 3 )  // teacher 
          {
            
            return  '1';

          }

return '0'; 

});


Route::get('check_if_teacher_also__login/{slug}',function($slug){

  $teacher =   \App\User::where('slug',$slug) -> first();

          if(!$teacher){
               return '2';
          }

           $current_login_user_id = Auth::id();
          if($teacher -> role_id == 3   && $teacher -> id == $current_login_user_id  )  // teacher 
          {
            
            return  '1';

          }

          if($teacher -> role_id == 1){
                
                return '2';

          }

 return '0'; 

});




Route::get('get_courses_2/{year}/{sem}/{staff_id?}',function ($year,$sem,$staff_id){

    if(Auth::user()->role_id == 3){
        return getTeacherCourses2($year,$sem,null);
    }
    else if ($staff_id != "null") {
      return getTeacherCourses2($year,$sem,$staff_id);
    }
    else if($staff_id == "null"){
        return 0;
    }
    return getCourses($year,$sem);
});

//getTeacherClasses
Route::get('teacher_classes/{sem}/{year}/{staff_id}/{course}',function ($sem,$year,$staff_id,$course){
    if(Auth::user()->role_id == 3){
        return getTeacherClasses($sem,$year,null,$course);
    }
    if(checkRole(getUserGrade(2)) && $staff_id == 'null'){
        return \App\Course::where('parent_id',$course)->get();
    }

    return getTeacherClasses($sem,$year,$staff_id,$course);


});



Route::get('supervisor/teacher-courses/{slug}',function($slug){

    $current_academic_id = new Academic();
    $semister = new App\AcademicSemester();
    $data['year']=$current_academic_id->getCurrentAcademic()->id;
    $current_semster = $semister->getCurrentSemeterOfAcademicYear($data['year'])->sem_num;

    return \App\Course::join('course_subject','courses.id','=','course_subject.course_parent_id')
        ->select(['courses.id','courses.course_title'])
        ->where('courses.parent_id',0)
        ->where('course_subject.semister',$current_semster)
        ->where('course_subject.staff_id',get_user_id_from_slug($slug))
        ->distinct()->get();

});

Route::get('get_year_sems/{year}',function ($year){
    $sems =  \App\AcademicSemester::where('academic_id',$year)->select(['id','sem_num'])->get();
    foreach ($sems as $sem){
        $sem->title = number_to_word($sem->sem_num);
    }
    return $sems;
});

Route::get('get_defualt_sem/{year}',function ($year){
    return default_sem($year);
});
//get_courses_2



Route::get('supervisor/teacher-subjects/{year}/{sem}/{course}/{slug}',function($year,$sem,$course,$slug){

    return \App\CourseSubject::join('subjects','course_subject.subject_id','=','subjects.id')
        ->where('academic_id',$year)
        ->where('semister',$sem)
        ->where('course_id',$course)
        ->where('staff_id',get_user_id_from_slug($slug))
        ->select(['course_subject.id','course_subject.subject_id','course_subject.slug','subjects.subject_title'])
        ->get();

});


//getTeacherCourses

Route::get('get_branches',function (){
    return \App\Branch::all();
});

Route::get('get_subjects/{year}/{sem}/{course}',function ($year,$sem,$course){
    if(Auth::user()->role_id == 3){
        return getTeacherSubjects($year,$sem,$course);
    }
    return getSubjects($year,$sem,$course);
});

Route::get('get_subjects_2/{year}/{sem}/{course}/{slug?}',function ($year,$sem,$course,$slug){
    if(Auth::user()->role_id == 3){
        return getTeacherSubjects2($year,$sem,$course,null);
    }
    elseif ($slug != 'null') {
      return getTeacherSubjects2($year,$sem,$course,$slug);
    }
    return getSubjects2($year,$sem,$course);
});

Route::get('get_teacher_subjects/{year}/{sem}/{course}',function ($year,$sem,$course){
    return getTeacherSubjects($year,$sem,$course, null);
});

Route::get('get_subject_edit/{id}',function ($id){
    return getSubjectDetails($id);
});

Route::get('get_topic_name/{id}',function ($id){
    return \App\Topic::where('id',$id)->first()->topic_name;
});


Route::get('current_year_sem',function (){

    $current_academic_id = new Academic();
    $data['year']=$current_academic_id->getCurrentAcademic()->id;
    $semister = new App\AcademicSemester();

    $data['semister'] = $semister->getCurrentSemeterOfAcademicYear($data['year']);
    if($data['semister']){
        $data['semister'] =  $data['semister']->sem_num;
    }else {  $data['semister'] = 1; }

    return $data;

});


Route::get('get_series/{year}/{sem}/{course}/{subject}/{topic}',function ($year,$sem,$course,$subject,$topic){
    $items 			= App\LmsContent::where('academic_id','=',$year)->where('sem_id','=',$sem)
    ->where('course_id','=',$course)
    ->where('subject_id','=',$subject);
   // ->where('topic_id','=',$topic)
   //->get();

    if($topic!="" && $topic!="0" && $topic!=null)
    {
    $parenttopics = App\Topic::where('parent_id',$topic)->select('id')->get();

    $items = $items->where('topic_id',$topic);
    }
    $results=$items->get();
    return json_encode(array('items'=>$results));
});




Route::get('get_all_courses',function (){
    if(Auth::user()->role_id == 3){
       return  \App\SubjectPreference::join('subjects','subjectpreferences.subject_id','=','subjects.id')
                                     ->where('subjectpreferences.user_id',Auth::user()->id)
                                     ->select(['subjects.subject_title','subjects.id'])
                                     ->get();
    }
    return \App\Subject::select(['subjects.subject_title','subjects.id'])->get();
});

Route::get('get_lms_content/{slug}',function ($slug){
    $data =  \App\LmsContent::where('slug',$slug)->first();
    $data->parent_topic = \App\Topic::where('id',$data->topic_id)->pluck('parent_id')->first();
    return $data;
});

Route::get('get_question_data/{slug}',function ($slug){
    $data =  \App\QuestionBank::where('slug',$slug)->first();
    $data->parent_topic = \App\Topic::where('id',$data->topic_id)->pluck('parent_id')->first();
    return $data;
});

Route::get('get_topics/{subject}/{course}', function ($subject,$course) {
    return \App\Topic::where('parent_id',0)->where('subject_id',$subject)->where('course_id',$course)->get();
});

Route::get('get_sub_topic/{id}',function ($id){
    $data = \App\Topic::where('parent_id',$id);
    $main = \App\Topic::where('id',$id)->union($data)->get();
    return $main;
});

Route::get('get_all_topics/{subject}/{course}/{year}/{sem}', function ($subject,$course,$year,$sem) {
    return \App\Topic::where('subject_id',$subject)
    ->where('course_id',$course)->where('semester_num',$sem)->where('academic_id',$year)
    ->orderBy('parent_id') ->select('topics.*','topics.id as total')->get();
    /*$subtopics= DB::table('questionbank')
    ->leftjoin('topics','questionbank.topic_id','=','topics.id')
    ->select('topics.*', DB::raw('count(questionbank.topic_id) as total'))
    ->groupBy('questionbank.topic_id')
    ->where('topics.subject_id',$subject)->where('topics.course_id',$course)->where('topics.semester_num',$sem)
    ->where('topics.academic_id',$year)->where('topics.parent_id','!=','0')->distinct()->orderBy('topics.parent_id')->get();*/

});
Route::get('get_all_topicscount/{subject}/{course}/{year}/{sem}/{quiz}', function ($subject,$course,$year,$sem,$quiz) {
    ///return \App\Topic::where('subject_id',$subject)->where('course_id',$course)->where('semester_num',$sem)->where('academic_id',$year)->orderBy('parent_id')->get();

    $ids=DB::table('questionbank_quizzes')
    ->join('quizzes','questionbank_quizzes.quize_id','=','quizzes.id')
    ->where('quizzes.slug','=',$quiz)->where('quizzes.record_status','!=','3')->pluck("questionbank_quizzes.questionbank_id");

    return
    DB::table('questionbank')
  ->leftjoin('topics','questionbank.topic_id','=','topics.id')
  ->select('questionbank.topic_id', DB::raw('count(questionbank.topic_id) as total'))
  ->groupBy('questionbank.topic_id')
  ->whereNotIn('questionbank.id',$ids)
  ->where('topics.subject_id',$subject)
  ->where('topics.record_status','!=','3')
  ->where('topics.course_id',$course)->where('topics.semester_num',$sem)
  ->where('topics.academic_id',$year)->where('topics.parent_id','!=','0')->distinct()->orderBy('topics.parent_id')->get();


});

Route::get('get_all_series_topicscount/{subject}/{course}/{year}/{sem}/{slug}', function ($subject,$course,$year,$sem,$slug) {
    ///return \App\Topic::where('subject_id',$subject)->where('course_id',$course)->where('semester_num',$sem)->where('academic_id',$year)->orderBy('parent_id')->get();

    $ids=DB::table('lmsseries_data')
    ->join('lmsseries','lmsseries_data.lmsseries_id','=','lmsseries.id')
    ->where('lmsseries.slug','=',$slug)->where('lmsseries.record_status','!=','3')->pluck("lmsseries_data.lmscontent_id");

    return
    DB::table('lmscontents')
  ->leftjoin('topics','lmscontents.topic_id','=','topics.id')
  ->select('lmscontents.topic_id', DB::raw('count(lmscontents.topic_id) as total'))
  ->groupBy('lmscontents.topic_id')
  ->whereNotIn('lmscontents.id',$ids)
  ->where('topics.subject_id',$subject)
  ->where('topics.record_status','!=','3')
  ->where('topics.course_id',$course)->where('topics.semester_num',$sem)
  ->where('topics.academic_id',$year)->where('topics.parent_id','!=','0')->distinct()->orderBy('topics.parent_id')->get();


});

Route::get('get_subjects_timetable/{subject}/{course}',function ($subject,$course){
    return \App\TimingsetDetails::join('timetable','timingsetdetails.id','=','timetable.timingset_details_id')
                ->where('timetable.subject_id', '=', $subject)
                ->where('timetable.course_id', '=',  $course)
                ->where('timetable.user_id', '=', Auth::user()->id)
        ->get();
});

Route::get('get_subject_topics/{subject}/{course}/{sem}', function ($subject,$course,$sem) {
    return get_subject_topics($subject,$course,$sem);
});

Route::get('get_subjects_by_course/{course}',function ($course){
    if(Auth::user()->role_id == 3){
        return \App\CourseSubject::join('subjects','course_subject.subject_id','=','subjects.id')->select(['subjects.id','subjects.slug','subjects.subject_title'])
            ->where('course_parent_id',$course)->where('staff_id',Auth::user()->id)->groupBy('course_subject.subject_id')->get();
    }
    return \App\CourseSubject::join('subjects','course_subject.subject_id','=','subjects.id')->select(['subjects.id','subjects.slug','subjects.subject_title'])
                             ->where('course_parent_id',$course)->groupBy('course_subject.subject_id')->get();
});

Route::get('get_toopy/{year}/{course}/{subject}/{sem}', function ($year,$course,$subject,$sem) {
    return get_Topics($year,$course,$subject,$sem);
});

Route::get('/get_classes/{course}',function ($course){
    return \App\Course::where('parent_id',$course)->get();
});
//test Route

Route::get('/test_it/{key}', function ($key){
    return json_encode(ValidateMail($key));
});

Route::get('/test_2', function () {

    return get_user_branch();
});

Route::get('/fix_mails', function () {

    $users =  \App\User::get();
    foreach ($users as $user){
        if(ValidateMail($user->email) == false){
            $user->email = $user->id_number."@".$_SERVER['SERVER_NAME'];
            $user->save();
        }
    }
    return "done";
});

Route::get('/get_countries', function () {
    $countries =  DB::table('countries')->orderBy('id')->get();
    foreach ($countries as $country){
        $country->nationality = getPhrase($country->country_name);
    }
    return $countries;
});

Route::get('/courseSem', function () {
    $ids =  \App\Course::withoutGlobalScope(\App\Scopes\CategoryScope::class)->where('parent_id',0)->select(['id'])->get();
    \App\Course::withoutGlobalScope(\App\Scopes\CategoryScope::class)->where('parent_id',0)->update(['is_having_semister' => 1]);
    foreach ($ids as $id){
        $CS = new \App\CourseSemister();
        $CS->course_id        = $id->id;
        $CS->year             = 1;
        $CS->total_semisters  = 2;
        $CS->current_semester = 0;
        $CS->save();
    }
    return "Done";
});



//return getMaxID();

Route::get('tables/list', 'TablesController@index');
Route::get('tables/fix', 'TablesController@fix');

//get_countries

Route::get('/record_branch', function () {
  $TABLES_IN_DB='Tables_in_'.env('DB_DATABASE');
    $tables = DB::select('SHOW TABLES');
    $right_tables = ['authors','examseries','examtoppers','feedbacks',
                    'libraryassettypes','librarymasters','modulehelper',
                    'notifications','quizofflinecategories','publishers',
                    'quizzes','quizcategories','quizresults','staff','students','users','users_login'];
    foreach ($tables as $table){
        if(in_array($table->$TABLES_IN_DB,$right_tables)) {
            continue;
        }
        $columns = Schema::getColumnListing($table->$TABLES_IN_DB);
        if(in_array('branch_id',$columns)) {

            DB::statement("ALTER TABLE `$table->$TABLES_IN_DB` DROP `branch_id`");
//              DB::statement("ALTER TABLE `$table->$TABLES_IN_DB` DROP  `branch_id`");
        }

    }
    return "Done";
});




Route::get('/popup_data', function () {
    return view("layouts.general");
});



