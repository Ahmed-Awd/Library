@extends($layout)

@section('content')
	<style>
		#progressbar {
			background-color: black;
			border-radius: 13px; /* (height of inner div) / 2 + padding */
			padding: 3px;
			margin-top: 3%;
		}

		#progressbar_2 {
			background-color: orange;
			width: 0%; /* Adjust with JavaScript */
			height: 20px;
			border-radius: 10px;
		}
	</style>

    <?php
    $data =  \App\Settings::get_feedback_extensions();
    $extn = $data->value;

    ?>
<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>

							<li class="active">{{getPhrase('feedback_form')}}</li>
						</ol>
					</div>
				</div>
					@include('errors.errors')
				<!-- /.row -->

<div class="panel panel-custom col-lg-6 col-lg-offset-3" ng-controller="feedbackCtrl">
					<div class="panel-heading">

					<h1>{{ $title }}  </h1>
					</div>
					<div class="panel-body" >
					<?php $button_name = getPhrase('send'); ?>

					{!! Form::open(array('url' => URL_FEEDBACK_SEND, 'method' => 'POST', 'name'=>'formQuiz ', 'novalidate'=>'')) !!}
					<div class="row">
 					 <fieldset class="form-group col-md-12">

						{{ Form::label('title', getphrase('title')) }}
						<span class="text-red">*</span>
						{{ Form::text('title', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('title'),
							'ng-model'=>'title',
							'ng-pattern'=>getRegexPattern('name'),
							'required'=> 'true',
							'ng-class'=>'{"has-error": formQuiz.title.$touched && formQuiz.title.$invalid}',
							'ng-minlength' => '4',
							'ng-maxlength' => '45',
							)) }}
						<div class="validation-error" ng-messages="formQuiz.title.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('pattern')!!}
	    					{!! getValidationMessage('minlength')!!}
	    					{!! getValidationMessage('maxlength')!!}
						</div>
					</fieldset>
					</div>

					<div class="row">
					<fieldset class="form-group col-md-12">

						{{ Form::label('subject', getphrase('feedback_subject')) }}
						<span class="text-red">*</span>
						{{ Form::text('subject', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('feedback_subject'),
							'ng-model'=>'subject',
							'required'=> 'true',
							'ng-class'=>'{"has-error": formQuiz.subject.$touched && formQuiz.subject.$invalid}',
							'ng-minlength' => '2',
							'ng-maxlength' => '40',
							)) }}
						<div class="validation-error" ng-messages="formQuiz.subject.$error" >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('pattern')!!}
	    					{!! getValidationMessage('minlength')!!}
	    					{!! getValidationMessage('maxlength')!!}
						</div>
					</fieldset>
				 </div>

					<div class="row">
					 <fieldset class="form-group col-md-12">
					 {{ Form::label('description', getphrase('feedback_description')) }}
						<span class="text-red">*</span>
							 <textarea name="description" ng-model="description"
							 required="true" class='form-control' rows="5"></textarea>
						<div class="validation-error ckeditor" ng-messages="formQuiz.description.$error"  >
	    					{!! getValidationMessage()!!}
	    					{!! getValidationMessage('number')!!}
						</div>
					</fieldset>
					</div>
						<div class="row">
							<div class="col-md-8">
								<fieldset class="form-group col-md-8">
									{{--<div ng-if="!file_name">--}}
									<input class="form-control" id="upload1" type="file" accept="{{$extn}}" multiple ng-model="upload1" ngf-select="upload_file($files)">
									<span style="color: red" ng-if="valid == 'no'"> @{{ massage }}</span>
									<span style="color: green" ng-if="valid == 'ok'"> @{{ massage }}</span>
									{{getPhrase('supported_formats_are')}} {{$extn}}
									{{--</div>--}}
									<input type="hidden" name="question_file" ng-model="file_name" value="@{{file_name}}">
									<span ng-if="file_name"><p style="color: green">@{{ file_name.length  }} {{getPhrase('files_uploaded')}}</p></span>
									<span ng-if="file_name"><p ng-repeat="file in file_name">@{{file}}</p></span>
									<a    ng-if="file_name" class="btn btn-danger" ng-click="deleteFile()">{{getPhrase('delete')}}</a>
									<div id="progressbar" style="display: none;width: 250%;">
										<div id="progressbar_2"></div>
									</div>
								</fieldset>
							</div>

						</div>
						<div class="buttons text-center">
							<button class="btn btn-lg btn-primary button"
							ng-disabled='!formQuiz.$valid || bupload == false'>{{ $button_name }}</button>
						</div>
					{!! Form::close() !!}
					</div>

				</div>
			</div>
			<!-- /.container-fluid -->
		</div>
		<!-- /#page-wrapper -->
@stop

@section('footer_scripts')
@include('feedbacks.script')


@stop
