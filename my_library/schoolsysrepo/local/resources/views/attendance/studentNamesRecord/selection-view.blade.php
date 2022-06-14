@extends($layout)

@section('header_scripts')
<link href="{{CSS}}bootstrap-datepicker.css" rel="stylesheet">
@stop

@section('custom_div')
<div ng-controller="TabController">
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
              <a href="{{URL_ACADEMICOPERATIONS_DASHBOARD}}">
                {{getPhrase('academic_operations')}}
              </a>
            </li>
            <li>

              {{$title}}

            </li>
          </ol>
        </div>
      </div>

      <div class="panel panel-custom">
        <div class="panel-heading">
          <h1>
            {{getPhrase('select_details')}}

          </h1>
        </div>
        <div class="panel-body instruction">
          <div class="row">
            <div class="col-md-12">

              <div class="row">
                <div class="col-md-6">
                  <fieldset class="form-group">
                    <label for="">{{getPhrase('academic_year')}}</label>
                    <span class="text-red">*</span>
                    <select name="academic_id" class="form-control"  required="required" ng-model="current_year_sc" ng-change="get_sems()">
                      <option  ng-repeat="year in academic_years_sc" value="@{{ year.id }}">@{{ year.academic_year_title }}</option>
                    </select>
                  </fieldset>
                </div>
                <div class="col-md-6">
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
                    <label for="">{{getPhrase('class')}}</label>
                    <span class="text-red">*</span>
                    <select name="course_id" class="form-control" required="required" ng-change="doCall()"
                            ng-model="current_sub_course">
                      <option ng-repeat="aclass in subcourses"
                              value="@{{ aclass.id }}">@{{ aclass.course_title }}</option>
                    </select>
                  </fieldset>
                </div>
            </div>

  </div>
  <hr>
  <br><br>
  <div  ng-show="result_data.length > 0">
    <table class="table" id="table">
      <thead>
        <th>{{getPhrase('image')}}</th>
        <th>{{getPhrase('name')}}</th>
        <th>{{getPhrase('student_record')}}</th>
        <th>{{getPhrase('New_name_record')}}</th>
        <th></th>
        <th>{{getPhrase('action')}}</th>
      </thead>
      <tbody>
        <tr ng-init="init(user.student_audio)" ng-repeat="user in result_data" ng-controller="recorderController">
          <td>
            <img ng-if="user.image!=null && user.image!=''" class="thumb"
            src="{{IMAGE_PATH_PROFILE}}@{{user.image}}" height="60">

            <img ng-if="user.image==null || user.image==''" class="thumb"
            src="{{IMAGE_PATH_USERS_DEFAULT_THUMB}}">
          </td>
          <td>@{{user.name}}</td>
          <td>
          <div ng-show="!hasRecord">
          <a ng-click="play(user.id)" ng-show="!isPlaying"><span class="glyphicon glyphicon-play">{{getPhrase('Play')}}</a>
            <a ng-click="pause()" ng-show="isPlaying"> <span class="glyphicon glyphicon-pause">{{getPhrase('Pause')}}</a>
              </div>
            </td>
          <td>
              <button id="recordButton@{{user.id}}" ng-click="startRecording(user.id)" ng-disabled="record" class="btn btn-info btn-small"> <span class="glyphicon glyphicon-play"></span>{{getPhrase('Record')}} </button>
              <button id="stopButton@{{user.id}}" ng-click="stopRecording(user.id)" ng-disabled="!record" class="btn btn-info btn-small"><span class="glyphicon glyphicon-pause"></span>{{getPhrase('Stop')}}</button>
          </td>
          <td><div id="record@{{user.id}}"></div>
            <div id="save@{{user.id}}"></div>
          </td>
           <td><button ng-click="deleteRecord(user.id)" class="btn btn-danger deleteExpenses" ng-disabled="hasRecord">{{getPhrase('delete')}}</button></td>
          </tr>
        </tbody>
      </table>
    </div>

    <div ng-if="result_data.length==0" class="text-center">{{getPhrase('no_data_available')}}</div>
    <br>
  </div>
</div>

</hr>
</div>
</div>
</div>



{!! Form::close() !!}


@stop

@section('custom_div_end')
</div>
@stop
@section('footer_scripts')


@include('student-promotions.scripts.js-scripts')
@include('attendance.studentNamesRecord.js-scripts')



@stop
