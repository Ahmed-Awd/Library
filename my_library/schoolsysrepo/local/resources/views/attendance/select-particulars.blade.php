@extends($layout)
@section('header_scripts')
    <link href="{{CSS}}plugins/datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet">

@stop

@section('content')
<div id="page-wrapper" ng-controller="academicAttendance">
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li>
                            <a href="{{PREFIX}}">
                                <i class="mdi mdi-home">
                                </i>
                            </a>
                        </li>
                        @if($role_name!='staff' and $role_name !='educational_supervisor')
                            <li>
                                <a href="{{URL_ACADEMICOPERATIONS_DASHBOARD}}">
                                    {{getPhrase('academic_operations')}}
                                </a>
                            </li>
                        @elseif($role_name =='educational_supervisor' or $role_name == 'staff')
                            <li>{{getPhrase('student_attendance_of_teacher')}}</li>
                            <li>
                                <a href="">
                                    {{$slugData->name}}
                                </a>
                            </li>
                        @endif
                    </ol>
                </div>
            </div>
            <!-- /.row -->
            <div class="panel panel-custom">
                <div class="panel-heading">
                    <div class="pull-right">
                        @if ($role == 'educational_supervisor')
                            <a class="btn btn-primary"
                               href="{{url('supervisor/staff/teacher-student-attendance')}}">{{getPhrase('all')}}</a>
                        @endif
                    </div>
                    <h1>{{ getPhrase('attendance') }}</h1>
                </div>

                <div class="panel-body instruction">


                    <div class="row">
                        <!-- <div class="col-md-6">
                            <h3>{{ getPhrase('general_instructions') }}</h3>
                            <ul class="guide">
                                <li>
                                <span class="answer">
                                    <i class="mdi mdi-check">
                                    </i>
                                </span>
                                    {{getPhrase('present')}}
                                </li>
                                <li>
                                <span class="notanswer">
                                    <i class="mdi mdi-close">
                                    </i>
                                </span>
                                    {{getPhrase('absent')}}

                                    {{getPhrase('leave')}}
                                </li>

                            </ul>
                        </div> -->
                        @if($role_name =='staff' || $role_name =='owner' || $role_name =='admin'|| $role_name == 'student_guide' )
                            {!! Form::open(array('url' => URL_STUDENT_ATTENDENCE_ADD.$userdata->slug, 'method' => 'POST')) !!}
                        @elseif($role_name=='educational_supervisor' )
                            {!! Form::open(array('url' => 'supervisor/staff/students-attendance/'.$slugData->slug, 'method' => 'POST')) !!}
                        @endif


                        @if($role_name!='staff' and $role_name!='educational_supervisor' and $role_name!='owner' and $role_name != 'student_guide')

                            <div class="col-md-6">

                                @include('common.year-selection-view')

                            </div>

                        @else
                        @if(isset($teachers))
                        <div class="col-md-8">
                            <fieldset class="form-group col-md-8">
                                <label for="">{{getPhrase('teachers')}}</label>
                                <span class="text-red">*</span>
                                <select name="teacherSlug" class="form-control" required="required"
                                        ng-model="current_teacher" ng-change="getCourses()">
                                    <option ng-repeat="teacher in {{$teachers}}"
                                            value="@{{teacher.slug}}">@{{ teacher.name }}</option>
                                </select>
                            </fieldset>
                          </div>
                            @endif
                            <div class="col-md-8">
                                <fieldset class="form-group col-md-8">
                                    <label for="">{{getPhrase('academic_year')}}</label>
                                    <span class="text-red">*</span>
                                    <select name="year_id" class="form-control"  required="required" ng-model="current_year_sc" ng-change="get_sems()">
                                        <option  ng-repeat="year in academic_years_sc" value="@{{ year.id }}">@{{ year.academic_year_title }}</option>
                                    </select>
                                </fieldset>
                            </div>
                         <div class="col-md-8">
                                <fieldset class="form-group col-md-8">
                                    <label for="">{{getPhrase('Semester')}}</label>
                                    <span class="text-red">*</span>
                                    <select name="sem_id" class="form-control" required="required" ng-model="current_sem_sc" ng-change="getCourses()">
                                        <option ng-repeat="sem in academic_sems_sc" id="@{{ sem.sem_num }}" value="@{{ sem.sem_num }}"> @{{ sem.title  }}</option>
                                    </select>
                                </fieldset>
                            </div>
                            <div class="col-md-8">
                                <fieldset class="form-group col-md-8">
                                    <label for="">{{getPhrase('branch')}}</label>
                                    <span class="text-red">*</span>
                                    <select name="course_id" class="form-control" required="required"
                                            ng-model="current_course_sc" ng-change="getClasses()">
                                        <option ng-repeat="course in academic_courses_sc"
                                                value="@{{ course.id }}">@{{ course.course_title }}</option>
                                    </select>
                                </fieldset>
                                <fieldset class="form-group col-md-8">
                                    <label for="">{{getPhrase('class')}}</label>
                                    <span class="text-red">*</span>
                                    <select name="class_id" class="form-control" required="required"
                                            ng-model="current_class_sc" ng-change="getSubjects()">
                                        <option ng-repeat="aclass in academic_classes_sc"
                                                value="@{{ aclass.id }}">@{{ aclass.course_title }}</option>
                                    </select>
                                </fieldset>
                                <fieldset class="form-group col-md-8">
                                    <label for="">{{getPhrase('subject')}}</label>
                                    <span class="text-red">*</span>
                                    <select name="course_subject_id" class="form-control" required="required"
                                            ng-model="current_subject_sc" ng-change="getSubjectsTimetable()">
                                        <option ng-repeat="subject in academic_subjects_sc"
                                                value="@{{ subject.id }}">@{{ subject.subject_title }}</option>
                                    </select>
                                </fieldset>
                            </div>

                            <div class="col-md-8">


                                <?php
                                $number_of_class = getPeriod();
                                ?>

                                <fieldset class="form-group col-md-8">
                                    {{ Form::label('class', getphrase('Session_number')) }}
                                    <span class="text-red">*</span>
                                    {{Form::select('total_class',$number_of_class,  null,
                                                        ['class'=>'form-control' ,'ng-model'=>'time_subjects_sc'])}}
                                </fieldset>



                                <fieldset class="form-group col-md-8">

                                    {{ Form::label('attendance_date', getphrase('attendance_date')) }}
                                    <div class="input-group date" data-date="{{date('Y/m/d')}}"
                                         data-provide="datepicker" data-date-format="{{getDateFormat()}}">
                                        {{ Form::text('attendance_date', $value = date('Y/m/d') , $attributes = array('class'=>'form-control','ng-model' => 'attendance_date','ng-change' => 'test_change()', 'placeholder' => '2015/7/17', 'id'=>'dp')) }}
                                        <div class="input-group-addon">
                                            <span class="mdi mdi-calendar"></span>
                                        </div>
                                    </div>
                                </fieldset>

                            </div>

                        @endif

                        <hr>


                        <div class="text-center col-md-6">
                            <button type="submit" class="btn button btn-lg btn-primary">
                                {{getPhrase('get_details')}}
                            </button>
                        </div>


                        {!! Form::close() !!}

                        </hr>
                    </div>
                </div>
            </div>
        </div>
</div>
        @stop



        @section('footer_scripts')

            <script src="{{JS}}moment.min.js"></script>

            <script src="{{JS}}plugins/datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
            <script>

                $(function () {
                    $('#dp').datetimepicker({
                        format: 'YYYY-MM-DD',
                        maxDate: 'now'

                    });
                });
            </script>
    @include('attendance.scripts.js-scripts')

@stop
