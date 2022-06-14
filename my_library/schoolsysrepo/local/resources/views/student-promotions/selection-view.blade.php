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


      <div class="modal fade" id="transfer_student_summary" tabindex="-1" role="dialog"
      aria-labelledby="myModalLabel">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header text-center">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">{{getPhrase('are_you_sure')}}</h4>
            </div>
            <div class="modal-body attendance-calendar-report">

              <div class="row">

                <div class="col-md-12">

                  {!! Form::open(array('url' => URL_STUDENT_TRANSFER_SINGLE_STUDENT, 'method' => 'POST', 'name'=>'idCards ','novalidate'=>'')) !!}


                  <div class="col-md-5">
                    <div class="">
                      <a href="#">
                        <img class="profile" ng-if="current_user.image!=''"
                        src="{{IMAGE_PATH_PROFILE}}@{{current_user.image}}"
                        alt="@{{current_user.name}}">
                        <img class="profile" ng-if="current_user.image==''"
                        src="{{IMAGE_PATH_PROFILE_DEFAULT}}"
                        alt="@{{current_user.name}}">
                      </a>

                      <h4 class="media-heading">@{{current_user.name}}</h4>
                      <p class="">@{{ current_user.mobile}}, @{{current_user.home_phone
                      }} </p>
                      <p>
                        <a href="mailto:@{{current_user.email}}">@{{current_user.email}}</a>
                      </p>
                      <input type="hidden" name="academic_id"
                      value="@{{current_user.academic_id}}">
                      <input type="hidden" name="course_id"
                      value="@{{current_user.course_id}}">
                      <input type="hidden" name="current_year"
                      value="@{{current_user.current_year}}">
                      <input type="hidden" name="current_semister"
                      value="@{{current_sem_sc}}">
                      <input type="hidden" name="user_id"
                      value="@{{current_user.user_id}}">

                    </div>

                  </div>

                  <div class="col-md-7">
                    <ul class="list-unstyled text-left attendance-calendar-report">
                      <li class="title"><strong>{{getPhrase('details')}}</strong></li>

                      <li><strong>{{getPhrase('academic_year')}}</strong>
                        @{{current_user.academic_year_title}}
                      </li>
                      <li><strong>{{getPhrase('admission_no')}}</strong>
                        @{{current_user.admission_no}}
                      </li>
                      <li><strong>{{getPhrase('course')}}</strong>
                        @{{current_user.course_title}}
                      </li>
                      <li ng-show="current_user.course_dueration>1">
                        <strong>{{getPhrase('current_year')}}</strong>
                        @{{current_user.current_year}}
                      </li>
                      <li ng-show="current_user.current_semister>0">
                        <strong>{{getPhrase('current_semister')}} </strong>
                        @{{current_sem_sc}}
                      </li>
                      <li><strong>{{getPhrase('remarks')}} </strong> <br><textarea
                        class="form-control" name="remarks"
                        ng-model="current_user_remarks">@{{current_user_remarks}}</textarea>
                      </li>
                    </ul>
                  </div>

                </div>
              </div>

            </div>
            <div class="modal-footer text-center">
              <a type="button" class="btn btn-success button btn-lg"
              data-dismiss="modal">{{getPhrase('cancel')}}</a>
              <button type="submit"
              class="btn btn-danger button btn-lg">{{getPhrase('confirm')}}</button>
            </div>
          </div>
        </div>
        {!! Form::close() !!}

      </div>


      {!! Form::open(array('url' => URL_STUDENT_TRANSFERS, 'method' => 'POST', 'name'=>'idCards ','novalidate'=>'')) !!}

      <div class="panel panel-custom">
        <div class="panel-heading">
          <h1>
            {{getPhrase('select_details')}}

          </h1>
        </div>
        <div class="panel-body instruction">
          <div class="row">
            <div class="col-md-6">

              <div class="row">
                <div class="col-md-8">
                  <fieldset class="form-group">
                    <label for="">{{getPhrase('academic_year')}}</label>
                    <span class="text-red">*</span>
                    <select name="academic_id" class="form-control"  required="required" ng-model="current_year_sc" ng-change="get_sems()">
                      <option  ng-repeat="year in academic_years_sc" value="@{{ year.id }}">@{{ year.academic_year_title }}</option>
                    </select>
                  </fieldset>
                </div>
              </div>
              <div class="row">
                <div class="col-md-8">
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
                <div class="col-md-8">
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
              </div>
              <div class="row">
                <div class="col-md-8">
                  <fieldset class="form-group">
                    <label for="">{{getPhrase('class')}}</label>
                    <span class="text-red">*</span>
                    <select name="course_id" class="form-control" required="required" ng-change="doCall()"
                            ng-model="current_sub_course">
                            <option value="0">{{getPhrase('select')}}</option>
                      <option ng-repeat="aclass in subcourses"
                              value="@{{ aclass.id }}">@{{ aclass.course_title }}</option>
                    </select>
                  </fieldset>
                </div>
              </div>


            </div>

            <div class="col-md-6">
              <div class="row">
                <fieldset class='form-group col-md-12'>
                  <div class="form-group row">
                    <div class="col-md-6">
                      {{ Form::radio('is_completed', 0, true, array('id'=>'free', 'name'=>'is_completed', 'ng-model'=>'is_completed')) }}

                      <label for="free"> <span class="fa-stack radio-button"> <i
                        class="mdi mdi-check active"></i> </span> {{getPhrase('do_you_want_to_promot_them')}}
                      </label>
                    </div>
                    <div class="col-md-6" ng-show="thirdYear">
                      {{ Form::radio('is_completed', 1, false, array('id'=>'paid', 'name'=>'is_completed','ng-model'=>'is_completed')) }}
                      <label for="paid"> <span class="fa-stack radio-button"> <i
                        class="mdi mdi-check active"></i> </span> {{getPhrase('do_you_want_to_graduate_them')}}
                      </label>
                    </div>
                  </div>
                </fieldset>
              </div>
              <div ng-show="is_completed==0">
                @if(Module_state('branches'))
                <fieldset class="form-group">
                  {{ Form::label('school_branch', getphrase('school_branch')) }}

                  <span class="text-red">*</span>
                  {{Form::select('branch', $branches, $default_branch, ['placeholder' => getPhrase('select_branch'),'class'=>'form-control',
                  'required'=> 'true',
                  ])
                  }}
                </fieldset>
                @endif
                <fieldset class="form-group">
                  {{ Form::label ('to_academic_year', getphrase('academic_year')) }}
                  {{ Form::select('to_academic_id', $academic_years, default_year(),
                  [   'class'     => 'form-control',
                  "id"        => "to_select_academic_year",
                  "ng-model"  => "to_academic_year",
                  "ng-change"  => "prepareToCourses()"
                  ])}}
                </fieldset>

                  <fieldset class="form-group">
                    <label for="category_id">{{getPhrase('category')}}</label>
                    <span class="text-red">*</span>
                    <select name="category_id" class="form-control"  required="required" ng-model="current_category" ng-change="get_courses()">
                      <option  ng-repeat="category in categorties" value="@{{ category.id }}">@{{ category.category_name }}</option>
                    </select>
                  </fieldset>

                {{--for another year class--}}
                <fieldset ng-if="showToNewParent" class="form-group">
                  <label for="to_course_parent_id">{{getphrase('transfer_to_course')}}</label>
                  <select required
                  name="to_course_parent_id"
                  id="to_course_parent_id"
                  class="form-control"
                  ng-model="to_course_parent_id"
                  ng-change="get_sub_courses(to_course_parent_id)"
                  ng-options="option.course_id as option.course_title for option in p_courses track by option.course_id">
                  <option value="">{{getPhrase('select')}}</option>

                </select>
              </fieldset>
              <fieldset ng-if="showToCourses" class="form-group">

                <label for="course_id">{{getPhrase('transfer_to_class')}}</label>
                <select required
                name="to_course"
                id="to_course"
                class="form-control"
                ng-model="to_course.course_id"
                ng-change="prepareToYears(to_course.course_id)"
                ng-options="option.id as option.course_title for option in to_courses track by option.id">
                <option value="">{{getPhrase('select')}}</option>

              </select>
            </fieldset>


            <div ng-if="to_total_years<=1">
              <input type="hidden" name="to_year" value="1">
              <input type="hidden" name="to_semister" value="0">
            </div>

            <fieldset ng-if="to_total_years>1" class="form-group">

              <label for="year">{{getPhrase('transfer_to_year')}}</label>

              <select
              name="to_year"
              class="form-control"
              ng-model="to_years.year"
              ng-options="v for v in to_years.values track by v"
              ng-change="toYearChanged(to_years.year)"
              >
            </select>
          </fieldset>

          <fieldset ng-if="to_total_semisters>0" class="form-group">

            <label for="semister">{{getPhrase('transfer_to_semister')}}</label>

            <select
            name="to_semister"
            class="form-control"
            ng-model="to_semisters.current_semister"
            ng-options="v for v in to_semisters.values track by v"
            >
          </select>

        </fieldset>

      </div>
    </div>
  </div>
  <hr>
  <div style="margin-right: 460px;">

    <a ng-click="promptAll()" class="btn btn-primary">{{ getPhrase('promote_all')}}</a>
    <a ng-click="stopAll()" class="btn btn-danger">{{ getPhrase('detain_all')}}</a>
    <a ng-click="nothingAll()" class="btn btn-info">{{ getPhrase('no_action_all')}}</a>

  </div>
  <br><br>
  <div class="table-responsive vertical-scroll">
    <table class="table">
      <thead>
        <th>{{getPhrase('image')}}</th>
        <th>{{getPhrase('name')}}</th>
        <th>{{getPhrase('roll_no')}}</th>
        <th>{{getPhrase('admission_no')}}</th>
        <th>{{getPhrase('action')}}</th>
        <th>{{getPhrase('remarks')}}</th>
        <th ng-show="thirdYear">{{getPhrase('action')}}</th>
      </thead>
      <tbody>
	  
        <tr ng-repeat="user in result_data track by user.id">
          <td>
            <img ng-if="user.image!=null && user.image!=''" class="thumb"
            src="{{IMAGE_PATH_PROFILE}}@{{user.image}}" height="60">

            <img ng-if="user.image==null || user.image==''" class="thumb"
            src="{{IMAGE_PATH_USERS_DEFAULT_THUMB}}">
          </td>
          <td>@{{user.name}}</td>
          <td>@{{user.roll_no}}</td>
          <td>@{{user.admission_no}}</td>
          <td>
            <select id="selctionAction@{{user.id}}" name="selected_list[@{{user.id}}]" class="form-control">
              <option value="promoted"
              selected="true">{{getPhrase('promoted')}}</option>
              <option value="detained">{{getPhrase('detained')}}</option>
              <option value="nothing">{{ getPhrase('no_action')}}</option>
            </select>
          </td>
          <td><textarea cols="20" rows="2" ng-model="remarks[user.id]"
            name="remarks[@{{user.id}}]"></textarea></td>
            <td ng-show="thirdYear"><a class="btn btn-success"
              ng-click="showConfirmation(user, remarks[user.id])">{{getPhrase('course_completed')}}</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>


    <button ng-if="users.length>0" class="btn btn-info pull-right" type="submit">
      {{getPhrase('transfer')}}
    </button>
    <div ng-if="result_data.length==0"
    class="text-center">{{getPhrase('no_users_available')}}</div>
    <br>
  </div>
</div>

</hr>
</div>
</div>
</div>
</div>



<div class="text-center">
  <button type="submit" class="btn button btn-lg btn-primary">
    {{getPhrase('transfer')}}
  </button>
</div>


{!! Form::close() !!}


@stop

@section('custom_div_end')
</div>
@stop
@section('footer_scripts')


@include('student-promotions.scripts.js-scripts')

@stop
