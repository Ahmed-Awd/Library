<html lang="en" dir="{{ (App\Language::isDefaultLanuageRtl()) ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{getSetting('meta_description', 'seo_settings')}}">
    <meta name="keywords" content="{{getSetting('meta_keywords', 'seo_settings')}}">

    <link rel="icon" href="{{IMAGE_PATH_SETTINGS.getSetting('site_favicon', 'site_settings')}}" type="image/x-icon" />
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

    <!-- Morris Charts CSS -->
    <link href="{{CSS}}plugins/morris.css" rel="stylesheet">
    <!-- Proxima Nova Fonts CSS -->
    <link href="{{CSS}}proximanova.css" rel="stylesheet">
    <!-- Custom CSS -->
    <!-- Custom Fonts -->
    <link href="{{CSS}}custom-fonts.css" rel="stylesheet" type="text/css">
    <link href="{{CSS}}materialdesignicons.css" rel="stylesheet" type="text/css">
    {{-- <link href="{{FONTAWSOME}}font-awesome.min.css" rel="stylesheet" type="text/css"> --}}
    <link rel="stylesheet" href="{{CSS}}font-awesome.css"/>

</head>

<body ng-app="academia">
@yield('custom_div')
<?php
$class = '';
if(!isset($right_bar))
    $class = 'no-right-sidebar';

?>
<?php
$settings = \App\Settings::where('key', 'module')->first(['settings_data']);
$settings = json_decode($settings->settings_data);
$total   = 0;
if($settings->push_notifications->value == 1){$total+=\App\user_notifications::get_new_count();}
$available_types = App\Settings::getMassages();
$current_user =     Auth::user();
if($settings->messaging->value == 1 && in_array($current_user->role_id,$available_types)){$total+=Auth::user()->newThreadsCount();}
?>
<div id="wrapper" class="{{$class}}">
    <!-- Navigation -->
    <nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
            <a class="navbar-brand" href="{{PREFIX}}"><img src="{{IMAGE_PATH_SETTINGS.getSetting('site_logo', 'site_settings')}}" alt="{{getSetting('site_title','site_settings')}}"></a>
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

                    <div class="profile-img"> <img src="{{ getProfilePath(Auth::user()->image, 'thumb') }}" alt=""> </div>
                    <div class="mdi mdi-menu-down"></div>
                </div>
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{URL_USERS_EDIT}}{{Auth::user()->slug}}">

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
                        <a href="{{URL_USERS_CHANGE_PASSWORD}}{{Auth::user()->slug}}">
                            <sapn><i class="fa fa-key" aria-hidden="true"></i> {{ getPhrase('change_password') }}</sapn>
                        </a>
                    </li>

                    <li>

                        <a href="{{URL_FEEDBACK_SEND}}">

                            <sapn><i class="fa fa-commenting-o" aria-hidden="true"></i> {{ getPhrase('feedback') }}</sapn>

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
        <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
        <!-- /.navbar-collapse -->
    </nav>
    <aside class="left-sidebar">			<div class="collapse navbar-collapse navbar-ex1-collapse">				<ul class="nav navbar-nav side-nav">					<li {{ isActive($active_class, 'dashboard') }}>
                    <a href="{{PREFIX}}">
                        <i class="fa fa-home" aria-hidden="true"></i>
                        {{ getPhrase('dashboard') }}
                    </a>
                </li>
                @if(Module_state('library_Management'))
                @if(checkRole(getUserGrade(8)))
                    <li {{ isActive($active_class, 'library') }} >

                        <a data-toggle="collapse" data-target="#library" ><i class="fa fa-book" ></i>
                            {{ getPhrase('library_masters') }} </a>

                        <ul id="library" class="collapse sidemenu-dropdown">

                            <li><a href="{{URL_LIBRARY_ASSETS}}"> <i class="fa fa-database"></i>{{ getPhrase('asset_types') }}</a></li>

                            <li><a href="{{URL_LIBRARY_MASTERS}}"> <i class="fa fa-book"></i> {{ getPhrase('master_data')}}</a></li>
                            <li><a href="{{URL_PUBLISHERS}}"> <i class="fa fa-paint-brush"></i>{{ getPhrase('publishers') }}</a></li>
                            <li><a href="{{URL_AUTHORS}}"> <i class="fa fa-mortar-board"></i>{{ getPhrase('authors') }}</a></li>

                        </ul>
                    </li>
                @endif
                @endif
                <li {{ isActive($active_class, 'students') }} >
                    <a href="{{URL_LIBRARY_USERS}}student"> <i class="fa fa-users"></i>{{ getPhrase('students') }}</a>
                </li>
                <li {{ isActive($active_class, 'staff') }} >
                    <a href="{{URL_LIBRARY_USERS}}staff"><i class="fa fa-user-o" aria-hidden="true"></i>

                        </i>{{ getPhrase('staff') }}</a>

                </li>

                <li {{ isActive($active_class, 'student-return') }} >
                    <a href="{{URL_LIBRARY_LIBRARYDASHBOARD_BOOKS}}"><i class="fa fa-user"></i></i>
                        {{ getPhrase('student_book_return') }}</a>
                </li>

                <li {{ isActive($active_class, 'staff-return') }} >
                    <a href="{{URL_LIBRARY_LIBRARYDASHBOARD_BOOKS_STAFF}}"><i class="fa fa-user-circle"></i>
                        {{ getPhrase('staff_book_return') }}</a>
                </li>

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

@include('errors.formMessages')
@include('layouts.scripts.popup')
@yield('custom_div_end')
{!!getSetting('google_analytics', 'seo_settings')!!}
</body>

</html>