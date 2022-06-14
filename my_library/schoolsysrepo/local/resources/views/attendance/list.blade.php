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
                        <li><a href="{{URL_STUDENT_ATTENDENCE.Auth::user()->slug}}">{{ getPhrase('attendance_sheet_for').' '.$classTitle }}</a>
                        </li>
                    </ol>
                </div>
            </div>

            <!-- /.row -->
            <div class="panel panel-custom">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-sm-8">
                            <h1>{{ getPhrase('attendance_sheet_for').' '.$classTitle }} - {{ getPeriodNumber($period) }}</h1>
                            <p><strong>{{ getPhrase('date').' '.$submitted_data->attendance_date }}</strong>
							<strong> - </strong>
							<strong>{{$submitted_data->phase_title}}</strong>
							<strong> - </strong>
							<strong>{{$submitted_data->course_title}}</strong>
							<strong> - </strong>
							<strong>{{$submitted_data->user_name}}</strong>
							<strong> - </strong>
							<strong>{{$submitted_data->subject_title}}</strong>
							<strong> - </strong>
							<strong>{{getPeriodNumber($submitted_data->total_class)}}</strong>

							</p>

                        </div>

                        <div class="col-sm-4 text-right">
                            <ul class="list-unstyled attendance_summary">
                                <li class="clearfix">
                                    <p class="pull-left"><strong>{{getphrase('total')}}:</strong> @{{total}}</p>
                                    <p class="pull-right"><strong>{{getphrase('present')}}:</strong> @{{present}}</p>
                                </li>
                                <li class="clearfix">
                                    <p class="pull-left text-danger"><strong>{{getphrase('absent')}}:</strong> @{{absent}}</p>
                                    <p class="pull-right"><strong>{{getphrase('leave')}}:</strong> @{{leave}}</p>
                                </li>
                            </ul>
                            <span>

						</span>
                        </div>
                    </div>


                </div>
                <?php
                ?>
                {!! Form::open(array('url' => URL_STUDENT_ATTENDENCE_UPDATE.$userdata->slug,'id'=>'form', 'method' => 'POST')) !!}
                <fieldset @if($role_name =='educational_supervisor') disabled @endif>
                    <input type="hidden" name="academic_id" value="{{$submitted_data->academic_id}}">
                    <input type="hidden" name="course_id" value="{{$submitted_data->course_record->id}}">

                    <input type="hidden" name="subject_id" value="{{$submitted_data->subject_id}}">
                    <input type="hidden" name="total_class" value="{{$submitted_data->total_class}}">
                    <input type="hidden" name="record_updated_by" value="{{$submitted_data->updated_by}}">

                    <input type="hidden" name="current_year" value="{{$submitted_data->current_year}}">
                    <input type="hidden" name="current_semister" value="{{$submitted_data->current_semister}}">
                    <input type="hidden" name="attendance_date" value="{{$submitted_data->attendance_date}}">
                    <input type="hidden" name="attendance_taken" value="{{$attendance_taken}}">

                    <div class="panel-body packages" id="myForm">
                        <div class="table-responsive vertical-scroll">
                            <table class="table table-striped table-bordered student-attendance-table datatable"
                                   cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>{{ getPhrase('sno')}}</th>

                                    <th>{{ getPhrase('photo')}}</th>
                                    <th style="width:20%">{{ getPhrase('name')}}</th>
                                    <th>{{ getPhrase('attendance_status')}}</th>
                                    <th>{{ getPhrase('notes')}}</th>
                                    <th>{{ getPhrase('activities')}}</th>
                                    <th>{{ getPhrase('health_status')}}</th>
                                </tr>
                                </thead>
                                <div class="row">
                                    <div class="centered" style="    margin-right: 39%;">
                                        {{ Form::radio('pall', 'P',false, array('id'=>'pall','name'=>'all','ng-click' => 'all_here()','class' => 'attendance_code')) }}
                                        <label for="pall" style="margin-left: 3%"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span> {{getPhrase('all_here')}}   </label>

                                        {{ Form::radio('aall', 'A',false, array('id'=>'aall','name'=>'all','ng-click' => 'all_absent()','class' => 'attendance_code')) }}
                                        <label for="aall" style="margin-left: 3%"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span> {{getPhrase('all_absent')}}   </label>

                                        {{ Form::radio('lall', 'P',false, array('id'=>'lall','name'=>'all','ng-click' => 'all_left()','class' => 'attendance_code')) }}
                                        <label for="lall" style="margin-left: 3%"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span> {{getPhrase('all_left')}}   </label>

                                        {{ Form::radio('cAll', 'P',false, array('id'=>'cAll','name'=>'all','ng-click' => 'cancel_all()','class' => 'attendance_code')) }}
                                        <label for="cAll"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span> {{getPhrase('cancel_all')}}   </label>
                                    </div>

                                </div>

                                {{--<input type="radio" name="test" value="test" onclick="test()" id="testit">test<br>--}}
                                {{--<label for="testit"> <span--}}
                                            {{--class="fa-stack radio-button"> <i--}}
                                                {{--class="mdi mdi-check active"></i> </span> {{getPhrase('present')}}--}}
                                {{--</label>--}}
                                <?php $sno = 1; ?>
                                @foreach($students as $student)
                                    <?php $user = $student->user()->first(); ?>
                                    <tr>
                                        <td>{{ $sno++ }}</td>

                                        <td><img src="{{getProfilePath(@$user->image)}}"></td>
                                        <td style="width:20%">{{ $student->name }}</td>
                                        <td>
                                            <div class="col-md-4">
                                                <?php
                                                $present = false;
                                                $absent = false;
                                                $leave = false;
                                                $activities = '';
                                                $notes = '';
                                                $healthStatus = '' ;
                                                if($attendance_taken) {
                                                foreach($attendance_records as $atr)
                                                {

                                                if($student->id == $atr->student_id)
                                                {
                                                $present    = false;
                                                $absent    = false;
                                                $leave        = false;
                                                $notes = $atr->notes;
                                                $activities = $atr->activities;
                                                $healthStatus = $atr->health_status;
                                                switch ($atr->attendance_code) {
                                                case 'P':
                                                $present = true;
                                                break;
                                                case 'A':
                                                $absent = true;
                                                break;
                                                case 'L':
                                                $leave = true;
                                                break;

                                                default:
                                                $present = true;
                                                break;
                                                }
                                                break;
                                                }
                                                }
                                                } ?>

                                                {{ Form::radio('attendance_code', 'P', $present, array(
                                                    'id'=>'present'.$student->id,
                                                    'name'=>'attendance_code['.$student->id.']',
                                                    'ng-click' => 'updateCount()',
                                                    'class' => 'attendance_code all_here'
                                                )) }}

                                                <label for="present{{$student->id}}"> <span
                                                            class="fa-stack radio-button"> <i
                                                                class="mdi mdi-check active"></i> </span> {{getPhrase('present')}}
                                                </label>
                                            </div>
                                            <div class="col-md-4">
                                                {{ Form::radio('attendance_code', 'A', $absent, array('id'=>'absent'.$student->id, 'name'=>'attendance_code['.$student->id.']',
                                                    'ng-click' => 'updateCount()','class' => 'all_absent'
                                                )) }}
                                                <label for="absent{{$student->id}}"> <span
                                                            class="fa-stack radio-button"> <i
                                                                class="mdi mdi-check active"></i> </span> {{getPhrase('absent')}}
                                                </label>
                                            </div>
                                            <div class="col-md-4">
                                                {{ Form::radio('attendance_code', 'L', $leave, array('id'=>'leave'.$student->id, 'name'=>'attendance_code['.$student->id.']',
                                                'ng-click' => 'updateCount()','class' => 'all_left'
                                                )) }}
                                                <label for="leave{{$student->id}}"> <span class="fa-stack radio-button"> <i
                                                                class="mdi mdi-check active"></i> </span> {{getPhrase('leave')}}
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            {{ Form::textarea('notes', $notes , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('notes'), 'rows'=>1, 'cols'=>15, 'name'=>'notes['.$student->id.']')) }}
                                        </td>
                                        <td>

                                          {{ Form::textarea('activities', $activities , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('activities'), 'rows'=>1, 'cols'=>15, 'name'=>'activities['.$student->id.']')) }}

                                        </td>
                                        <td>
                                          {{ Form::textarea('health_status', $healthStatus , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('health_status'), 'rows'=>1, 'cols'=>15, 'name'=>'health_status['.$student->id.']')) }}
                                        </td>
                                    </tr>

                                @endforeach
                            </table>
                        </div>
                        <div class="buttons text-center">
                            <button  id="ajaxSubmit" class="btn btn-lg btn-primary button">{{ getPhrase('update') }}</button>
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
