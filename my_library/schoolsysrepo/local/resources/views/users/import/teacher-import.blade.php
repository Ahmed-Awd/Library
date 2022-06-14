@extends($layout)

@section('content')
    <div id="page-wrapper" ng-controller="TabController">
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
                        @if(checkRole(getUserGrade(2)))
                            <li><a href="{{URL_USERS."all"}}">{{ getPhrase('users')}}</a> </li>
                            <li class="active">{{isset($title) ? $title : ''}}</li>
                        @else
                            <li class="active">{{$title}}</li>
                        @endif
                    </ol>
                </div>
            </div>
        @include('errors.errors')
        <!-- /.row -->

            <div class="panel panel-custom col-lg-12">
                <div class="panel-heading">
                    @if(checkRole(getUserGrade(2)))
                        <div class="pull-right messages-buttons">

                            <a href="{{URL_USERS."all"}}" class="btn  btn-primary button" >{{ getPhrase('list')}}</a>

                        </div>
                    @endif
                    <h1>{{ $title }}  </h1>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="panel-body text-center">

                            <a href="{{DOWNLOAD_LINK_TEACHERS_IMPORT_EXCEL}}" class="btn btn-info">{{getPhrase('download_template')}}
                            </a>

                            <?php $button_name = getPhrase('upload'); ?>

                            {!! Form::open(array('url' => URL_TEACHER_IMPORT, 'method' => 'POST', 'novalidate'=>'','name'=>'formUsers ', 'files'=>'true')) !!}




                            <fieldset >
                                <label class="margintop30">Upload Excel</label>
                                {{-- {{ Form::label('excel', getphrase('upload_excel')) }} --}}


                                {!! Form::file('excel', array('class'=>'form-control','id'=>'excel_input', 'accept'=>'.xls,.xlsx', 'required'=>'true')) !!}



                            </fieldset>


                            <div class="buttons text-center">
                                <button class="btn btn-lg btn-primary button"
                                        ng-disabled='!formUsers.$valid'>{{ $button_name }}</button>
                            </div>


                            {!! Form::close() !!}
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h4>{{ getPhrase('information_helper_for_excel_data')}}</h4>
                        <div class="row">

                            <fieldset class="form-group col-md-3">
                                <label for="academic_branch">{{getPhrase('academic_branch')}}</label>
                                <span class="text-red">*</span>
                                <select name="branch_id" class="form-control" required="required" ng-model="current_branch">
                                    <option ng-repeat="branch in branchs" value="@{{ branch.id }}">@{{ branch.name }}</option>
                                </select>
                            </fieldset>

                            <fieldset class="form-group col-md-3">
                                <label for="">{{getPhrase('academic_year')}}</label>
                                <span class="text-red">*</span>
                                <select name="year" class="form-control"  required="required" ng-model="current_year_sc" ng-change="get_courses()">
                                    <option  ng-repeat="year in academic_years_sc" value="@{{ year.id }}">@{{ year.academic_year_title }}</option>
                                </select>
                            </fieldset>

                            <fieldset class="form-group col-md-3">
                                <label for="">{{getPhrase('Semester')}}</label>
                                <span class="text-red">*</span>
                                <select name="semesters" class="form-control" required="required" ng-model="current_sem_sc" ng-change="get_courses()">
                                    <option ng-repeat="sem in academic_sems_sc" id="@{{ sem.sem_num }}" value="@{{ sem.sem_num }}"> @{{ sem.title  }}</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="row">

                            <fieldset class="form-group col-md-3">
                                <label for="category_id">{{getPhrase('category')}}</label>
                                <span class="text-red">*</span>
                                <select name="category_id" class="form-control"  required="required" ng-model="current_category" ng-change="get_courses()">
                                    <option  ng-repeat="category in categorties" value="@{{ category.id }}">@{{ category.category_name }}</option>
                                </select>
                            </fieldset>

                            <fieldset class="form-group col-md-3">
                                <label for="">{{getPhrase('branch')}}</label>
                                <span class="text-red">*</span>
                                <select name="course_id" class="form-control" required="required" ng-model="current_course_sc" ng-change="get_sub_courses(current_course_sc)">
                                    <option ng-repeat="course in academic_courses_sc" value="@{{ course.course_id }}">@{{ course.course_title }}</option>
                                </select>
                            </fieldset>
                            <fieldset class="form-group col-md-3">
                                <label for="">{{getPhrase('class')}}</label>
                                <span class="text-red">*</span>
                                <select name="class" class="form-control" required="required" ng-model="current_sub_course">
                                    <option ng-repeat="sub_course in subcourses" value="@{{ sub_course.id }}">@{{ sub_course.course_title }}</option>
                                </select>
                            </fieldset>
                        </div>

                        <div class="row">
                            <div class="col-md-12 vertical-scroll" >

                                <table class='table table-bordered table-striped' ng-if="current_sub_course">
                                    <thead>
                                    <tr>
                                        <th><center>branch_id</center></th>
                                        <th><center>academic_id</center></th>
                                        <th><center>current_semister</center></th>
                                        <th><center>category_id</center></th>
                                        <th><center>course_parent_id</center></th>
                                        <th><center>course_id</center></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>@{{ current_branch }}</td>
                                        <td>@{{ current_year_sc }}</td>
                                        <td>@{{ current_sem_sc }}</td>
                                        <td>@{{ current_category }}</td>
                                        <td>@{{ current_course_sc }}</td>
                                        <td>@{{ current_sub_course }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                                <br><br><br>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /#page-wrapper -->
@endsection

@section('footer_scripts')
    @include('common.validations')
    @include('common.alertify')
    @include('users.import.scripts.js-scripts')
    <script>
        var file = document.getElementById('excel_input');

        file.onchange = function(e){
            var ext = this.value.match(/\.([^\.]+)$/)[1];
            switch(ext)
            {
                case 'xls':
                case 'xlsx':

                    break;
                default:
                    alertify.error("{{getPhrase('file_type_not_allowed')}}");
                    this.value='';
            }
        };
    </script>
@stop