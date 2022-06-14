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
                            <li>{{getPhrase('year_grades')}}</li>
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
                     
                    <h1>{{ getPhrase('year_grade') }}</h1>
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
                        @if($role_name =='staff' || $role_name =='owner' || $role_name =='admin'|| $role_name == 'student_guide' || $role_name =='educational_supervisor')
                            {!! Form::open(array('url' => 'student/year_grades/add/'.$userdata->slug, 'method' => 'GET')) !!}
                        
                        @endif


                        @if($role_name!='staff' and $role_name!='educational_supervisor' and $role_name!='owner' and $role_name != 'student_guide')

                            <div class="col-md-6">

                                @include('common.year-selection-view')

                            </div>

                        @else
                        @if(isset($teachers) && request()-> get('status') !='hide')
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


                        <input type="hidden" name="status" value="{{request()-> get('status') == 'hide' ? 1 :0}}">

                         <input type="hidden" name="teacherSlug" value="{{$slugData-> slug}}">

 
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
