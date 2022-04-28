<?php

declare(strict_types=1);

use App\Http\Controllers\ClassController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\InstructionController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\TimingSetController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UniversitySettingController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Models\User;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TimetableController;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/
Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->prefix('api')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/gettoken', function(){
        $user  = User::first();
        $token = $user->createToken('new-token');
        return $token;
    });
});

Route::middleware([
    'api','auth:sanctum',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->prefix('api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('users',UserController::class);
    Route::apiResource('academics',AcademicYearController::class);
    Route::apiResource('branches',BranchController::class);
    Route::apiResource('categories',CategoryController::class);
    Route::apiResource('programs',ProgramController::class);
    Route::apiResource('tracks',TrackController::class);
    Route::apiResource('subjects',SubjectController::class);
    Route::apiResource('levels',LevelController::class);
    Route::apiResource('courses',CourseController::class);
    Route::apiResource('classes',ClassController::class);
    Route::apiResource('timing-sets',TimingSetController::class);
    Route::apiResource('feedbacks',FeedbackController::class);
    Route::apiResource('topics',TopicController::class);
    Route::apiResource('contents',ContentController::class);
    Route::apiResource('instructions',InstructionController::class);

    Route::get('topics/get-subtopics/{topic}',[TopicController::class,'getSubTopics'])->middleware('can:view,topic');
    Route::get('topics/get-parent/{topic}',[TopicController::class,'getParentTopic'])->middleware('can:view,topic');
    Route::get('topics/get-content/{topic}',[TopicController::class,'getTopicContent'])->middleware('can:view,topic');


    Route::delete('feedbacks/remove-file/{feedback}',[FeedbackController::class,'removeFile'])
        ->middleware('can:update,feedback');

    Route::post('import/students', [ImportController::class, 'storeStudents']);
    Route::post('import/staff', [ImportController::class, 'storeStaff']);
    Route::post('users/suspend/{user}',[UserController::class,'suspend'])->middleware('can:suspend,user');
    Route::post('users/unsuspend/{user}',[UserController::class,'unsuspend'])->middleware('can:suspend,user');
    Route::get('site-settings',[SettingController::class,'showSiteSettings']);
    Route::patch('site-settings',[SettingController::class,'UpdateSiteSettings']);

    Route::get('levels/assigned/{program}/{track}',[LevelController::class,'getAssigned'])
        ->middleware('can:assign-level');

    Route::post('levels/assign/{program}/{track}',[LevelController::class,'setAssigned'])
        ->middleware('can:assign-level');

    Route::get('university/get-settings/{program}/{track}',[UniversitySettingController::class,'getSettings']);
    Route::post('university/set-settings/{program}/{track}',[UniversitySettingController::class,'setSettings'])
        ->middleware('can:set-settings');

    Route::get('subjects/get-staff/{semester}/{class?}',[SubjectController::class,'getStaff']);
    Route::post('subjects/set-staff/{semester}/{class?}',[SubjectController::class,'setStaff'])
        ->middleware('can:assign-staff');

    Route::get('courses/get-classes/{course}',[CourseController::class,'getClasses'])->middleware('can:view,course');
    Route::get('courses/subjects/{course}',[CourseController::class,'getSubjects']);
    Route::post('courses/subjects/{course}',[CourseController::class,'setSubjects']);

    Route::get('classes/subjects/{class}',[CourseController::class,'getClassSubjects']);
    Route::delete('classes/subjects/{class}/{subject}',[CourseController::class,'deleteClassSubject']);
    Route::get('tracks/assigned-subjects/{track}/{program}/{semester}/{level?}',[TrackController::class,'AssignedSubjects'])
        ->middleware('can:assign-subject');

    Route::get('subjects/config/{subject_id}',[SubjectController::class,'showSubjectConfig']);
    Route::post('subjects/config/{subject_id}',[SubjectController::class,'updateSubjectConfig']);

    Route::get('subjects/get-topics/{subject}/{semester?}',[SubjectController::class,'getTopics']);

    Route::post('tracks/assign-subjects/{track}/{program}/{level?}',[TrackController::class,'AssignSubjects'])
        ->middleware('can:assign-subject');

    Route::get('timetable/user/{username}',[TimetableController::class,'getUserSchedule']);
    Route::get('timetable/class/{class}',[TimetableController::class,'getClassSchedule']);
    Route::get('timetable/level/{program}/{track}/{level}',[TimetableController::class,'getLevelSchedule']);
    Route::post('timetable/update/',[TimetableController::class,'updateSchedule']);
    Route::get('timetable/staff_available/',[TimetableController::class,'isStaffAvailable']);
    Route::post('timetable/staff-report/',[TimetableController::class,'staffReport']);
    Route::post('timetable/classes-report/',[TimetableController::class,'classesReport']);

    Route::get('lessons/check/{topic}/{class?}',[LessonController::class,'check'])->middleware('can:check-lessons');
    Route::get('lessons/{subject}/{class?}',[LessonController::class,'get']);
});
