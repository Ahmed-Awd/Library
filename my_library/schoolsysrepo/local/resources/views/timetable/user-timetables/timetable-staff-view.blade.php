@extends($layout)

@section('header_scripts')
    <link href="{{CSS}}animate.css" rel="stylesheet">

@stop
@section('custom_div')
    <?php $data = null;
    if (isset($allocated_periods)) {
        $data = $allocated_periods;
    }
    $roleId = Auth::user()->role_id;
    ?>
    <div ng-controller="TimetableController" ng-init="ingAngData({{$data}})">
        @stop
        @section('content')
            <div id="page-wrapper">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <ol class="breadcrumb">
                                <li>
                                    <a href="{{PREFIX}}">
                                        <i class="mdi mdi-home">
                                        </i>
                                    </a>
                                </li>
                                <li>

                                    {{$title}}

                                </li>
                                @if($role!='staff')
                                    <li>
                                        <a href="{{URL_TIMETABLE_DASHBOARD}}">
                                            {{getPhrase('timetable_dashboard')}}
                                        </a>
                                    </li>
                                @endif
                                @if(checkRole(getUserGrade(2)))
                                    <li><a href="{{URL_USERS_DASHBOARD}}">{{ getPhrase('users_dashboard') }}</a></li>


                                    <li><a href="{{URL_USERS."staff"}}">{{ getPhrase('staff_users') }}</a></li>
                                @endif
                                <li>
                                    <a href="@if($roleId!= 9){{URL_STAFF_DETAILS.$record->slug}}@endif">{{ $record->name }}</a>
                                </li>
                            </ol>
                        </div>
                    </div>


                    {!! Form::open(array('url' => URL_UPDATE_TIMETABLE, 'method' => 'POST', 'name'=>'idCards ', 'novalidate'=>'')) !!}

                    <div class="panel panel-custom">
                        <div class="panel-heading">
                            <h1>
                                {{getPhrase('timetable_for').' '.$user->name}}
                                <div class="pull-right">
                                    <a target="_blank" href="{{URL_TIMETABLE_STAFF_STUDENT_PRINT.$user->slug}}"
                                       class="btn btn-primary">
                                        {{getPhrase('print')}}</a>
                                    @if ($role == 'educational_supervisor')
                                        <a href="{{url('supervisor/staff/teachers-timetable')}}"
                                           class="btn btn-primary">
                                            {{getPhrase('all')}}</a>
                                    @endif
                                </div>

                            </h1>
                        </div>
                        <div class="panel-body">

                            <a href="javascript:void(0);"
                               class="pull-right btn btn-link"
                               ng-click="toggleCalender()"
                               ng-show="showCalender"
                            >
                                Back </a>


                            @include('timetable.user-timetables.calender-view')

                            <br>
                        </div>
                    </div>
                    {!! Form::close() !!}


                    </hr>
                </div>
            </div>
    </div>
    </div>

    @stop

    @section('footer_scripts')

    @include('timetable.user-timetables.scripts.js-scripts')
    @include('common.alertify')
    @stop

    @section('custom_div_end')
    </div>
@stop
