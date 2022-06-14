@extends($layout)
@section('header_scripts')
    <link href="{{CSS}}ajax-datatables.css" rel="stylesheet">
@stop
@section('content')

<?php
$classTitle = $submitted_data->course_record->course_title;

?>
    <div id="page-wrapper" ng-controller="attendanceController" ng-init="initAngData('{{count($students)}}');">
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a></li>
                        @if($role_name!='staff' and $role_name != 'educational_supervisor')
                            <li><a href="{{URL_ACADEMICOPERATIONS_DASHBOARD}}">
                                    {{getPhrase('academic_operations')}}</a></li>
                        @endif
                        <li>{{ $title }}</li>
                        <li>{{ $slugData->name }}</li>
                        <li><a href="{{url('student/year_grades/'.Auth::user()->slug)}}">{{ getPhrase('student_sheet_for_yearGrade').' '.$classTitle }}</a>
                        </li>
                    </ol>
                </div>
            </div>

            <!-- /.row -->
            <div class="panel panel-custom">
                <div class="panel-heading">

                    @if(request()-> get('status') != 1)

                	     <div class="pull-right messages-buttons helper_step1">
        						<a href="{{url('student/year_grades/template/import')}}" class="btn  btn-primary button">تنزيل  ملف اكسل</a>

        						<a href="{{url('student/year_grades/upload/'.Auth::user() -> slug)}}?{{$args}}" class="btn  btn-primary button">  رفع وحفظ ملف اكسل </a>

						 </div>
                        @endif


                    <div class="row">
                        <div class="col-sm-8">
                            <h1>{{ getPhrase('student_sheet_for_yearGrade').' '.$classTitle }} - {{ getPeriodNumber($period) }}</h1>
 							<strong> - </strong>
							<strong>{{$submitted_data->phase_title}}</strong>
							<strong> - </strong>
							<strong>{{$submitted_data->course_title}}</strong>
							<strong> - </strong>
							<strong>{{$submitted_data->user_name}}</strong>
							<strong> - </strong>
							<strong>{{$submitted_data->subject_title}}</strong>
							</p>
                        </div>

                        <div class="col-sm-4 text-right">
                            <ul class="list-unstyled attendance_summary">
                                <li class="clearfix">
                                    <p class="pull-left"><strong>{{getphrase('total')}}:</strong> @{{total}}</p>
                                 </li>

                            </ul>
                            <span>

						</span>
                        </div>
                    </div>


                </div>
                <?php
                ?>
                {!! Form::open(array('url' => url('student/year_grades/update/'.$userdata->slug),'id'=>'form', 'method' => 'POST')) !!}
                {{csrf_field()}}



                <input type="hidden" name="args" value="{{$args}}">

                <fieldset @if($role_name =='educational_supervisor') disabled @endif>
                    <input type="hidden" name="academic_id" value="{{$submitted_data->academic_id}}">
                    <input type="hidden" name="course_id" value="{{$submitted_data->course_record->id}}">

                    <input type="hidden" name="subject_id" value="{{$submitted_data->subject_id}}">
                     <input type="hidden" name="record_updated_by" value="{{$submitted_data->updated_by}}">

                    <input type="hidden" name="current_year" value="{{$submitted_data->current_year}}">
                    <input type="hidden" name="current_semister" value="{{$submitted_data->current_semister}}">

                    <div class="panel-body packages" id="myForm">
                        <div class="table-responsive vertical-scroll">
                            <table class="table table-striped table-bordered student-attendance-table datatable"
                                   cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>{{ getPhrase('sno')}}</th>
                                    <th>{{ getPhrase('photo')}}</th>
                                    <th style="width:20%">{{ getPhrase('name')}}</th>
                                    <th>{{ getPhrase('Quiz')}}</th>
                                    <th>{{ getPhrase('chapter_test')}}</th>
                                    <th>{{ getPhrase('project')}}</th>
                                    <th>{{ getPhrase('Portfolio')}}</th>

                                    <th>{{ getPhrase('Participation')}}</th>
                                    <th>{{ getPhrase('Total')}}</th>
                                    <th>{{ getPhrase('Letter_Grade')}}</th>
                                    <th>{{ getPhrase('Comment_Code')}}</th>
                                 </tr>
                                </thead>

                                <?php $sno = 1; ?>
                                @foreach($students as $student)
                                    <?php $user = $student->user()->first(); ?>

                                    <?php

                                       if($student -> degrees){
                                       	   $degrees = json_decode($student -> degrees);
                                       }

                                    ?>

                                     <tr>
                                        <td>{{ $sno++ }}</td>

                                        <td><img src="{{getProfilePath(@$user->image)}}"></td>
                                        <td style="width:20%">{{ $student->name }}</td>

                                         <input type="hidden" name="students[{{$student -> id}}][name]" value="{{@$student->name}}">

                                        <td>
                                               @if(request()-> get('status') == 1)
                                                 <p>{{@$degrees -> quize }}</p>
                                                @else
                                                 <input type="text" style="text-align: center;width:50px;" name="students[{{$student -> id}}][quize]" value="{{@$degrees -> quize }}">
                                                @endif

                                        </td>
                                        <td>
                                               @if(request()-> get('status') == 1)
                                               <p>{{@$degrees -> chapter_test  }}</p>
                                                @else

                                                 <input type="text" style="text-align: center;width:50px; " name="students[{{$student -> id}}][chapter_test]" value="{{@$degrees -> chapter_test  }}" >
                                                @endif

                                        </td>
                                        <td>
                                               @if(request()-> get('status') == 1)
                                               <p>{{@$degrees -> project }}</p>
                                                @else
                                                 <input type="text" style="text-align: center;width:50px;" name="students[{{$student -> id}}][project]" value="{{@$degrees -> project }}">
                                                @endif

                                        </td>
                                        <td>

                                                @if(request()-> get('status') == 1)
                                                <p>{{@$degrees -> portfolio  }}</p>
                                                @else
                                                <input type="text" style="text-align: center;width:50px;" name="students[{{$student -> id}}][portfolio]" value="{{@$degrees -> portfolio  }}">
                                                @endif

                                        </td>

                                        <td>
                                               @if(request()-> get('status') == 1)
                                               <p>{{@$degrees -> participation}}</p>
                                                @else
                                                 <input type="text" style="text-align: center;width:50px;" name="students[{{$student -> id}}][participation]" value="{{@$degrees -> participation}}">
                                                @endif

                                        </td>
                                        <td>

                                               @if(request()-> get('status') == 1)
                                                <p>{{@$degrees -> total }}</p>
                                                @else
                                                <input type="text" style="text-align: center;width:50px;" name="students[{{$student -> id}}][total]" value="{{@$degrees -> total }}">

                                                @endif

                                        </td>
                                        <td>

                                               @if(request()-> get('status') == 1)
                                                  <p>{{@$degrees -> letter_grade   }}</p>
                                                @else
                                                <input type="text" style="text-align: center;width:50px;" name="students[{{$student -> id}}][letter_grade]" value="{{@$degrees -> letter_grade   }}">
                                                @endif

                                        </td>
                                        <td>

                                               @if(request()-> get('status') == 1)
                                               <p> {{@$degrees -> comment_code   }}</p>
                                                @else
                                                <input type="text" style="text-align: center;width:50px;" name="students[{{$student -> id}}][comment_code]" value="{{@$degrees -> comment_code   }}">
                                                @endif

                                        </td>
                                    </tr>


                                @endforeach
                            </table>
                        </div>
                        <div class="buttons text-center">

                             @if(request()-> get('status') != 1)

                             <button  id="ajaxSubmit" class="btn btn-lg btn-primary button">{{ getPhrase('update') }}</button>

                             @else

                          <?php
                          $args =  substr(url()->full(), strpos(url()->full(), "?") + 1); ?>
                                <a  href="{{url('student/year_grades-report/'.$slugData->slug)}}?{{$args}}&type=make_report" class="btn btn-lg btn-primary button">{{ getPhrase('print') }}</a>

                            @endif
                        </div>
                    </div>
                </fieldset>
                </form>
            </div>

        </div>
        <!-- /.container-fluid -->
    </div>

@endsection

@section('footer_scripts')

    @include('attendance.scripts.attendance-script')

@stop
