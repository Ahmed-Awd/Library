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
                    <div class="pull-right">
                        @if ($role == 'educational_supervisor')
                            <a class="btn btn-primary"
                               href="{{url('supervisor/staff/teacher-student-attendance')}}">{{getPhrase('all')}}</a>
                        @endif
                    </div>
                    <h1>{{ getPhrase('year_grade') }}</h1>
                </div>

                <div class="panel-body instruction">


                    <div class="row">

                    	@include('errors.errors')
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
                            {!! Form::open(array('url' => 'student/year_grades/import/file', 'method' => 'POST' ,'files'=>'true')) !!}
                        @elseif($role_name=='educational_supervisor' )
                            {!! Form::open(array('url' => 'supervisor/staff/students-attendance/'.$slugData->slug, 'method' => 'POST', 'files'=>'true')) !!}
                        @endif


                        @if($role_name!='staff' and $role_name!='educational_supervisor' and $role_name!='owner' and $role_name != 'student_guide')

                            <div class="col-md-6">

                                @include('common.year-selection-view')

                            </div>

                        @else
                      
                     
                            <div class="col-md-8">
                                

                                 <fieldset class="form-group col-md-8">
                                    <label for="">{{getPhrase('upload_file')}}</label>
                                    <span class="text-red">*</span>
                                   <input class="form-control" id="excel_input" accept=".xls,.xlsx" name="excel" type="file">
                                </fieldset>

                                  <?php $args = substr(url()->full(), strpos(url()->full(), "?") + 1); ?>
                                <input type="hidden" name="args" value="{{$args}}">
                               
                            </div>
                        

                        @endif

                        <hr>

                        <div class="text-center col-md-6">
                            <button type="submit" id="submitreq" class="btn button btn-lg btn-primary">
                                {{getPhrase('save')}}
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


                $(document).on('click','#submitreq',function(){

                    $(this).disable();
                });
            </script>
    @include('attendance.scripts.js-scripts')

@stop
