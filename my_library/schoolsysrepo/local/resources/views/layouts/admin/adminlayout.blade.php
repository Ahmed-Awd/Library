<!DOCTYPE html>

<html lang="en" dir="{{ (App\Language::isDefaultLanuageRtl()) ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{getSetting('meta_description', 'seo_settings')}}">
    <meta name="keywords" content="{{getSetting('meta_keywords', 'seo_settings')}}">

    <link rel="icon" href="{{IMAGE_PATH_SETTINGS.getSetting('site_favicon', 'site_settings')}}" type="image/x-icon"/>
    <title>@yield('title') {{ isset($title) ? $title : getSetting('site_title','site_settings') }}</title>
    <!-- Bootstrap Core CSS -->

@yield('header_scripts')
<!-- edit here -->
    @if(Session::has('lang_dir') && Session::get('lang_dir') == 0 )
        <link href="{{CSS}}bootstrap_en.min.css" rel="stylesheet">
        <link href="{{CSS}}{{\App\Settings::get_default_theme()}}-theme-en.css" rel="stylesheet">
    @else
        <link href="{{CSS}}bootstrap.min.css" rel="stylesheet">
        <link href="{{CSS}}{{\App\Settings::get_default_theme()}}-theme.css" rel="stylesheet">
    @endif

    <link rel="stylesheet" href="{{CSS}}bootstrap-datepicker.min.css">
    <link href="{{CSS}}sweetalert.css" rel="stylesheet" type="text/css">
    @if(isset($module_helper))
        <link href="{{CSS}}bootstrap-tour.css" rel="stylesheet">
    @endif
<!-- Morris Charts CSS -->
    <link href="{{CSS}}plugins/morris.css" rel="stylesheet">
    <!-- Proxima Nova Fonts CSS -->
    <link href="{{CSS}}proximanova.css" rel="stylesheet">
    <!-- Custom CSS -->
    <!-- edit here -->

    {{--<link href="{{CSS}}{{getSetting('current_theme', 'site_settings')}}-theme.css" rel="stylesheet">--}}
