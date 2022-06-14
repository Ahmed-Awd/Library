@extends($layout)

@section('header_scripts')

@stop

@section('content')
    <div id="page-wrapper" ng-controller="MarksController">
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
                            <a href="">
                                {{getPhrase('teacher_students_marks')}}
                            </a>
                        </li>

                        <li>
                            {{$slug->name}}
                        </li>
                    </ol>
                </div>
            </div>
            {!! Form::open(array('url' => 'supervisor/staff/print-students-marks/'.$slug->slug, 'method' => 'POST', 'name'=>'htmlform ','target'=>'_blank', 'id'=>'htmlform', 'novalidate'=>'')) !!}

            <div class="panel panel-custom">
                <div class="panel-heading">
                    <div class="pull-right">
                        @if(Auth::user()->role_id!=9)
                        <a class="btn btn-primary"
                           href="{{url('supervisor/staff/students-marks')}}">{{getPhrase('teachers')}}</a> &nbsp;
                        @endif
                    </div>
                    <h1>{{getPhrase('outstanding_students_report')}}</h1>
                </div>
                <div class="panel-body instruction" style="margin-top: -120px;">
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                                <fieldset class="form-group">
                                    <label for="">{{getPhrase('branch')}}</label>
                                    <span class="text-red">*</span>
                                    <select name="course_parent_id" class="form-control" required="required"
                                            ng-model="current_course_sc" ng-change="getClasses()">
                                    <option ng-repeat="course in academic_courses_sc"
                                            value="@{{ course.id }}">@{{ course.course_title }}</option>
                                    </select>
                                </fieldset>
                        </div>
                        <div class="col-md-6">
                                <fieldset class="form-group">
                                  <label for="">{{getPhrase('classes')}}</label>
                                  <span class="text-red">*</span>
                                  <select name="course_id" class="form-control" required="required"  ng-change="getStudentMarks112()"
                                  ng-model='default'>
                                    <option ng-repeat="aclass in subcourses"
                                            value="@{{ aclass.id }}">@{{ aclass.course_title }}</option>
                                  </select>
                                </fieldset>
                              </div>
                        <fieldset class="form-group col-md-6" ng-hide="true">
                            {{ Form::label('classes', getphrase('classes')) }}
                            <span class="text-red">*</span>
                            <select name="classes" class="form-control" id="classes" required="required"
                                    ng-model='default' ng-change="getStudentMarks112()">
                                <option value="select">{{getPhrase('select')}}</option>
                                @foreach($classes as $class)
                                    @if($class != null)
                                        <?php
                                        $parent_title = new \App\Course();
                                        $parent_title = $parent_title->getParentCourseTitle($class->id);
                                        ?>
                                        <option value="{{$class->id}}">{{$class->course_title.'-'.$parent_title}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                    <div ng-show="result_data.length>0" class="row">

                        <div class="col-sm-4 col-sm-offset-8">
                            <div class="input-group">
                                <input type="text" ng-model="search" class="form-control input-lg"
                                       placeholder="{{getPhrase('search')}}" name="search"/>
                                <span class="input-group-btn">
                        <button class="btn btn-info btn-lg" type="button">
                            <i class="glyphicon glyphicon-search"></i>
                        </button>
                    </span>
                            </div>
                        </div>
                    </div>
                    <br>

                    <div class="row vertical-scroll" ng-if="result_data.subjects.length>0">
                        <h4 ng-if="result_data.students.length>0">@{{course_title}}</h4>
                        <table ng-if="result_data.students.length>0" class="table table-bordered"
                               style="border-collapse: collapse;">
                            <thead>
                            <th style="border:1px solid #000;">{{getPhrase('name')}}</th>

                            <th style="border:1px solid #000;">{{getPhrase('roll_no')}}</th>
                            <th style="border:1px solid #000;" ng-repeat="subject in subjects">@{{subject.title}}
                                (@{{subject.total_marks}})
                            </th>
                            <th style="border:1px solid #000;">{{getPhrase('excellence_ratio')}} %</th>
                            <th style="border:1px solid #000;">{{getPhrase('excellence_level')}}</th>

                            </thead>
                            <tbody>
                            <tr ng-repeat="student in students | filter:search track by $index">
                                <td style="border:1px solid #000;"><a>@{{student.name}}</a>
                                </td>

                                <td style="border:1px solid #000;">@{{student.roll_no}}</td>

                                <td style="border:1px solid #000; text-align: center;"
                                    ng-repeat="marks_record in student.marks"
                                    ng-if="marks_record && marks_record.score != null">
                                    @{{marks_record.score.marks_obtained}}
                                </td>
                                <td style="border:1px solid #000; text-align: center;"
                                    ng-repeat="marks_record in student.marks"
                                    ng-if="marks_record && marks_record.score == null">{{getPhrase('this_student_did_not_take_this_exam')}}
                                </td>
                                <td style="border:1px solid #000;">
                                    <div ng-if="!student.marks">
                                        {{getPhrase('this_student_did_not_take_exams')}}
                                    </div>
                                    <div ng-if="student.marks" class="progress">
                                        <div ng-class="{'progress-bar progress-bar-success':student.average>=75, 'progress-bar progress-bar-warning':student.average<75 && student.average>=50, 'progress-bar progress-bar-danger':student.average<50 && student.average>=0}"
                                             role="progressbar" aria-valuenow="@{{student.average}}"
                                             aria-valuemin="0" aria-valuemax="100" style='width:@{{student.average}}%'>
                                            @{{student.average}}%
                                        </div>
                                    </div>

                                </td>
                                <td style="border:1px solid #000;"
                                    ng-if="student.marks && student.average >= 70">{{getPhrase('excellent_level')}}</td>
                                <td style="border:1px solid #000;"
                                    ng-if="student.marks && student.average < 70 && student.average > 0">{{getPhrase('ordinary_level')}}</td>
                                <td style="border:1px solid #000;"
                                    ng-if="student.marks && student.average <= 0">{{getPhrase('failed')}}</td>
                                <td style="border:1px solid #000;"
                                    ng-if="!student.marks">{{getPhrase('not_exist')}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div ng-if="result_data.students.length<=0"
                         class="text-center">{{getPhrase('no_data_available')}}</div>
                    <div ng-if="result_data.subjects.length<=0"
                         class="text-center">{{getPhrase('no_subject_available_for_this_teacher')}}</div>
                    <br>
                    <input type="hidden" name="classNumber" value="" id="classNumber">
                    <a ng-if="result_data.students.length>0 && result_data.subjects.length>0" class="btn btn-primary"
                       ng-click="printIt()">{{getPhrase('print')}}</a>
                </div>
            </div>
            </hr>
        </div>
    </div>
    {!! Form::close() !!}

@stop

@section('footer_scripts')

    @include('educational_supervisor.js-script-marks',array('slug'=>$slug))

@stop