@extends($layout)

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
							
							@if(checkRole(getUserGrade(2)))
                       <li><a href="{{URL_USERS_DASHBOARD}}">{{ getPhrase('users_dashboard') }}</a> </li>
                       @endif

                    @if(checkRole(getUserGrade(2)))
					<li><a href="{{URL_USERS."student"}}">{{ getPhrase('student_users') }}</a> </li>
					@endif

					 @if(checkRole(getUserGrade(7)))
                   <li><a href="{{URL_PARENT_CHILDREN}}">{{ getPhrase('children') }}</a> </li>
                   @endif

                       <li>{{ $title }}</li>

						</ol>

					</div>

				</div>

				<div class="panel panel-custom">

					<div class="panel-heading">

						<h1>{{ $title }}</h1>

					</div>

					<div class="panel-body packages">

						<div> 

						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">

							<thead>

								<tr>
								@if($is_parent)
								 <th>{{ getPhrase('image')}}</th>
                                    <th>{{ getPhrase('student_name')}}</th>
                                @endif

									<th>{{ getPhrase('details_of_payment')}}</th>

									<th>{{ getPhrase('plan_type')}}</th>

									<th>{{ getPhrase('paid_amount')}}</th>

									<th>{{ getPhrase('notes')}}</th>

									<th>{{ getPhrase('admin_comments')}}</th>

									<th>{{ getPhrase('date_time')}}</th>

									<th>{{ getPhrase('status')}}</th>

                                     </tr>

							</thead>

						</table>

						</div>

                     </div>

				</div>

			</div>

			<!-- /.container-fluid -->

		</div>

@endsection

@section('footer_scripts')

 @include('common.datatables', array('route'=>URL_PAYPAL_PAYMENTS_AJAXLIST.$user->slug, 'route_as_url' => TRUE))

 @include('common.deletescript', array('route'=>'/exams/quiz/delete/'))

@stop

