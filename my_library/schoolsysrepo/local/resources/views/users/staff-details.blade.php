@extends($layout) 
@section('header_scripts') 
@stop 
@section('content')
<div id="page-wrapper">
	<div class="container-fluid">
		<!-- Page Heading -->
		<div class="row">
			<div class="col-lg-12">
				<ol class="breadcrumb">
					<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i> </a> </li>

					@if(checkRole(getUserGrade(2)))
					<li><a href="{{URL_USERS_DASHBOARD}}">{{getPhrase('users_dashboard')}}</a></li>
					<li><a href="{{URL_USERS." staff "}}">{{ getPhrase('staff_users') }}</a> </li>
					@endif @if(checkRole(getUserGrade(7)))
					<li><a href="{{URL_PARENT_CHILDREN}}">{{ getPhrase('users') }}</a> </li>
					@endif

					<li>{{ $title }} </li>
				</ol>
			</div>

		</div>

		<div class="panel panel-custom">
			<div class="panel-heading">
				<h1>{{ getPhrase('details_of').' '.$record->name }}</h1>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-6 col-md-6">
					 
							<div class="profile-details text-center"> 
								<div class="profile-img"><img src="{{ getProfilePath($record->image,'profile')}}" alt=""></div>
								<div class="aouther-school">
									<h2>{{ $record->name}}</h2>
									<p><span>{{$record->email}}</span></p>
									@if($role_name=="educational_supervisor" || $role_name=="owner")
										<p><span>{{$record->mobile}}</span></p>
										<a href="{{ URL_USERS_SWITCH_STAFF.$record->slug}}">
											<sapn><i class="fa fa-sign-out" aria-hidden="true"></i> {{ getPhrase('switch_to_staff') }}</sapn>
										</a>
									@endif
								</div>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="profile-details ">
									@if($role_name=="educational_supervisor" || $role_name=="owner")
								<table class="table table-bordered">
							 
								@foreach($courses->groupBy('id') as $course)
									<tr>
									<td>{{$course[0]->course_title}}</td>
									
										<td>	@foreach($course  as $c)
										<span style="padding-right:50px;"> {{$c->child_title}}	@endforeach </span>
										</td>
									
									</tr>
								@endforeach
									 
								</table> @endif

							</div>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-lg-3 col-md-6">
							<div class="card card-blue text-xs-center">
								<div class="card-block">
									<h4 class="card-title">
										<i class="fa fa-paper-plane-o"></i></h4>
									<p class="card-text">{{ getPhrase('lesson_plans')}}</p>
								</div>
								<a class="card-footer text-muted" href="{{URL_LESSION_PLANS_DASHBOARD.$record->slug}}">{{ getPhrase('view_details')}}</a>
							</div>
						</div>


						@if(Module_state('library_Management'))
						<div class="col-lg-3 col-md-6">
							<div class="card card-yellow text-xs-center">
								<div class="card-block">
									<h4 class="card-title">
										<i class="fa fa-book"></i></h4>
									<p class="card-text">{{ getPhrase('library_history')}}</p>
								</div>
								<a class="card-footer text-muted" href="{{URL_USER_LIBRARY_DETAILS.$record->slug}}">{{ getPhrase('view_details')}}</a>
							</div>
						</div>
						@endif @if(Module_state('daily_school_schedule'))
						<div class="col-lg-3 col-md-6">
							<div class="card card-green text-xs-center">
								<div class="card-block">
									<h4 class="card-title">
										<i class="fa fa-calendar"></i></h4>
									<p class="card-text">{{ getPhrase('timetable')}}</p>
								</div>
								<a class="card-footer text-muted" href="{{URL_TIMETABLE_STAFF.$record->slug}}">{{ getPhrase('view_details')}}</a>
							</div>
						</div>
						@endif @if($role_name=="educational_supervisor" || $role_name=="owner")
						<div class="col-lg-3 col-md-6">
							<div class="card card-default text-xs-center">
								<div class="card-block">
									<h4 class="card-title">
										<i class="fa fa-calendar"></i></h4>
									<p class="card-text">{{ getPhrase('students')}}</p>
								</div>
								<a class="card-footer text-muted" href="{{URL_LESSION_PLANS_STUDENTLIST_DASHBOARD.$record->slug}}">{{ getPhrase('view_details')}}</a>
							</div>
						</div>
						<div class="col-lg-3 col-md-6">
							<div class="card card-red text-xs-center">
								<div class="card-block">
									<h4 class="card-title"><i class="fa fa-users"></i></h4>
									<p class="card-text">{{ getPhrase('teacher_students_marks') }}</p>
								</div>
								<a class="card-footer text-muted" href="{{url('supervisor/staff/students-marks/'.$record->slug)}}">
											{{ getPhrase('view_all')}}
										</a>
							</div>
						</div>

					 
						<div class="col-lg-3 col-md-6">
							<div class="card card-black text-xs-center helper_step6">
								<div class="card-block">
									<h4 class="card-title">
										<i class="fa fa-file-archive-o" aria-hidden="true"></i>
									</h4>

									<p class="card-text">{{ getPhrase('Attendance_and_departure_report')}}</p>
								</div>
								<a class="card-footer text-muted" href="{{ATTENDANCE_REPORT_VIEW.'/'.$record->slug}}">
											{{ getPhrase('view_all')}}
										</a>
							</div>
						</div>

						<div class="col-lg-3 col-md-6">
							<div class="card card-blue text-xs-center helper_step5">
								<div class="card-block">
									<h4 class="card-title">
										<i class="fa fa-check-square-o">	</i>
									</h4>

									<p class="card-text">{{ getPhrase('class_attendance_report')}}</p>
								</div>
								<a class="card-footer text-muted" href="{{URL_STUDENT_CLASS_ATTENDANCE.'/'.$record->slug}}">
												{{ getPhrase('view_all')}}
											</a>
							</div>
						</div>

						<div class="col-lg-3 col-md-6">
								<div class="card card-green text-xs-center helper_step3">
									<div class="card-block">
									<h4 class="card-title">
								   <i class="fa fa-file" aria-hidden="true"></i>
									</h4>
										<p class="card-text">{{ getPhrase('REPORT_OF_QUIZ_RESULTS')}}</p>
									</div>
									<a class="card-footer text-muted"
									href="{{URL_QUIZ_REPORT_VIEW.'/'.$record->slug}}">
										{{ getPhrase('view_all')}}
									</a>
								</div>
							</div>


						<div class="col-lg-3 col-md-6">
								<div class="card card-green text-xs-center helper_step3">
									<div class="card-block">
									<h4 class="card-title">
								   <i class="fa fa-file" aria-hidden="true"></i>
									</h4>
										<p class="card-text">{{ getPhrase('REPORT_OF_Year_grad')}}</p>
									</div>
									<a class="card-footer text-muted"
									href="{{url('student/year_grades/'.$record->slug.'?status=hide')}}">
										{{ getPhrase('view_all')}}
									</a>
								</div>
							</div>

						@endif
					</div>
				</div>

			</div>


		</div>
	</div>
</div>
<!-- /.container-fluid -->
</div>
@endsection
 
@section('footer_scripts') 
@stop