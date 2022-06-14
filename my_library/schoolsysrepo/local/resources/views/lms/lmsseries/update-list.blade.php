@extends(getLayout())
<script src="{{JS}}angular.js"></script>
 @section('custom_div')

 <div ng-controller="prepareQuestions">

 @stop

@section('content')

<div id="page-wrapper">

			<div class="container-fluid">

				<!-- Page Heading -->

				<div class="row">

					<div class="col-lg-12">

						<ol class="breadcrumb">

							<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
							<li><a  href="{{URL_LMS_DASHBOARD}}">{{getPhrase('lms')}} {{ getPhrase('dashboard')}}</a></li>

							<li><a href="{{URL_LMS_SERIES}}">{{getPhrase('lms')}} {{ getPhrase('series')}}</a></li>

							<li class="active">{{isset($title) ? $title : ''}}</li>

						</ol>

					</div>

				</div>

					@include('errors.errors')

				<?php $settings = ($record) ? $settings : ''; ?>

				<div class="panel panel-custom" ng-init="initAngData({{$settings}});" >

					<div class="panel-heading">

						<div class="pull-right messages-buttons">

							<a href="{{URL_LMS_SERIES}}" class="btn  btn-primary button" >{{ getPhrase('list')}}</a>

						</div>

					<h1>{{ $title }}  </h1>

					</div>

					<div class="panel-body" >

					<?php $button_name = getPhrase('create'); ?>

						<div class="row">
							<fieldset class="form-group col-md-4">
								<label for="">{{getPhrase('academic_year')}}</label>
								<span class="text-red">*</span>
								<select name="year_id" class="form-control"  required="required" ng-model="current_year_sc" ng-change="get_sems()">
									<option  ng-repeat="year in academic_years_sc" value="@{{ year.id }}">@{{ year.academic_year_title }}</option>
								</select>
							</fieldset>
							<fieldset class="form-group col-md-4">
								<label for="">{{getPhrase('Semester')}}</label>
								<span class="text-red">*</span>
								<select name="sem_id" class="form-control" required="required" ng-model="current_sem_sc" ng-change="getSubjects()">
									<option ng-repeat="sem in academic_sems_sc" id="@{{ sem.sem_num }}" value="@{{ sem.sem_num }}"> @{{ sem.title  }}</option>
								</select>
							</fieldset>
							<fieldset class="form-group col-md-4">
								<label for="">{{getPhrase('branch')}}</label>
								<span class="text-red">*</span>
								<select name="course_id" class="form-control" required="required" ng-model="current_course_sc" ng-change="getSubjects()">
									<option ng-repeat="course in academic_courses_sc" value="@{{ course.id }}">@{{ course.course_title }}</option>
								</select>
							</fieldset>
						</div>

					 		<div class="row">

							<fieldset class="form-group col-md-4">

								{{ Form::label('select_subject', getphrase('select_subject')) }}


								<select name="select_subject" class="form-control" required="required" ng-model="current_subject_sc" ng-change="get_topics();">
									<option ng-repeat="subject in academic_subjects_sc" value="@{{ subject.subject_id }}">@{{ subject.subject_title }}</option>
								</select>


							</fieldset>
							<fieldset class="form-group col-md-4">

								{{ Form::label('topic', getphrase('select_topic')) }} 
								<select  name="topic" class="form-control" required="required" ng-model="current_topic_sc"  ng-change="categoryChanged(current_subject_sc)">
										<option value="">{{getphrase('select')}}</option>
										<optgroup ng-repeat="topic in main_topic" label="@{{ topic.topic_name }}">
											<option ng-repeat="subs in topic.sub_topics" value="@{{ subs.id }}">@{{ subs.topic_name }} (@{{ subs.total }})</option>
										</optgroup>
										{{--<option ng-repeat="topic in topics_sc" ng-class="topic.parent_id == 0 ? 'optionGroup' : 'optionChild' " value="@{{ topic.id }}">@{{ topic.topic_name }}</option>--}}
									{{--<option ng-repeat="topic in topics_sc" ng-class="topic.parent_id == 0 ? 'optionGroup' : 'optionChild' " value="@{{ topic.id }}">@{{ topic.topic_name }}</option>--}}
									</select>

							</fieldset>

						<?php $lmssettings = getSettings('lms');?>

							<fieldset class="form-group col-md-4">

								{{ Form::label('file_type', getphrase('file_type')) }}



								{{Form::select('file_type', $lmssettings->content_types, null, ['class'=>'form-control', 'ng-model' => 'content_type',

								'placeholder' => getPhrase('Select')  ])}}

							</fieldset>


								<div class="col-md-12">

							<div ng-if="examSeries!=''" class="vertical-scroll" >



								<h4 ng-if="categoryItems.length>0" class="text-success">{{getPhrase('total_items')}}: @{{ categoryItems.length}} </h4>



								<table

								  class="table table-hover">

  									<th>{{getPhrase('title')}}</th>

									<th>{{getPhrase('code')}}</th>

									<th>{{getPhrase('type')}}</th>

                                    <th>{{getPhrase('action')}}</th>

									<tr ng-repeat="item in categoryItems | filter : {content_type: content_type} | filter:search_term  track by $index">

										<td

										title="@{{item.title}}" >

										@{{item.title}}

										</td>

										<td>@{{item.code}}</td>

										<td>@{{item.content_type}}</td>
                                        </td>

										<td><a

										ng-click="addToBag(item);" class="btn btn-primary" >{{getPhrase('add')}}</a>

									  	</td>

									</tr>

								</table>

								</div>

							</div>

					 		</div>

					</div>

                  </div>

			</div>

			<!-- /.container-fluid -->

		</div>

		<!-- /#page-wrapper -->

@stop

@section('footer_scripts')

@include('lms.lmsseries.scripts.js-scripts')

@stop

@section('custom_div_end')

 </div>

@stop
