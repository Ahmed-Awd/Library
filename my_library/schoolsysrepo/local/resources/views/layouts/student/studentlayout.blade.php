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
    <!-- edit here -->
    @if(Session::has('lang_dir') && Session::get('lang_dir') == 0 )
        <link href="{{CSS}}bootstrap_en.min.css" rel="stylesheet">
        <link href="{{CSS}}{{\App\Settings::get_default_theme()}}-theme-en.css" rel="stylesheet">
    @else
        <link href="{{CSS}}bootstrap.min.css" rel="stylesheet">
        <link href="{{CSS}}{{\App\Settings::get_default_theme()}}-theme.css" rel="stylesheet">
    @endif
    @yield('header_scripts')


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

$block_class = '';

if (isset($block_navigation))

    $block_class = 'non-clickable';

?>
<?php
$settings = \App\Settings::where('key', 'module')->first(['settings_data']);
$settings = json_decode($settings->settings_data);
$total   = 0;
$available_types = App\Settings::getMassages();
$current_user =     Auth::user();
if($settings->messaging->value == 1 && in_array($current_user->role_id,$available_types)){$total+=Auth::user()->newThreadsCount();}
if($settings->push_notifications->value == 1){$total+=\App\user_notifications::get_new_count();}
?>
<div id="wrapper" class="{{$class}}">

    <!-- Navigation -->

    <nav class="navbar navbar-custom navbar-fixed-top {{$block_class}}" role="navigation">

        <?php
        if(isset($block_navigation)) { ?>
        <div class="alert alert-danger alert-norefresh">
            <strong>{{getPhrase('warning')}} !</strong> {{getPhrase('do_not_press_back_or_refresh_button')}}
        </div>

    <?php } ?>

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

        <ul class="nav navbar-right top-nav">


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

                        <a href="{{URL_BOOKMARKS.Auth::user()->slug}}">

                            <sapn><i class="fa fa-bookmark" aria-hidden="true"></i> {{ getPhrase('my_bookmarks') }}
                            </sapn>

                        </a>

                    </li>

                    <li>

                        <a href="{{URL_USERS_EDIT.Auth::user()->slug}}">

                            <sapn><i class="fa fa-user" aria-hidden="true"></i> {{ getPhrase('my_profile') }}</sapn>

                        </a>

                    </li>

                    @if($settings->push_notifications->value == 1)
                        <li>

                            <a href="{{URL_NOTIFICATIONS}}"><i class="fa fa-bell-o" aria-hidden="true"></i>
                                <h6 class="badge badge-success">{{ \App\user_notifications::get_new_count() }}</h6>
                                {{ getPhrase('notifications') }} </a>

                        </li>
                    @endif

                    @if($settings->messaging->value == 1 && in_array($current_user->role_id,$available_types))
                        <li>
                            <a href="{{URL_MESSAGES}}"><span><i class="fa fa-comments-o" aria-hidden="true"><h6
                                                class="badge badge-success">{{$count = Auth::user()->newThreadsCount()}}</h6></i></span>
                                {{ getPhrase('messages')}} </a>
                        </li>
                    @endif

                    <li>

                        <a href="{{URL_USERS_CHANGE_PASSWORD.Auth::user()->slug}}">

                            <sapn><i class="fa fa-key" aria-hidden="true"></i> {{ getPhrase('change_password') }}</sapn>

                        </a>

                    </li>
                <!--
                    <li>

                        <a href="{{URL_USERS_SETTINGS.Auth::user()->slug}}">

                            <sapn><i class="fa fa-cog" aria-hidden="true"></i> {{ getPhrase('settings') }}</sapn>

                        </a>

                    </li>
                     -->


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
                    @if(Session::has('restore_teacher') && Session::get('restore_teacher') !=null )
                        <li>
                        <a href="{{URL_USERS_SWITCH_TEACHER.Auth::user()->slug}}">

                            <sapn><i class="fa fa-sign-out" aria-hidden="true"></i> {{ getPhrase('back_to_teacher') }}</sapn>

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

        <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->

        <!-- /.navbar-collapse -->

    </nav>

    <aside class="left-sidebar">

        <div class="collapse navbar-collapse navbar-ex1-collapse">

            <ul class="nav navbar-nav side-nav {{$block_class}}">

                <li {{ isActive($active_class, 'dashboard') }}>

                    <a href="{{PREFIX}}">

                        <i class="icon-home"></i> {{ getPhrase('dashboard') }}

                    </a>

                </li>


                @if(Module_state('exams'))
                <li {{ isActive($active_class, 'exams') }} >


                    <a data-toggle="collapse" data-target="#exams" href="{{URL_STUDENT_EXAM_CATEGORIES}}"><i class="fa fa-pencil-square-o"
                                                                      aria-hidden="true"></i>

                        {{ getPhrase('exams') }} </a>


                    <!-- <ul id="exams" class="collapse sidemenu-dropdown">

                        <li><a href="{{URL_STUDENT_EXAM_CATEGORIES}}"><i
                                        class="fa fa-random"></i>{{ getPhrase('exam_categories') }}</a></li>

                        <li><a href="{{URL_STUDENT_EXAM_SERIES_LIST}}"><i
                                        class="fa fa-list-ol"></i>{{ getPhrase('exam_series') }}</a></li>

                       <li><a href="{{URL_STUDENT_EXAM_OFFLINE_EXAMS}}"> <i class="fa fa-sort-amount-asc"
                                                                               aria-hidden="true"></i> {{ getPhrase('offline_exams_categories')}}
                            </a></li>
                    </ul> -->

                    <li {{ isActive($active_class, 'homeworks') }} >
                        <a href="{{URL_HOMEWORK_STUDENT}}">
                            <i class="fa fa-briefcase"></i>{{ getPhrase('Homeworks') }}</a></li>

                </li>
                @endif
                @if(Module_state('exams'))
                <li {{ isActive($active_class, 'analysis') }} >


                    <a data-toggle="collapse" data-target="#analysis" href="{{URL_USER_DETAILS.Auth::user()->slug}}">
                        <i class="fa fa-bar-chart" aria-hidden="true"></i>

                        {{ getPhrase('analysis') }} </a>


                    <ul id="analysis" class="collapse sidemenu-dropdown">

                        <li><a href="{{URL_STUDENT_ANALYSIS_SUBJECT.Auth::user()->slug }}"> <i
                                        class="fa fa-key"></i>{{ getPhrase('by_subjcet') }}</a></li>

                        <li><a href="{{URL_STUDENT_ANALYSIS_BY_EXAM.Auth::user()->slug }}"> <i
                                        class="fa fa-suitcase"></i>{{ getPhrase('by_exam') }}</a></li>

                        <li><a href="{{URL_STUDENT_EXAM_ATTEMPTS.Auth::user()->slug }}"> <i
                                        class="fa fa-history"></i>{{ getPhrase('history') }} </a></li>

                        <li><a href="{{URL_STUDENT_RESULTS.Auth::user()->slug }}"> <i
                                        class="fa fa-percent"></i>{{ getPhrase('scheduled_exam_marks') }} </a></li>

                       {{-- <li><a href="{{URL_STUDENT_MARKS_REPORT}}"> <i class="fa fa-line-chart"
                                                                       aria-hidden="true"></i> {{ getPhrase('class_marks_report')}}
                            </a></li>--}}
                        {{-- <li><a href="{{URL_STUDENT_ATTENDENCE_REPORT.'/'.Auth::user()->slug }}"> <i class="fa fa-calendar-check-o"></i>{{ getPhrase('attendance') }} </a></li> --}}
                        @if(Module_state('daily_school_schedule'))
                        <li><a target="_blank" href="{{URL_TIMETABLE_STAFF_STUDENT_PRINT.Auth::user()->slug }}"> <i
                                        class="fa fa-calendar"></i>{{ getPhrase('timetable') }} </a></li>
                        @endif
                    </ul>


                </li>
                @endif

                @if(Module_state('management_of_educational_content'))
                <li {{ isActive($active_class, 'lms') }} >

                    <a  href="{{ URL_STUDENT_LMS_CATEGORIES }}"><i class="fa fa-leanpub" aria-hidden="true"></i>

                        {{ getPhrase('lms') }}</a>


                    <!-- <ul id="lms" class="collapse sidemenu-dropdown">

                        <li><a href="{{ URL_STUDENT_LMS_CATEGORIES }}"> <i
                                        class="fa fa-random"></i>{{ getPhrase('lms_categories') }}</a></li>


                        <li><a href="{{ URL_STUDENT_LMS_SERIES }}"> <i
                                        class="fa fa-list-ol"></i>{{ getPhrase('lms_series') }}</a></li>

                    </ul> -->

                </li>
                @endif
                @if(Module_state('library_Management'))
                <li {{ isActive($active_class, 'library') }} >
                    <a href="{{URL_USER_LIBRARY_DETAILS.Auth::user()->slug}}"><i class="fa fa-book"
                                                                                 aria-hidden="true"></i>

                        {{ getPhrase('library') }} </a>

                </li>
                @endif

                @if($settings->messaging->value == 1)

                    <li {{ isActive($active_class, 'messages') }} >


                        <a href="{{URL_MESSAGES}}"><span><i class="fa fa-comments-o fa-2x" aria-hidden="true"><h5
                                            class="badge badge-success">{{$count = Auth::user()->newThreadsCount()}}</h5></i></span>
                            {{ getPhrase('messages')}} </a>


                    </li>

                @endif


                <li {{ isActive($active_class, 'subscriptions') }} >


                    <a href="{{URL_PAYMENTS_LIST.Auth::user()->slug}}"><i class="fa fa-credit-card"
                                                                          aria-hidden="true"></i>

                        {{ getPhrase('subscriptions') }} </a>


                </li>

                @if($settings->push_notifications->value == 1)
                    <li {{ isActive($active_class, 'notifications') }} >

                        <a href="{{URL_NOTIFICATIONS}}"><i class="fa fa-bell-o" aria-hidden="true"></i>

                            {{ getPhrase('notifications') }} </a>


                    </li>
                @endif


            </ul>

        </div>

    </aside>

    @if(isset($right_bar))



        <aside class="right-sidebar" id="rightSidebar">

            <button class="sidebat-toggle" id="sidebarToggle" href='javascript:'><i class="mdi mdi-menu"></i></button>

            <?php $right_bar_class_value = '';

            if (isset($right_bar_class))

                $right_bar_class_value = $right_bar_class;

            ?>

            <div class="panel panel-right-sidebar {{$right_bar_class_value}}">

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
@include('common.alertify')



@yield('footer_scripts')

@include('errors.formMessages')

@yield('custom_div_end')
{!!getSetting('google_analytics', 'seo_settings')!!}
</body>


</html>
