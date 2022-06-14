@extends(getLayout())
@section('header_scripts')
    <link href="{{CSS}}ajax-datatables.css" rel="stylesheet">
@stop
@section('content')


    <div id="page-wrapper">
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
                        <li>{{ $title }}</li>
                    </ol>
                </div>
            </div>

            <!-- /.row -->
            <div class="panel panel-custom" ng-controller="homeworkCtrl">
                <div class="panel-heading">

                    <div class="pull-right messages-buttons">
                            <a href="{{URL_HOMEWORK_ADD}}" class="btn  btn-primary button" >{{ getPhrase('Add_Homework')}}</a>
                    </div>
                    <h1>{{ $title }}</h1>
                </div>
                <div class="panel-body packages">
                    <div>
                        <div class="row">
                            @if(isset($teachers) && !is_teacher())
                            <fieldset class="form-group col-md-4">
                                <label for="">{{getPhrase('teachers')}}</label>
                                <span class="text-red">*</span>
                                <select name="teacherSlug" class="form-control" required="required"
                                        ng-model="current_teacher" ng-change="getCourses()">
                                    <option ng-repeat="teacher in {{$teachers}}"
                                            value="@{{teacher.slug}}">@{{ teacher.name }}</option>
                                </select>
                            </fieldset>
                                @endif
                                <div class="col-md-4">
                                    <fieldset class="form-group">
                                        <label for="">{{getPhrase('academic_year')}}</label>
                                        <span class="text-red">*</span>
                                        <select name="academic_id" class="form-control"  required="required" ng-model="current_year_sc" ng-change="get_sems()">
                                            <option  ng-repeat="year in academic_years_sc" value="@{{ year.id }}">@{{ year.academic_year_title }}</option>
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-md-4">
                                    <fieldset class="form-group">
                                        <label for="">{{getPhrase('Semester')}}</label>
                                        <span class="text-red">*</span>
                                        <select name="current_semister" class="form-control" required="required" ng-model="current_sem_sc" ng-change="getCourses()">
                                            <option ng-repeat="sem in academic_sems_sc" id="@{{ sem.sem_num }}" value="@{{ sem.sem_num }}"> @{{ sem.title  }}</option>
                                        </select>
                                    </fieldset>
                                </div>
                        </div>

                        <div class="row">
                                    <fieldset class="form-group col-md-4">
                                        <label for="">{{getPhrase('branch')}}</label>
                                        <span class="text-red">*</span>
                                        <select name="course_id" class="form-control" required="required"
                                                ng-model="current_course_sc" ng-change="getClasses()">
                                            <option ng-repeat="course in academic_courses_sc"
                                                    value="@{{ course.id }}">@{{ course.course_title }}</option>
                                        </select>
                                    </fieldset>
                                    <fieldset class="form-group col-md-4">
                                        <label for="">{{getPhrase('class')}}</label>
                                        <span class="text-red">*</span>
                                        <select name="class_id" class="form-control" required="required"
                                                ng-model="current_class_sc" ng-change="getSubjects()">
                                            <option ng-repeat="aclass in academic_classes_sc"
                                                    value="@{{ aclass.id }}">@{{ aclass.course_title }}</option>
                                        </select>
                                    </fieldset>
                                    <fieldset class="form-group col-md-4">
                                        <label for="">{{getPhrase('subject')}}</label>
                                        <span class="text-red">*</span>
                                        <select name="course_subject_id" class="form-control" required="required"
                                                ng-model="current_subject_sc">
                                            <option ng-repeat="subject in academic_subjects_sc"
                                                    value="@{{ subject.subject_id }}">@{{ subject.subject_title }}</option>
                                        </select>
                                    </fieldset>
                        </div>
                        <div class="row">
                            <center>
                                <a class="btn btn-primary" ng-click="toTable()" ng-disabled="current_subject_sc == null">{{getPhrase('get_details')}}</a>
                            </center>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </div>
@endsection


@section('footer_scripts')

    @include('common.datatables', array('route'=> URL_QUESTIONBANK_GETLIST, 'route_as_url' => 'TRUE'))
    @include('common.deletescript', array('route'=> URL_QUESTIONBANK_DELETE))
    @include('home_work.scripts.script')

@stop
