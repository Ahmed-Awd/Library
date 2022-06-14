@extends($layout)
@section('header_scripts')

@stop
@section('content')


	<style>
		input[type="radio"], input[type="checkbox"] {
			display: inherit;
		}
	</style>

<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
							<li><a href="{{URL_LANGUAGES_LIST}}">{{ getPhrase('languages')}}</a> </li>
							<li>{{ $title }}</li>
						</ol>
					</div>
				</div>
					<?php $language_data = json_decode($record->phrases);?>
				<!-- /.row -->
				<div class="panel panel-custom">
					<div class="panel-heading">

						<h1>{{ $title }}</h1>
					</div>
					<div class="panel-body packages">
					{!! Form::open(array('url' => URL_LANGUAGES_UPDATE_STRINGS.$record->slug, 'method' => 'PATCH',
						'novalidate'=>'','name'=>'formSettings ', 'files'=>'true')) !!}
						<div class="table-responsive">
						<ul class="list-group">
						@if(count($language_data))
						@foreach($language_data as $key=>$value)
					 <div class="col-md-4">
						<fieldset class="form-group">
						  <pre><span><input type="checkbox" name="to_delete[{{$key}}]" value="{{$value}}"></span> {{ Form::label($key, $key) }}</pre> <br>
						  <div class="form-control" id="div_{{$key}}" onclick="$('#{{$key}}').removeAttr('disabled');$('#{{$key}}').show();$('#'+this.id).hide(); " >{{$value}}</div>
						   <input  style="display:none;" type="text" class="form-control" name="{{$key}}" id="{{$key}}"	required="true" value = "{{$value}}" disabled>
							</fieldset>
							</div>

						  @endforeach

						  @else
							  <li class="list-group-item">{{ getPhrase('no_settings_available')}}</li>
						  @endif
						</ul>

						</div>

						@if(count($language_data))
						<div class="buttons text-center">
							<button class="btn btn-lg btn-primary button" ng-disabled='!formTopics.$valid'
							>{{ getPhrase('update') }}</button>
							<button class="btn btn-lg btn-warning button">{{ getPhrase('delete') }}</button>
						</div>

						@endif
							{!! Form::close() !!}
					</div>
				</div>
			</div>
			<!-- /.container-fluid -->
		</div>
@endsection


@section('footer_scripts')

@stop
