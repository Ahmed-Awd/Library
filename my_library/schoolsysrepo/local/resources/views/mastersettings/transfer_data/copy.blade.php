@extends($layout)

@section('header_scripts')
	<link href="{{CSS}}animate.css" rel="stylesheet">
@stop

@section('custom_div')
	<div ng-controller="transferDataController">
		@stop

		@section('content')
			<div id="page-wrapper">
				<div class="container-fluid">
					<!-- Page Heading -->
					<div class="row">
						<div class="col-lg-12">
							<ol class="breadcrumb">
								<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a></li>
								<li><a href="{{URL_COURSES_DASHBOARD}}">{{ getPhrase('master_setup_dashboard')}}</a>
								</li>
								<li class="active">{{isset($title) ? $title : ''}}</li>
							</ol>
						</div>
					</div>
				@include('errors.errors')
				<!-- /.row -->

					<div class="panel panel-custom">
						<div class="panel-heading">
							<h1>{{ $title }}  </h1>
						</div>
						<div class="panel-body" ng-controller="transferDataController">

              <fieldset class="form-group col-md-6">
                  {{ Form::label('from_academic_year', getphrase('from_academic_year')) }}

                  {{Form::select('from_academic_id', $academic_years, null,
                  [   'class'     => 'form-control',
                      "id"        => "select_academic_year",
                      "ng-model"  => "from_academic_year",
                      "ng-change" => "from_year()",
                      'required' => 'required'
                  ])}}
              </fieldset>

              <fieldset class="form-group col-md-6">
                  {{ Form::label('to_academic_year', getphrase('to_academic_year')) }}

                  {{Form::select('to_academic_id', $academic_years, null,
                  [   'class'     => 'form-control',
                      "id"        => "select_academic_year",
                      "ng-model"  => "to_academic_year",
                      "ng-change" => "to_year()",
                      'required' => 'required'
                  ])}}
              </fieldset>

              <button ng-click="transferData()" class="btn btn-lg btn-primary button">{{ getPhrase('Transfer_data') }}</button>

						</div>
					</div>
				</div>
				<!-- /.container-fluid -->
			</div>
			<!-- /#page-wrapper -->
		@stop


		@section('footer_scripts')
			<script src="{{JS}}angular.js"></script>
			@include('mastersettings.transfer_data.js-scripts')
			@include('common.alertify')
		@stop

		@section('custom_div_end')
	</div>
@stop