<!-- Custom Fonts -->
    <link href="{{CSS}}custom-fonts.css" rel="stylesheet" type="text/css">
    <link href="{{CSS}}materialdesignicons.css" rel="stylesheet" type="text/css">
    {{-- <link href="{{FONTAWSOME}}font-awesome.min.css" rel="stylesheet" type="text/css"> --}}
    {{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css"/>--}}
    <link rel="stylesheet" href="{{CSS}}font-awesome.css"/>


</head>

<body ng-app="academia">

@if(isset($module_helper))
    <!-- <div class="fab-buttons">
        <a href="javascript:void(0);" class="module-helper-link"
           onclick="startTour()" title="Help me">
            <i class="fa fa-question" aria-hidden="true"></i>
        </a>
        <a target="_blank" href="{{PREFIX}}documentation/#{{$module_helper->help_link_url}}" class="module-helper-link"
           title="Help URL">
            <i class="fa fa-link" aria-hidden="true"></i>
        </a>
    </div> -->
@endif
@yield('custom_div')
<?php
$class = '';
if (!isset($right_bar))
    $class = 'no-right-sidebar';

?>

<?php
$settings = \App\Settings::where('key', 'module')->first(['settings_data']);
$settings = json_decode($settings->settings_data);
$total = \App\user_feedback::get_new_count();
if ($settings->push_notifications->value == 1) {
    $total += \App\user_notifications::get_new_count();
}
$available_types = App\Settings::getMassages();
$current_user = Auth::user();
if ($settings->messaging->value == 1 && in_array($current_user->role_id, $available_types)) {
    $total += Auth::user()->newThreadsCount();
}

// \App\user_notifications::get_new_count()
// Auth::user()->newThreadsCount();
?>
<div id="wrapper" class="{{$class}}">
    <!-- Navigation -->
    <nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"><span
                        class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span
                        class="icon-bar"></span> <span class="icon-bar"></span></button>
            <a class="navbar-brand" href="{{PREFIX}}"><img
                        src="{{IMAGE_PATH_SETTINGS.getSetting('site_logo', 'site_settings')}}"
                        alt="{{getSetting('site_title','site_settings')}}"></a>
        </div>

        <!-- Top Menu Items -->
        <?php $newUsers = (new App\User())->getLatestUsers(); ?>
        <ul class="nav navbar-right top-nav">

            <li><a href="{{URL_HEADER_SEARCH_LINK}}">
                    <i class="fa fa-search"></i> {{ getPhrase('search_student') }}  </a>

            </li>

            <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-topbar-event"></i> {{ getPhrase('latest_users') }}  </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-notif" aria-labelledby="dd-notification">
                    <div class="dropdown-menu-notif-list" id="latestUsers">
                        @foreach($newUsers as $user)
                            <div class="dropdown-menu-notif-item">
                                <div class="photo">
                                    <img src="{{ getProfilePath($user->image)}}" alt="">
                                </div>
                                <a href="{{URL_USER_DETAILS.$user->slug}}">{{ucfirst($user->name)}}</a> {{ getPhrase('was_joined_as').' '. getRoleData($user->role_id)}}
                                <div class="color-blue-grey-lighter">{{$user->updated_at->diffForHumans()}}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="dropdown-menu-notif-more">
                        <a href="{{URL_USERS."users"}}">{{ getPhrase('see_more') }}</a>
                    </div>
                </div>
            </li>

            <li class="dropdown profile-menu">

                <div class="dropdown-toggle top-profile-menu" data-toggle="dropdown">
                    @if(Auth::check())

                        <h6 class="badge badge-success">{{ $total }}</h6>

                        <div class="username">
                            <h2>{{Auth::user()->name}}</h2>

                        </div>
                    @endif

                    <div class="profile-img"><img src="{{ getProfilePath(Auth::user()->image, 'thumb') }}" alt=""></div>
                    <div class="mdi mdi-menu-down"></div>
                </div>
                <ul class="dropdown-menu">
                    <li>

                        <a href="{{URL_USERS_EDIT}}{{Auth::user()->slug}}">
                            <sapn><i class="fa fa-user" aria-hidden="true"></i> {{ getPhrase('my_profile') }}</sapn>
                        </a>
                    </li>
                    <li>
                        <a href="{{URL_USERS_CHANGE_PASSWORD}}{{Auth::user()->slug}}">
                            <sapn><i class="fa fa-key" aria-hidden="true"></i> {{ getPhrase('change_password') }}</sapn>
                        </a>
                    </li>


                    <li>
                        <a href="{{URL_FEEDBACKS}}">
                            <sapn><i class="fa fa-commenting-o" aria-hidden="true">
                                    <h6 class="badge badge-success">{{\App\user_feedback::get_new_count()}}</h6>
                                </i>
                                {{ getPhrase('feedback') }}
                            </sapn>
                        </a>
                    </li>

                    @if($settings->push_notifications->value == 1)
                        <li>
                            <a href="{{URL_ADMIN_NOTIFICATIONS}}">
                                <sapn><i class="fa fa-bell-o" aria-hidden="true"></i>
                                    <h6 class="badge badge-success">{{ \App\user_notifications::get_new_count() }}</h6>
                                    {{ getPhrase('notifications') }}
                                </sapn>
                            </a>
                        </li>
                    @endif
                    @if($settings->messaging->value == 1 && in_array($current_user->role_id,$available_types))
                        <li>
                            <a href="{{URL_MESSAGES}}"><span><i class="fa fa-comments-o" aria-hidden="true"><h6
                                                class="badge badge-success">{{$count = Auth::user()->newThreadsCount()}}</h6></i></span>
                                {{ getPhrase('messages')}} </a>
                        </li>
                    @endif
                    @if(Module_state('language_settings'))
                        <li>
                            <a href="{{URL_LANGUAGES_LIST}}">
                                <sapn><i class="fa fa-language" aria-hidden="true"></i>
                                    {{ getPhrase('languages') }}</sapn>
                            </a>
                        </li>
                    @endif

                    <li>
                        <a href="{{URL_TRASH_LIST}}">
                            <sapn><i class="fa fa-trash" aria-hidden="true"></i>
                                {{ getPhrase('Recycle_Bin') }}</sapn>
                        </a>
                    </li>
                    <li>
                        <a href="{{URL_LOGGED_LIST}}">
                            <sapn><i class="fa fa-users" aria-hidden="true"></i>
                                {{ getPhrase('Logged_Users') }}</sapn>
                        </a>
                    </li>

                    <li>
                        <a href="{{URL_USERS_LOGOUT}}">
                            <sapn><i class="fa fa-sign-out" aria-hidden="true"></i> {{ getPhrase('logout') }}</sapn>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
        <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
        <!-- /.navbar-collapse -->
    </nav>
    @if(env('DEMO_MODE'))
        <div class="alert alert-info demo-alert">
            &nbsp;&nbsp;&nbsp;<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>{{getPhrase('info')}}!</strong> CRUD {{getPhrase('operations_are_disabled_in_demo_version')}}
        </div>
    @endif

    <aside class="left-sidebar">
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav side-nav">
                <li {{ isActive($active_class, 'dashboard') }}>
                    <a href="{{PREFIX}}">
                        <i class="fa fa-home" aria-hidden="true"></i>
                        {{ getPhrase('home_page') }}
                    </a>
                </li>

                <li {{ isActive($active_class, 'users') }}><a href="{{URL_USERS_DASHBOARD}}"><i class="fa fa-users"
                                                                                                aria-hidden="true"></i> {{ getPhrase('users') }}
                    </a></li>

                <li {{ isActive($active_class, 'master_settings') }} >

                    <a data-toggle="collapse" data-target="#master_settings" href="{{URL_SETTINGS_DASHBOARD}}"><i
                                class="fa fa-cog" aria-hidden="true"></i>

                        {{ getPhrase('master_settings') }} </a>

                    <ul id="master_settings" class="collapse sidemenu-dropdown">
                        @if(checkRole(getUserGrade(2)))

                            <li><a href="{{URL_COURSES_DASHBOARD}}"> <i class="fa fa-cogs"
                                                                        aria-hidden="true"></i> {{ getPhrase('master_setup') }}
                                </a></li>

                            <li><a href="{{URL_MASTERSETTINGS_SETTINGS}}"> <i
                                            class="icon-settings"></i> {{ getPhrase('settings') }}</a></li>

                            <li><a href="{{URL_SETTINGS_VIEW."time-table"}}"> <i
                                            class="icon-settings"></i> {{ getPhrase('time_table_settings')}}</a></li>
                            @if(($settings->coupons->value == 1))
                                <!-- <li {{ isActive($active_class, 'coupons') }} >

                                    <a href="{{URL_COUPONS}}" data-target="#coupons" href="javascript:void()"><i
                                                class="fa fa-hashtag"></i>
                                        {{ getPhrase('coupons') }} </a>



                                </li> -->
                            @endif

                        @endif

                        @if($settings->certificate->value == 1)
                            <!-- <li><a href="{{URL_MASTERSETTINGS_CERTIFICATES_SETTINGS}}"> <i
                                            class="icon-settings"></i> {{ getPhrase('certificates_settings') }}</a></li> -->
                        @endif




                        <li><a href="{{URL_MASTERSETTINGS_EMAIL_TEMPLATES}}"> <i class="fa fa-telegram"
                                                                                 aria-hidden="true"></i> {{ getPhrase('email_templates') }}
                            </a></li>

                    </ul>
                </li>



                <li {{ isActive($active_class, 'academic') }} >

                    <a data-toggle="collapse" data-target="#academic" href="{{URL_ACADEMICOPERATIONS_DASHBOARD}}">

                        <i class="fa fa-university" aria-hidden="true"></i>
                        {{ getPhrase('academic_operations') }} </a>

                    <ul id="academic" class="collapse sidemenu-dropdown">
                    <!-- <li><a href="{{URL_STUDENT_ATTENDENCE.Auth::user()->slug}}"> <i class="fa fa-check-circle"></i>{{ getPhrase('attendance') }}</a></li> -->

                        @if($settings->certificate->value == 1)
                            <!-- <li><a href="{{URL_CERTIFICATES_DASHBOARD}}"> <i
                                            class="fa fa-certificate"></i> {{ getPhrase('certificates')}}</a></li> -->
                        @endif
                        <li><a href="{{url('dues')}}"> <i
                                        class="fa fa-exchange"></i> {{ getPhrase('academic_dues')}}</a></li>
                        @if(getSetting("transfer_students", 'module'))
                            <li><a href="{{URL_STUDENT_TRANSFERS}}"> <i
                                            class="fa fa-exchange"></i> {{ getPhrase('transfers')}}</a>
                            </li>
                        @endif

                        @if(Module_state('daily_school_schedule'))
                            <li><a href="{{URL_TIMETABLE_DASHBOARD}}"> <i
                                            class="fa fa-clock-o"></i> {{ getPhrase('timetable')}}</a></li>
                        @endif
                        <!-- <li><a href="{{URL_STUDENT_CLASS_ATTENDANCE}}"> <i class="fa fa-check-square-o"
                                                                           aria-hidden="true"></i> {{ getPhrase('class_attendance_report')}}


                            </a></li> -->


                        <li><a href="{{url('student/year_grades/'.Auth::user()->slug)}}">
                                <i class="fa fa-check-square-o"aria-hidden="true"></i> 
                                {{ getPhrase('Year_Report')}}
                            </a></li>



                        @if(Module_state('exams'))
                            <li><a href="{{URL_STUDENT_MARKS_REPORT}}"> <i class="fa fa-line-chart"
                                                                           aria-hidden="true"></i> {{ getPhrase('class_marks_report')}}
                                </a></li>
                        @endif

                        <li><a href="{{URL_STUDENT_LIST}}"><i class="fa fa-users"
                                                              aria-hidden="true"></i> {{ getPhrase('student_list')}}</a>
                        </li>
                        @if(getSetting("transfer_students", 'module'))
                            <li><a href="{{URL_STUDENT_COMPLETED_LIST}}"><i class="fa fa-graduation-cap"
                                                                            aria-hidden="true"></i> {{ getPhrase('students_completed_list')}}
                                </a></li>
                        @endif
                        @if(getSetting("transfer_students", 'module'))
                            <li><a href="{{URL_STUDENT_DETAINED_LIST}}"><i class="fa fa-user-circle-o"
                                                                           aria-hidden="true"></i> {{ getPhrase('students_detained_list')}}
                                </a></li>
                        @endif





                        {{--<li><a href="{{URL_MASTERSETTINGS_RELIGIONS}}"> <i class="fa fa-rebel"--}}
                                                                           {{--aria-hidden="true"></i>{{ getPhrase('religions_master') }}--}}
                            {{--</a></li>--}}
                        {{--@if(Module_state('branches'))--}}
                            {{--<li><a href="{{URL_BRANCHES_LIST}}"><i class="fa fa-building"--}}
                                                                   {{--aria-hidden="true"></i> {{ getPhrase('branches') }}--}}
                                {{--</a></li>--}}
                        {{--@endif--}}

                        {{--<li><a href="{{URL_MASTERSETTINGS_CATEGORIES}}"> <i class="fa fa-arrows"--}}
                                                                            {{--aria-hidden="true"></i>{{ getPhrase('categories_master') }}--}}
                            {{--</a></li>--}}
                    </ul>

                </li>

                @if($settings->attendance_and_departure->value == 1)
                <li {{ isActive($active_class, 'attendance') }} >
                    <a data-toggle="collapse" data-target="#attendance" href="{{URL_AttendanceOperations_DASHBOARD}}">
                        <i class="fa fa-street-view" aria-hidden="true"></i>
                        {{ getPhrase('attendance_and_departure') }} </a>

                    <ul id="attendance" class="collapse sidemenu-dropdown">



                        <li><a href="{{URL_CERTIFICATES_GENERATE_IDCARD}}">
                                <i class="fa fa-id-card"aria-hidden="true"></i> {{ getPhrase('ID_cards')}}
                            </a></li>

                        <li><a href="{{URL_STUDENT_ATTENDENCE.Auth::user()->slug}}">
                                <i class="fa fa-user-secret"aria-hidden="true"></i> {{ getPhrase('Attendance_and_departure')}}
                            </a></li>

                        <li><a href="{{url('attendanceoperations/autocall/view')}}" target="_blank">
                                <i class="fa fa-assistive-listening-systems"aria-hidden="true"></i> {{ getPhrase('Automatic_call')}}
                            </a></li>

                        <li><a href="{{url('attendanceoperations/record-student')}}">
                                <i class="fa fa-microphone"aria-hidden="true"></i> {{ getPhrase('voice_records_for_students')}}
                            </a></li>

                        <li><a href="{{ATTENDANCE_REPORT_VIEW}}">
                                <i class="fa fa-file-archive-o"aria-hidden="true"></i> {{ getPhrase('Attendance_and_departure_report')}}
                            </a></li>



                        <li><a href="{{URL_STUDENT_CLASS_ATTENDANCE}}">
                                <i class="fa fa-check-square-o"aria-hidden="true"></i> {{ getPhrase('class_attendance_report')}}
                            </a></li>
                    </ul>

                </li>
                @endif


                    <li {{ isActive($active_class, 'homeworks') }} >
                        <a href="{{URL_HOMEWORK_VIEW}}">
                            <i class="fa fa-briefcase"></i>{{ getPhrase('Homeworks') }}</a></li>


            @if(Module_state('exams'))
                    <li {{ isActive($active_class, 'exams') }} >

                        <a data-toggle="collapse" data-target="#exams" href="{{URL_EXAMS_DASHBOARD}}"><i
                                    class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            </i>
                            {{ getPhrase('exams') }} </a>

                        <ul id="exams" class="collapse sidemenu-dropdown">
                            <li><a href="{{URL_QUIZ_QUESTIONBANK}}"> <i
                                            class="fa fa-question"></i>{{ getPhrase('question_bank') }}</a></li>
                            <li><a href="{{URL_QUIZ_CATEGORIES}}"> <i
                                            class="fa fa-random"></i>{{ getPhrase('exam_categories') }}
                                </a></li>
                            <li><a href="{{URL_QUIZZES}}"> <i class="icon-total-time"></i> {{ getPhrase('quiz')}}</a>
                            </li>
                            <li><a href="{{URL_QUIZ_REPORT_VIEW}}"> <i class="fa fa-file"></i> {{ getPhrase('REPORT_OF_QUIZ_RESULTS')}}</a>
                            </li>
                            <!-- <li><a href="{{URL_EXAM_SERIES}}"> <i
                                            class="fa fa-list-ol"></i> {{ getPhrase('exam_series')}}
                                </a></li> -->
                            {{--@if(Module_state('experimental_tests_only'))--}}
                                {{--<li><a href="{{URL_OFFLINEEXMAS_QUIZ_CATEGORIES}}"> <i class="fa fa-sort-amount-asc"--}}
                                                                                       {{--aria-hidden="true"></i> {{ getPhrase('offline_exams_categories')}}--}}
                                    {{--</a></li>--}}
                                {{--<li><a href="{{URL_OFFLINE_EXAMS}}"> <i class="fa fa-external-link"--}}
                                                                        {{--aria-hidden="true"></i> {{ getPhrase('offline_exams_')}}--}}
                                    {{--</a></li>--}}
                            {{--@endif--}}
                            <li><a href="{{URL_INSTRUCTIONS}}"> <i
                                            class="fa fa-hand-o-right"></i> {{ getPhrase('instructions')}}</a></li>

                        </ul>

                    </li>
                @endif

                @if(Module_state('management_of_educational_content'))
                    <li {{ isActive($active_class, 'lms') }} >

                        <a data-toggle="collapse" data-target="#lms" href="{{URL_LMS_DASHBOARD}}"><i
                                    class="fa fa-leanpub"
                                    aria-hidden="true"></i>
                            </i>
                            {{ getPhrase('lms') }} </a>

                        <ul id="lms" class="collapse sidemenu-dropdown">
                            <li><a href="{{ URL_LMS_CATEGORIES }}"> <i
                                            class="fa fa-random"></i>{{ getPhrase('lms_categories') }}</a></li>
                            <li><a href="{{ URL_LMS_SERIES }}"> <i
                                            class="fa fa-list-ol"></i>{{ getPhrase('lms_series') }}
                                </a></li>
                                <li><a href="{{ URL_LMS_CONTENT }}"> <i
                                                class="icon-books"></i>{{ getPhrase('lms_contents') }}
                                    </a></li>
                        </ul>
                    </li>
                @endif



                @if(Module_state('library_Management'))
                    <li {{ isActive($active_class, 'library') }} >

                        <a data-toggle="collapse" data-target="#library" href="{{URL_LIBRARY_LIBRARYDASHBOARD}}"><i
                                    class="fa fa-book" aria-hidden="true"></i>

                            {{ getPhrase('library') }} </a>

                        <ul id="library" class="collapse sidemenu-dropdown">

                            <li><a href="{{URL_LIBRARY_ASSETS}}"> <i
                                            class="fa fa-database"></i>{{ getPhrase('asset_types') }}</a></li>

                            <li><a href="{{URL_LIBRARY_MASTERS}}"> <i class="fa fa-book"
                                                                      aria-hidden="true"></i> {{ getPhrase('master_data')}}
                                </a></li>
                            <li><a href="{{URL_PUBLISHERS}}"> <i
                                            class="fa fa-paint-brush"></i>{{ getPhrase('publishers') }}
                                </a></li>
                            <li><a href="{{URL_AUTHORS}}"> <i class="fa fa-mortar-board"></i>{{ getPhrase('authors') }}
                                </a>
                            </li>
                            <li><a href="{{URL_LIBRARY_USERS}}student"> <i
                                            class="fa fa-user"></i>{{ getPhrase('students') }}</a></li>
                            <li><a href="{{URL_LIBRARY_USERS}}staff"><i class="fa fa-user-circle"
                                                                        aria-hidden="true"></i>
                                    {{ getPhrase('staff') }}</a></li>
                            <li><a href="{{URL_LIBRARY_LIBRARYDASHBOARD_BOOKS}}"> <i
                                            class="fa fa-book"></i>{{ getPhrase('student_book_return') }}</a></li>
                            <li><a href="{{URL_LIBRARY_LIBRARYDASHBOARD_BOOKS_STAFF}}"> <i class="fa fa-address-book"
                                                                                           aria-hidden="true"></i>{{ getPhrase('staff_book_return') }}
                                </a></li>
                        </ul>

                    </li>
                @endif
                <!-- <li {{ isActive($active_class, 'reports') }} >
                    <a data-toggle="collapse" data-target="#reports"><i class="fa fa-flag" aria-hidden="true"></i>
                        {{ getPhrase('payment_reports') }} </a>
                    <ul id="reports" class="collapse sidemenu-dropdown">
                        <li><a href="{{URL_ONLINE_PAYMENT_REPORTS}}"> <i
                                        class="fa fa-link"></i>{{ getPhrase('online_payments') }}</a></li>
                        @if($settings->offline_payment->value ==1 )
                            <li><a href="{{URL_OFFLINE_PAYMENT_REPORTS}}"> <i
                                            class="fa fa-chain-broken"></i>{{ getPhrase('offline_payments') }}</a></li>
                        @endif
                        <li><a href="{{URL_PAYMENT_REPORT_EXPORT}}"> <i
                                        class="fa fa-file-excel-o"></i>{{ getPhrase('export') }}</a></li>
                    </ul>

                </li> -->
            </ul>
        </div>
    </aside>
    @if(isset($right_bar))

        <aside class="right-sidebar" id="rightSidebar">
            <button class="sidebat-toggle" id="sidebarToggle" href='javascript:'><i class="mdi mdi-menu"></i></button>
            <div class="panel panel-right-sidebar">
                <?php $data = '';
                if (isset($right_bar_data))
                    $data = $right_bar_data;
                ?>
                @include($right_bar_path, array('data' => $data))
            </div>
        </aside>

    @endif
    @yield('content')
</div>
<!-- /#wrapper -->
<!-- jQuery -->
<script src="{{JS}}jquery-1.12.1.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="{{JS}}bootstrap.min.js"></script>


<!--JS Control-->
<script src="{{JS}}main.js"></script>
<script src="{{JS}}sweetalert-dev.js"></script>

@if(isset($module_helper))
    <script src="{{JS}}bootstrap-tour.min.js"></script>
    @include('common.module-helper', array('module_helper'=>$module_helper))
@endif


@yield('footer_scripts')
@include('layouts.scripts.popup')
@include('errors.formMessages')

@yield('custom_div_end')
{!!getSetting('google_analytics', 'seo_settings')!!}


<div class="ajax-loader" id="ajax_loader"><img src="{{AJAXLOADER}}"> {{getPhrase('please_wait')}}...</div>

</body>

</html>
