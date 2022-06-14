
@extends('layouts.site')

@section('content')

<div class="login-content" ng-controller="login">

	<div class="logo text-center"><img src="{{IMAGE_PATH_SETTINGS.getSetting('site_logo', 'site_settings')}}" alt=""  width="150" ></div>
	<div class="men-caption">
		<p>{{getSetting('login_page_title','site_settings')}}</p>
	</div>
	@include('layouts.site-navigation')


	{!! Form::open(array('url' => URL_USERS_LOGIN, 'method' => 'POST', 'name'=>'formLanguage ', 'novalidate'=>'', 'class'=>"loginform", 'name'=>"loginForm")) !!}

	<?php $key = \App\Settings::Social_switch() ?>

	@include('errors.errors')
	<div id="warn_him" style="display:none">
		<ul class="error" ng-if="warning">
			<li style="color:red;">@{{warning}}</li>
		</ul>
	</div>



	<div class="input-group">

		<span class="input-group-addon" id="basic-addon1"><i class="icon icon-user"></i></span>



		{{ Form::text('email', $value = null , $attributes = array('class'=>'form-control',

		'ng-model'=>'email',

		'required'=> 'true',

		'placeholder' => getPhrase('username').'/'.getPhrase('email').'/'.getPhrase('ID_number'),

		'ng-class'=>'{"has-error": loginForm.email.$touched && loginForm.email.$invalid}',

		)) }}

		<div class="validation-error" ng-messages="loginForm.email.$error" >

			{!! getValidationMessage()!!}

			{!! getValidationMessage('email')!!}

		</div>

	</div>

	<div class="input-group">

		<span class="input-group-addon" id="basic-addon1"><i class="icon icon-lock"></i></span>

		{{ Form::password('password', $attributes = array('class'=>'form-control instruction-call',

		'placeholder' => getPhrase("password"),

		'ng-model'=>'registration.password',

		'required'=> 'true',

		'ng-class'=>'{"has-error": loginForm.password.$touched && loginForm.password.$invalid}',

		'ng-minlength' => 5

		)) }}

		<div class="validation-error" ng-messages="loginForm.password.$error" >

			{!! getValidationMessage()!!}

			{!! getValidationMessage('password')!!}

		</div>

	</div>
	<div class="row">
		<div class="text-center buttons ">
			<div class="col-md-5">
				<a href="javascript:void(0);" class="pull-left" data-toggle="modal" data-target="#myModal" ><i class="icon icon-question"></i> {{getPhrase('forgot_password')}}</a>
			</div>
			<div class="col-md-7">
				<!-- <button type="submit" class="btn button btn-primary btn-lg" ng-disabled='!loginForm.$valid'>{{getPhrase('login')}}</button> -->
				<button type="submit" class="btn button btn-primary btn-lg" >{{getPhrase('login')}}</button>
			
				<div class="text-center buttons">

						@if($key['facebook'] == 1)
						<a ng-click="authenticate('facebook')" class="btn btn-block btn-social btn-facebook"><span style="padding-left: 5%;" class="fa fa-facebook" aria-hidden="true"></span>{{getPhrase('Sign_in_with_Facebook')}}</a>
						<br>
						@endif

						@if($key['google'] == 1)
						<a ng-click="authenticate('google')" class="btn btn-block btn-social btn-google-plus"><span style="padding-left: 5%;" class="fa fa-google"></span>{{getPhrase('Sign_in_with_Google')}}</a>
						@endif

				</div>
			</div>

		</div>
	</div>

	{!! Form::close() !!}


</div>



<!-- Modal -->

<div id="myModal" class="modal fade" role="dialog">

	<div class="modal-dialog">

		{!! Form::open(array('url' => URL_FORGOT_PASSWORD, 'method' => 'POST', 'name'=>'formLanguage ', 'novalidate'=>'', 'class'=>"loginform", 'name'=>"passwordForm")) !!}

		<!-- Modal content-->

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal">&times;</button>

				<h4 class="modal-title">{{getPhrase('forgot_password')}}</h4>

			</div>

			<div class="modal-body">

				<div class="input-group">

					<span class="input-group-addon" id="basic-addon1"><i class="icon icon-email-resend"></i></span>



					{{ Form::email('email', $value = null , $attributes = array('class'=>'form-control',

					'ng-model'=>'email',

					'required'=> 'true',

					'placeholder' => getPhrase('email'),

					'ng-class'=>'{"has-error": passwordForm.email.$touched && passwordForm.email.$invalid}',

					)) }}

					<div class="validation-error" ng-messages="passwordForm.email.$error" >

						{!! getValidationMessage()!!}

						{!! getValidationMessage('email')!!}

					</div>



				</div>
				`
			</div>

			<div class="modal-footer">

				<div class="pull-right">

					<button type="button" class="btn btn-default" data-dismiss="modal">{{getPhrase('close')}}</button>

					<button type="submit" class="btn btn-primary" ng-disabled='!passwordForm.$valid'>{{getPhrase('submit')}}</button>

				</div>

			</div>

		</div>

		{!! Form::close() !!}

	</div>

</div>

@stop


@section('footer_scripts')

@include('common.validations')

@stop
