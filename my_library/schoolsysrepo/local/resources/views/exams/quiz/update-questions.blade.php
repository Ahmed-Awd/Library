@extends(getLayout())

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
							<li><a  href="{{URL_EXAMS_DASHBOARD}}">{{ getPhrase('exams_dashboard')}}</a></li>


							<li><a href="{{URL_QUIZZES}}">{{ getPhrase('quizzes')}}</a></li>

							<li class="active">{{isset($title) ? $title : ''}}</li>

						</ol>

					</div>

				</div>

					@include('errors.errors')

				<?php $settings = ($record) ? $settings : ''; ?>

				<div class="panel panel-custom" ng-init="initAngData('{{$settings}}');" >

					<div class="panel-heading">

						<div class="pull-right messages-buttons">

							<a href="{{URL_QUIZZES}}" class="btn  btn-primary button helper_step1" >{{ getPhrase('list')}}</a>

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
								<select name="course_id" class="form-control" required="required" ng-model="current_course_sc" ng-init="getSubjects()" ng-change="getSubjects()">
									<option ng-repeat="course in academic_courses_sc" value="@{{ course.id }}">@{{ course.course_title }}</option>
								</select>
							</fieldset>
						</div>
					 		<div class="row">

								<fieldset class="form-group col-md-4"> 

									{{ Form::label('subject', getphrase('select_subject')) }} 
									<select name="subject" class="form-control" id="helper_step2" required="required" ng-model="current_subject_sc" ng-change="get_topics();subjectChanged()">
										<option ng-repeat="subject in academic_subjects_sc" value="@{{ subject.subject_id }}">@{{ subject.subject_title }}</option>
									</select> 
								</fieldset>

								<fieldset class="form-group col-md-4">

									{{ Form::label('topic', getphrase('select_topic')) }} 
									<select  name="topic" class="form-control" required="required" ng-model="current_topic_sc"  ng-change="subjectChanged()">
											<option value="">{{getphrase('select')}}</option>
										    <optgroup ng-repeat="topic in main_topic" label="@{{ topic.topic_name }}">
												<option ng-repeat="subs in topic.sub_topics" value="@{{ subs.id }}">@{{ subs.topic_name }} (@{{ subs.total }})</option>
											</optgroup>
											{{--<option ng-repeat="topic in topics_sc" ng-class="topic.parent_id == 0 ? 'optionGroup' : 'optionChild' " value="@{{ topic.id }}">@{{ topic.topic_name }}</option>--}}
										{{--<option ng-repeat="topic in topics_sc" ng-class="topic.parent_id == 0 ? 'optionGroup' : 'optionChild' " value="@{{ topic.id }}">@{{ topic.topic_name }}</option>--}}
										</select>

								</fieldset>





								<fieldset class="form-group col-md-4 helper_step3">
								{{ Form::label('difficulty', getphrase('difficulty')) }}

								<select ng-model="difficulty" class="form-control" >
								<option value="">{{getPhrase('select')}}</option>
								<option value="easy">{{getPhrase('easy')}}</option>
								<option value="medium">{{getPhrase('medium')}}</option>
								<option value="hard">{{getPhrase('hard')}}</option>
								</select>

								</fieldset>



								<fieldset class="form-group col-md-6 helper_step4">
								{{ Form::label('question_type', getphrase('question_type')) }}
								<select ng-model="question_type" class="form-control" >
									<option selected="selected" value="">{{getPhrase('select')}}</option>
									<option value="radio">{{getPhrase('single_answer')}}</option>
									<option value="checkbox">{{getPhrase('multi_answer')}}</option>
									<option value="blanks">{{getPhrase('fill_in_the_blanks')}}</option>
									<option value="match">{{getPhrase('match_the_following')}}</option>
									<option value="para">{{getPhrase('paragraph')}}</option>
									<option value="video">{{getPhrase('video')}}</option>
									<option value="audio">{{getPhrase('audio')}}</option>
								</select>

								</fieldset>

								<fieldset class="form-group col-md-6">

								{{ Form::label('searchTerm', getphrase('search_term')) }}

								{{ Form::text('searchTerm', $value = null , $attributes = array('class'=>'form-control', 

						'placeholder' => getPhrase('enter_search_term'),

						'ng-model'=>'searchTerm',
						'id'=>'helper_step5')) }}

								</fieldset>


								<div class="col-md-12" ng-show="contentAvailable">

							<div ng-if="subjectQuestions!=''" class="vertical-scroll" >

								<h4 class="text-success">Questions @{{ subjectQuestions.length }} </h4>
								<table  

								  class="table table-hover">

  									 

									<th >{{getPhrase('subject')}}</th>

									<th>{{getPhrase('question')}}</th>

									<th>{{getPhrase('difficulty')}}</th>

									<th>{{getPhrase('type')}}</th>

									<th>{{getPhrase('marks')}}</th>	

									<th>{{getPhrase('action')}}</th>	


									<tr ng-repeat="question in subjectQuestions | filter: { difficulty_level:difficulty, question_type:question_type} | filter: searchTerm track by $index ">
										 

										<td>@{{subject.subject_title}}</td>

										<td

										title="@{{subjectQuestions[$index].question | removeHTMLTags}}" >

										@{{question.question | removeHTMLTags}}

										</td>

										
										<td>@{{question.difficulty_level | uppercase}}</td>

										<td>@{{question.question_type | uppercase}}</td>

										<td>@{{question.marks}}</td>

										<td><a 
										 

										ng-click="addQuestion(question, subject);" class="btn btn-primary" >{{getPhrase('add')}}</a>

									  		

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

@include('exams.quiz.scripts.js-scripts',array('settings'=>$settings,'record' => $record))

@stop


@section('custom_div_end')

 </div>

@stop