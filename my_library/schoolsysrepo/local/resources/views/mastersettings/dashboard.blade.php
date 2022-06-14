@extends($layout)
@section('content')

<div id="page-wrapper">
			<div class="container-fluid">
			<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							 <li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
							 <li>{{ $title}}</li>
						</ol>
					</div>
				</div>

				<div class="col-md-3">
					<div class="card card-red text-xs-center helper_step1">
						<div class="card-block">
							<h4 class="card-title">
								<i class="fa fa-cogs" aria-hidden="true"></i>
							</h4>
							<p class="card-text">{{ getPhrase('master_setup')}}</p>
						</div>
						<a class="card-footer text-muted" href="{{URL_COURSES_DASHBOARD}}">
							{{ getPhrase('view_all')}}
						</a>
					</div>
				</div>
					<div class="col-md-3">
						<div class="card card-blue text-xs-center">
							<div class="card-block">
					  <h4 class="card-title">
					  <i class="fa fa-cog"></i>
					  </h4>

								<p class="card-text">{{ getPhrase('settings')}}</p>
							</div>
							<a class="card-footer text-muted"
							href="{{URL_MASTERSETTINGS_SETTINGS}}">
								{{ getPhrase('view_all')}}
							</a>
						</div>
					</div>

                     <?php
                     $settings = \App\Settings::where('key', 'module')->first(['settings_data']);
                     $settings = json_decode($settings->settings_data);
                     ?>
                     @if($settings->certificate->value == 1)
					 <!-- <div class="col-md-3">
						<div class="card card-red text-xs-center">
							<div class="card-block">
							<h4 class="card-title">
							<i class="fa fa-certificate"></i>

							</h4>
								<p class="card-text">{{ getPhrase('certificate_settings')}}</p>
							</div>
							<a class="card-footer text-muted"
							href="{{URL_SETTINGS_CERTIFICATES}}">
								{{ getPhrase('view_all')}}
							</a>
						</div>
					</div> -->
                     @endif


				@if(($settings->coupons->value == 1))
				<!-- <div class="col-md-3">
					<div class="card card-black text-xs-center">
						<div class="card-block">
							<h4 class="card-title">
								<i class="fa fa-hashtag" aria-hidden="true"></i>

							</h4>
							<p class="card-text">{{ getPhrase('coupons')}}</p>
						</div>
						<a class="card-footer text-muted"
						   href="{{URL_COUPONS}}">
							{{ getPhrase('view_all')}}
						</a>
					</div>
				</div> -->
				@endif

					<div class="col-md-3">
						<div class="card card-yellow text-xs-center">
							<div class="card-block">
							<h4 class="card-title">
                                <i class="fa fa-calendar" aria-hidden="true"></i>
							</h4>
								<p class="card-text">{{ getPhrase('time_table_settings')}}</p>
							</div>
							<a class="card-footer text-muted"
							href="{{URL_SETTINGS_TIMETABLE}}">
								{{ getPhrase('view_all')}}
							</a>
						</div>
					</div>
					 <div class="col-md-3">
						<div class="card card-black text-xs-center">
							<div class="card-block">
							<h4 class="card-title">
							 <i class="fa fa-telegram" aria-hidden="true"></i>

							</h4>
								<p class="card-text">{{ getPhrase('email_templates')}}</p>
							</div>
							<a class="card-footer text-muted"
							href="{{URL_MASTERSETTINGS_EMAIL_TEMPLATES}}">
								{{ getPhrase('view_all')}}
							</a>
						</div>
					</div>

@stop

@section('footer_scripts')

@stop
