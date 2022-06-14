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
    @if(Session::has('lang_dir') && Session::get('lang_dir') == 0 )
        <link href="{{CSS}}bootstrap_en.min.css" rel="stylesheet">
        <link href="{{CSS}}{{\App\Settings::get_default_theme()}}-theme-en.css" rel="stylesheet">
    @else
        <link href="{{CSS}}bootstrap.min.css" rel="stylesheet">
        <link href="{{CSS}}{{\App\Settings::get_default_theme()}}-theme.css" rel="stylesheet">
    @endif
    <link rel="stylesheet" href="{{CSS}}bootstrap-datepicker.min.css">
    <link href="{{CSS}}sweetalert.css" rel="stylesheet" type="text/css">

    <!-- Morris Charts CSS -->
    <link href="{{CSS}}plugins/morris.css" rel="stylesheet">
    <!-- Proxima Nova Fonts CSS -->
    <link href="{{CSS}}proximanova.css" rel="stylesheet">
    <!-- Custom CSS -->
    {{--<link href="{{CSS}}{{getSetting('current_theme', 'site_settings')}}-theme.css" rel="stylesheet">--}}
<!-- Custom Fonts -->
    <link href="{{CSS}}custom-fonts.css" rel="stylesheet" type="text/css">
    <link href="{{CSS}}materialdesignicons.css" rel="stylesheet" type="text/css">
    {{-- <link href="{{FONTAWSOME}}font-awesome.min.css" rel="stylesheet" type="text/css"> --}}
    <link rel="stylesheet" href="{{CSS}}font-awesome.css"/>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="{{JS}}html5shiv.js"></script>

    <script src="{{JS}}respond.min.js"></script>
    <![endif]-->

</head>

<body ng-app="academia">
@yield('custom_div')
<?php
$class = '';
if (!isset($right_bar))
    $class = 'no-right-sidebar';

?>
<?php
$settings = \App\Settings::where('key', 'module')->first(['settings_data']);
$settings = json_decode($settings->settings_data);
$total = 0;
if ($settings->push_notifications->value == 1) {
    $total += \App\user_notifications::get_new_count();
}
$available_types = App\Settings::getMassages();
$current_user =     Auth::user();
if($settings->messaging->value == 1 && in_array($current_user->role_id,$available_types)){$total+=Auth::user()->newThreadsCount();}
?>
<?php
$user = getUserRecord();
$role = getRoleData($user->role_id);
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
        <?php //$newUsers = (new App\User())->getLatestUsers();
        ?>
        <ul class="nav navbar-right top-nav">
            <li><a href="{{URL_HEADER_SEARCH_LINK}}">
                    <i class="fa fa-search"></i> {{ getPhrase('search_student') }}  </a>

            </li>

            <li class="dropdown profile-menu">
                <div class="dropdown-toggle top-profile-menu" data-toggle="dropdown">
                    @if(Auth::check())
                        @if($settings->push_notifications->value == 1 || ($settings->messaging->value == 1 && in_array($current_user->role_id,$available_types)))
                            <h6 class="badge badge-success">{{ $total }}</h6>
                        @endif
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

                    <?php $messages_module = FALSE;
                    $message_settings = getSetting('messaging_system_for','messaging_system');

                    if($message_settings=='all' || $message_settings=='admin_student_staff')
                    {
                    $messages_module = TRUE;
                    }
                    ?>

                    @if($settings->messaging->value == 1 && in_array($current_user->role_id,$available_types))
                        <li>
                            <a href="{{URL_MESSAGES}}"><span><i class="fa fa-comments-o" aria-hidden="true"><h6
                                                class="badge badge-success">{{$count = Auth::user()->newThreadsCount()}}</h6></i></span>
                                {{ getPhrase('messages')}} </a>
                        </li>
                    @endif
                    @if($settings->push_notifications->value == 1)
                        <li>

                            <a href="{{URL_NOTIFICATIONS}}"><i class="fa fa-bell-o" aria-hidden="true"></i>
                                <h6 class="badge badge-success">{{ \App\user_notifications::get_new_count() }}</h6>
                                {{ getPhrase('notifications') }} </a>

                        </li>
                    @endif

                    <li>
                        <a href="{{URL_LANGUAGES_USER_LIST}}">
                            <sapn><i class="fa fa-language" aria-hidden="true"></i>
                                {{ getPhrase('change_user_language') }}</sapn>
                        </a>
                    </li>

                    <li>

                        <a href="{{URL_FEEDBACK_SEND}}">

                            <sapn><i class="fa fa-commenting-o" aria-hidden="true"></i> {{ getPhrase('feedback') }}
                            </sapn>

                        </a>

                    </li>

                    @if(Session::has('restore_slug') && Session::get('restore_slug') !=null )
                        <li> 
                        <a href="{{URL_USERS_SWITCH_ADMIN.Auth::user()->slug}}">

                            <sapn><i class="fa fa-sign-out" aria-hidden="true"></i> {{ getPhrase('back_to_admin') }}</sapn>

                        </a> 
                        </li>
                    @endif
                    <li>
                        <a href="{{URL_USERS_LOGOUT}}">
                            <sapn><i class="fa fa-sign-out" aria-hidden="true"></i> {{ getPhrase('logout') }}</sapn>
                        </a>
                    </li>
                </ul>
            </li>

        </ul>

    </nav>
    <aside class="left-sidebar">
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav side-nav">

                <li {{ isActive($active_class, 'dashboard') }}>
                    <a href="{{PREFIX}}">
                        <i class="icon-home"></i> {{ getPhrase('dashboard') }}
                    </a>
                </li>
                    @if($settings->push_notifications->value == 1)
                        <li {{ isActive($active_class, 'notifications') }} >

                            <a href="{{URL_NOTIFICATIONS}}"><i class="fa fa-bell-o" aria-hidden="true"></i>

                                {{ getPhrase('notifications') }} </a>


                        </li>
                    @endif
                    @if($settings->attendance_and_departure->value == 1 && $role == 'student_guide')
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
                        <li {{ isActive($active_class, 'search_student') }} >
                            <a href="{{ URL_HEADER_SEARCH_LINK }}">
                                <i class="fa fa-street-view"></i>{{ getPhrase('search_student') }}</a></li>


            </ul>
        </div>
    </aside>
    @if(isset($right_bar))

        <aside class="right-sidebar" id="rightSidebar">
            <button class="sidebat-toggle" id="sidebarToggle" href='javascript:'><i class="mdi mdi-menu"></i></button>
            <div class="panel panel-right-sidebar">
                <?php $data = '';
                if(isset($right_bar_data))
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


@yield('footer_scripts')
@include('layouts.scripts.popup')
@include('errors.formMessages')

@yield('custom_div_end')
{!!getSetting('google_analytics', 'seo_settings')!!}
</body>

</html>
