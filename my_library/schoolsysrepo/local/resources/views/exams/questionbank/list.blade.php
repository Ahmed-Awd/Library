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
							<li><a  href="{{URL_EXAMS_DASHBOARD}}">{{ getPhrase('exams_dashboard')}}</a></li>
							<li>{{ $title }}</li>
						</ol>
					</div>
				</div>
								
				<!-- /.row -->
				<div class="panel panel-custom" ng-controller="mainList">
					<div class="panel-heading">
						
						<div class="pull-right messages-buttons">
							 @if($is_staff)
							<a href="{{URL_QUESTIONBAMK_IMPORT}}" class="btn  btn-primary button helper_step2" >{{ getPhrase('import_questions')}}</a>
								<a href="{{URL_QUESTIONBANK_ADD_QUESTION}}" class="btn  btn-primary button" >{{ getPhrase('create_question')}}</a>
							 @endif
						</div>
						<h1>{{ $title }}</h1>
					</div>
					<div class="panel-body packages">
						<div>
							<div class="row">
								<fieldset class="form-group col-md-6">
									<label for="">{{getPhrase('academic_year')}}</label>
									<span class="text-red">*</span>
									<select name="year_id" class="form-control"  required="required" ng-model="current_year_sc" ng-change="get_sems()">
										<option  ng-repeat="year in academic_years_sc" value="@{{ year.id }}">@{{ year.academic_year_title }}</option>
									</select>
								</fieldset>
								<fieldset class="form-group col-md-6">
									<label for="">{{getPhrase('Semester')}}</label>
									<span class="text-red">*</span>
									<select name="sem_id" class="form-control" required="required" ng-model="current_sem_sc" ng-change="getCourses()">
										<option ng-repeat="sem in academic_sems_sc" id="@{{ sem.sem_num }}" value="@{{ sem.sem_num }}"> @{{ sem.title  }}</option>
									</select>
								</fieldset>
							</div>
							<div class="row">
								<fieldset class="form-group col-md-6">
									<label for="">{{getPhrase('branch')}}</label>
									<span class="text-red">*</span>
									<select name="course_id" class="form-control" required="required" ng-model="current_course_sc" ng-change="getSubjects()">
										<option ng-repeat="course in academic_courses_sc" value="@{{ course.id }}">@{{ course.course_title }}</option>
									</select>
								</fieldset>
								<fieldset class="form-group col-md-6">
									<label for="">{{getPhrase('subject')}}</label>
									<span class="text-red">*</span>
									<select name="subject_id" class="form-control" required="required" ng-model="current_subject_sc">
										<option ng-repeat="subject in academic_subjects_sc" value="@{{ subject.subject_id }}">@{{ subject.subject_title }}</option>
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
 @include('exams.questionbank.scripts.list-script')

@stop
