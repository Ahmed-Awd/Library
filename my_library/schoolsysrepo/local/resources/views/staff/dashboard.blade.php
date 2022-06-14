@extends($layout)
@section('header_scripts')
    {!! Charts::assets() !!}
@stop

@section('content')
    @if($role == 'educational_supervisor')
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">

                        <li><i class="fa fa-home"></i> {{ $title}}</li>
                    </ol>
                </div>
            </div>

            <div class="row">

                @foreach($allocated_staff as $record)
                <div class="col-md-3">
                    <div class="card {{getDashboardBoxColor()}} text-xs-center">
                        <div class="card-block">
                            <h4 class="card-title">
                                <i class="fa fa-archive"></i>
                            </h4>
                        <p class="card-text">{{$record->name}}</p>
                        </div>
                        <a class="card-footer text-muted"
                           href="{{url(URL_STAFF_DETAILS.$record->slug)}}">
                            {{ getPhrase('VIEW_STAFF')}}
                        </a>
                    </div>
                </div>
                @endforeach
                
            </div>
        </div>
        <!-- /#page-wrapper -->
    </div>
    @endif
    @if(/*$role == 'educational_supervisor' ||*/ $role == 'student_guide')
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <ol class="breadcrumb">

                            <li><i class="fa fa-home"></i> {{ $title}}</li>
                        </ol>
                    </div>
                </div>

                <div class="row">


                    <div class="col-md-4 ">
                        <div class="card card-yellow text-xs-center">
                            <div class="card-block">
                                <h4 class="card-title">
                                    <i class="fa fa-archive"></i>
                                </h4>
                                <p class="card-text">{{ getPhrase('specify_subjects_to_teachers') }}</p>
                            </div>
                            <a class="card-footer text-muted"
                               href="{{url('supervisor/staff/teachers-subjects')}}">
                                {{ getPhrase('view_all')}}
                            </a>
                        </div>
                    </div>

                    <div class="col-md-4 ">
                        <div class="card card-green text-xs-center">
                            <div class="card-block">
                                <h4 class="card-title">
                                    <i class="fa fa-paper-plane-o"></i>
                                </h4>
                                <p class="card-text">{{ getPhrase('staff_toic_plan') }}</p>
                            </div>
                            <a class="card-footer text-muted" href="{{url('supervisor/staff/staff-topic-plan')}}">
                                {{ getPhrase('view_all')}}
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 ">
                        <div class="card card-blue text-xs-center">
                            <div class="card-block">
                                <h4 class="card-title"><i class="fa fa-calendar-check-o"></i></h4>
                                <p class="card-text">{{ getPhrase('teacher_students_attendance') }}</p>
                            </div>
                            <a class="card-footer text-muted" href="{{url('supervisor/staff/teacher-student-attendance')}}">
                                {{ getPhrase('view_all')}}
                            </a>
                        </div>
                    </div>

                    @if(Module_state('daily_school_schedule'))
                    <div class="col-md-4">
                        <div class="card card-red text-xs-center">
                            <div class="card-block">
                                <h4 class="card-title"><i class="fa fa-calendar"></i></h4>
                                <p class="card-text">{{ getPhrase('teachers_timetables') }}</p>
                            </div>
                            <a class="card-footer text-muted" href="{{url('supervisor/staff/teachers-timetable')}}">
                                {{ getPhrase('view_all')}}
                            </a>
                        </div>
                    </div>
                    @endif
                    <div class="col-md-4 ">
                        <div class="card card-black text-xs-center">
                            <div class="card-block">
                                <h4 class="card-title"><i class="fa fa-users"></i></h4>
                                <p class="card-text">{{ getPhrase('teacher_students_marks') }}</p>
                            </div>
                            <a class="card-footer text-muted"
                               href="{{url('supervisor/staff/students-marks')}}">
                                {{ getPhrase('view_all')}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /#page-wrapper -->
        </div>
    @endif
    @if($role == 'staff')
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <ol class="breadcrumb">

                            <li><i class="fa fa-home"></i> {{ $title}}</li> - {{get_branch_name(Auth::user()->branch_id)}}
                        </ol>
                    </div>
                </div>

                <div class="row">


                    {{--<div class="col-md-4 ">--}}
                        {{--<div class="card card-yellow text-xs-center">--}}
                            {{--<div class="card-block">--}}
                                {{--<h4 class="card-title">--}}
                                    {{--<i class="fa fa-hand-o-up"></i>--}}
                                {{--</h4>--}}
                                {{--<p class="card-text">{{ getPhrase('subject_preferences')}}</p>--}}
                            {{--</div>--}}
                            {{--<a class="card-footer text-muted"--}}
                               {{--href="{{URL_STAFF_SUBJECT_PREFERENCES.Auth::user()->slug}}">--}}
                                {{--{{ getPhrase('view_all')}}--}}
                            {{--</a>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    <div class="col-md-4 ">
                        <div class="card card-green text-xs-center">
                            <div class="card-block">
                                <h4 class="card-title">
                                    <i class="fa fa-paper-plane-o"></i>
                                </h4>
                                <p class="card-text">{{ getPhrase('lesson_plans')}}</p>
                            </div>
                            <a class="card-footer text-muted" href="{{URL_LESSION_PLANS_DASHBOARD.Auth::user()->slug}}">
                                {{ getPhrase('view_all')}}
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 ">
                        <div class="card card-blue text-xs-center">
                            <div class="card-block">
                                <h4 class="card-title"><i class="fa fa-calendar-check-o"></i></h4>
                                <p class="card-text">{{ getPhrase('student_attendance') }}</p>
                            </div>
                            <a class="card-footer text-muted" href="{{URL_STUDENT_ATTENDENCE.Auth::user()->slug}}">
                                {{ getPhrase('view_all')}}
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-yellow text-xs-center">
                            <div class="card-block">
                                <h4 class="card-title"><i class="fa fa-briefcase"></i></h4>
                                <p class="card-text">{{ getPhrase('Homeworks')}}</p>
                            </div>
                            <a class="card-footer text-muted" href="{{URL_HOMEWORK_VIEW }}">
                                {{ getPhrase('view_all')}}
                            </a>
                        </div>
                    </div>
                    @if(Module_state('daily_school_schedule'))
                    <div class="col-md-4 ">
                        <div class="card card-red text-xs-center">
                            <div class="card-block">
                                <h4 class="card-title"><i class="fa fa-calendar"></i></h4>
                                <p class="card-text">{{ getPhrase('timetable')}}</p>
                            </div>
                            <a class="card-footer text-muted" href="{{URL_TIMETABLE_STAFF.Auth::user()->slug}}">
                                {{ getPhrase('view_all')}}
                            </a>
                        </div>
                    </div>
                    @endif

                    <div class="col-md-4 ">
                        <div class="card card-black text-xs-center">
                            <div class="card-block">
                                <h4 class="card-title"><i class="fa fa-users"></i></h4>
                                <p class="card-text">{{ getPhrase('students')}}</p>
                            </div>
                            <a class="card-footer text-muted"
                               href="{{URL_LESSION_PLANS_STUDENTLIST_DASHBOARD.Auth::user()->slug}}">
                                {{ getPhrase('view_all')}}
                            </a>
                        </div>
                    </div>

                    @if(Module_state('library_Management'))
                    <div class="col-md-4 ">
                        <div class="card card-yellow text-xs-center">
                            <div class="card-block">
                                <h4 class="card-title"><i class="fa fa-book"></i></h4>
                                <p class="card-text">{{ getPhrase('library_history')}}</p>
                            </div>
                            <a class="card-footer text-muted" href="{{URL_USER_LIBRARY_DETAILS.Auth::user()->slug}}">
                                {{ getPhrase('view_all')}}
                            </a>
                        </div>
                    </div>
                @endif
                <div class="col-md-4 ">
                    <div class="card card-green text-xs-center helper_step5">
                        <div class="card-block">
                            <h4 class="card-title">
                                <i class="fa fa-tasks" aria-hidden="true"></i>

                            </h4>
                            <p class="card-text">{{ getPhrase('subject_topics')}}</p>
                        </div>
                        <a class="card-footer text-muted"
                           href="{{URL_STAFF_TOPICS}}">
                            {{ getPhrase('view_all')}}
                        </a>
                    </div>
                </div>
                </div>

                <!-- /.container-fluid -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary dsPanel">
                            <div class="panel-heading"><i class="fa fa-book"></i> {{getPhrase('library_history')}}</div>
                            <div class="panel-body table-responsive">
                                <?php
                                $records = App\LibraryIssue::issueHistory('', 5);
                                ?>

                                <table class="table ">
                                    <thead>
                                    <tr>
                                        <th>
                                            <strong>{{getPhrase('sn')}}</strong></th>
                                        <th><strong>{{getPhrase('title')}}
                                            </strong></th>
                                        <th><strong>{{getPhrase('number')}}</th>
                                        <th><strong>{{getPhrase('issued')}}
                                            </strong></th>
                                        <th><strong>{{getPhrase('status')}}
                                            </strong></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $sno = 1;?>
                                    @if(count($records))
                                        @foreach($records as $record)
                                            <?php $class = 'label label-success';
                                            if ($record->issue_type == 'issue' || $record->issue_type == 'renewal')
                                                $class = 'label label-danger';
                                            if ($record->issue_type == 'lost')
                                                $class = 'label label-warning';
                                            ?>
                                            <tr>
                                                <td>{{$sno++}}</td>
                                                <td>{{$record->title}}</td>
                                                <td>{{$record->library_asset_no}}</td>
                                                <td>{{date('Y-m-d',strtotime($record->issued_on))}}</td>
                                                <td><span class='{{$class}}'>{{ ucfirst($record->issue_type)}}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td>{{getPhrase('no_data_available')}}</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="panel panel-primary dsPanel">
                            <div class="panel-heading"><i class="fa  fa-bell"></i> {{getPhrase("today_classes")}}</div>
                            <div class="panel-body table-responsive">
                                <?php
                                $records = App\Timetable::getDayClasses();
                                ?>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>
                                            <strong>{{getPhrase('sn')}}</strong></th>
                                        <th><strong>{{getPhrase('subject')}}
                                            </strong></th>
                                        <th><strong>{{getPhrase('class')}}</th>
                                        <th><strong>{{getPhrase('from')}}
                                            </strong></th>
                                        <th><strong>{{getPhrase('to')}}
                                            </strong></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $sno = 1;?>
                                    @if(count($records))
                                        @foreach($records as $record)

                                            <tr>
                                                <td>{{$sno++}}</td>
                                                <td>{{$record->subject_title}}</td>
                                                <td>{{$record->course_title}}</td>
                                                <td>{{$record->start_time}}</td>
                                                <td>{{ $record->end_time}}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td>{{getPhrase('no_data_available')}}</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- /#page-wrapper -->
        </div>
    @endif
@stop

@section('footer_scripts')


@stop
