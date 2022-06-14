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

				 <div class="row">
					<div class="col-md-3 ">
						<div class="card card-blue text-xs-center helper_step1">
							<div class="card-block">
					  <h4 class="card-title">
					  <?php $ownerObject =  App\User::withoutGlobalScope(\App\Scopes\BranchScope::class)->where('role_id','=',1)->get()->count();

							 ?>
						   {{$ownerObject}}
					  </h4>

								<p class="card-text">{{ getPhrase('owners')}}</p>
							</div>
							<a class="card-footer text-muted"
							href="{{URL_USERS."owner"}}">
								{{ getPhrase('view_all')}}
							</a>
						</div>
					</div>
					<div class="col-md-3 ">
						<div class="card card-green text-xs-center helper_step2">
							<div class="card-block">
					  <h4 class="card-title">
					  	<h4 class="card-title">
								<?php $adminObject =  App\User::where('role_id','=',2)
								/*->where('users.category_id','=',Auth::user()->category_id)*/->get()->count();

							 ?>
						   {{$adminObject}}
					  </h4>

								<p class="card-text">{{ getPhrase('admins')}}</p>
							</div>
							<a class="card-footer text-muted"
							href="{{URL_USERS."admin"}}">
								{{ getPhrase('view_all')}}
							</a>
						</div>
					</div>
                   <div class="col-md-3 ">
						<div class="card card-red text-xs-center helper_step3">
							<div class="card-block">
							<h4 class="card-title">
                       {{ $students_count =  \App\User::join('roles', 'users.role_id', '=', 'roles.id')
                		->where('users.category_id','=',Auth::user()->category_id)->where('roles.id', '=', 5)->get()->count()}}

							</h4>
								<p class="card-text">{{ getPhrase('students')}}</p>
							</div>
							<a class="card-footer text-muted"
							href="{{URL_USERS."student"}}">
								{{ getPhrase('view_all')}}
							</a>
						</div>
					</div>
					 <div class="col-md-3 ">
						<div class="card card-orange text-xs-center helper_step4">
							<div class="card-block">
								
							<h4 class="card-title"><?php $staffObject =  App\User::join('staff','staff.user_id','=','users.id')->where('staff.course_id','!=','')
							->where('role_id','=',3)
							//->where('users.category_id','=',Auth::user()->category_id)
							->where('status','!=',0)->get()->count();

							 ?>
						   {{$staffObject}}
							</h4>
								<p class="card-text">{{ getPhrase('staff')}}</p>
							</div>
							<a class="card-footer text-muted"
							href="{{URL_USERS."staff"}}">
								{{ getPhrase('view_all')}}
							</a>
						</div>
					</div>
					 <div class="col-md-3 ">
						 <div class="card card-green text-xs-center helper_step4">
							 <div class="card-block">
								 <h4 class="card-title"><?php $staffObject2 =  App\User::where('role_id','=',9)
								 /*->where('users.category_id','=',Auth::user()->category_id)*/->get()->count();
                                     ?>
									 {{$staffObject2}}
								 </h4>
								 <p class="card-text">{{ getPhrase('educational_supervisors')}}</p>
							 </div>
							 <a class="card-footer text-muted"
								href="{{URL_USERS."educational_supervisor"}}">
								 {{ getPhrase('view_all')}}
							 </a>
						 </div>
					 </div>
					 <div class="col-md-3 ">
						 <div class="card card-green text-xs-center helper_step4">
							 <div class="card-block">
								 <h4 class="card-title"><?php $staffObject3 =  App\User::where('role_id','=',11)
								 /*->where('users.category_id','=',Auth::user()->category_id)*/->get()->count();
                                     ?>
									 {{$staffObject3}}
								 </h4>
								 <p class="card-text">{{ getPhrase('student_guide')}}</p>
							 </div>
							 <a class="card-footer text-muted"
								href="{{URL_USERS."student_guide"}}">
								 {{ getPhrase('view_all')}}
							 </a>
						 </div>
					 </div>
					<div class="col-md-3 ">
						<div class="card card-red text-xs-center helper_step5">
							<div class="card-block">
							<h4 class="card-title">
							<?php $librarianObject =  App\User::where('role_id','=',7)
							->get()->count();

							 ?>
						   {{$librarianObject}}
							</h4>
								<p class="card-text">{{ getPhrase('librarians')}}</p>
							</div>
							<a class="card-footer text-muted"
							href="{{URL_USERS."librarian"}}">
								{{ getPhrase('view_all')}}
							</a>
						</div>
					</div>
					<div class="col-md-3 ">
						<div class="card card-yellow text-xs-center helper_step6">
							<div class="card-block">
							<h4 class="card-title">
								<?php $assistantlibrarianObject =  App\User::where('role_id','=',8)
								/*->where('users.category_id','=',Auth::user()->category_id)*/->get()->count();

							 ?>
						   {{$assistantlibrarianObject}}

							</h4>
								<p class="card-text">{{ getPhrase('assistant_librarians')}}</p>
							</div>
							<a class="card-footer text-muted"
							href="{{URL_USERS."assistant_librarian"}}">
								{{ getPhrase('view_all')}}
							</a>
						</div>
					</div>

					<div class="col-md-3 ">
						<div class="card card-blue text-xs-center helper_step7">
							<div class="card-block">
							<h4 class="card-title">
								<?php $parentObject =  App\User::withoutGlobalScope(\App\Scopes\BranchScope::class)->where('role_id','=',6)->get()->count();

							 ?>
						   {{$parentObject}}

							</h4>

								<p class="card-text">{{ getPhrase('parents')}}</p>
							</div>
							<a class="card-footer text-muted"
							href="{{URL_USERS."parent"}}">
								{{ getPhrase('view_all')}}
							</a>
						</div>
					</div>

					<div class="col-md-3 ">
						<div class="card card-yellow text-xs-center helper_step7">
							<div class="card-block">
							<h4 class="card-title">
								<?php $secondaryParentsObject =  App\User::withoutGlobalScope(\App\Scopes\BranchScope::class)->where('role_id','=',10)->get()->count();

							 ?>
						   {{$secondaryParentsObject}}

							</h4>

								<p class="card-text">{{ getPhrase('Secondary_parents')}}</p>
							</div>
							<a class="card-footer text-muted"
							href="{{URL_USERS."secondary_parent"}}">
								{{ getPhrase('view_all')}}
							</a>
						</div>
					</div>

					<div class="col-md-3 ">
						<div class="card card-green text-xs-center helper_step8">
							<div class="card-block">
							<h4 class="card-title">{{ $staffObject+$staffObject2+$staffObject3+$adminObject+$ownerObject+$librarianObject+$assistantlibrarianObject+$parentObject+$secondaryParentsObject+$students_count }}</h4>

								<p class="card-text">{{ getPhrase('all_users')}}</p>
							</div>
							<a class="card-footer text-muted"
							href="{{URL_USERS."users"}}">
								{{ getPhrase('view_all')}}
							</a>
						</div>
					</div>

					<div class="col-md-3 ">
						<div class="card card-green text-xs-center helper_step9">
							<div class="card-block">
							<h4 class="card-title">
								<?php $staff_inactive_listobject = App\User::where('status','=',0)
								->where('role_id','=',3)->get()->count();
								  ?>

								{{$staff_inactive_listobject}}
							</h4>

								<p class="card-text">{{ getPhrase('staff_inactive_list')}}</p>
							</div>
							<a class="card-footer text-muted"
							href="{{URL_USERS_STAFF_INACTIVE."staff"}}">
								{{ getPhrase('view_all')}}
							</a>
						</div>
					</div>
                   @if(count($academic_details)&&count($course_details))
						<div class="col-md-3 ">
						<div class="card card-black text-xs-center helper_step10">
							<div class="card-block">
							<h4 class="card-title">
                             <i class="fa fa-user-plus" aria-hidden="true"></i>
                             {{ getPhrase('create_user')}}
                             </h4>


								<p class="card-text">&nbsp;</p>
							</div>
						<a class="card-footer text-muted"
							href="{{URL_USERS_ADD}}">
								{{ getPhrase('create')}}
							</a>

						</div>
					</div>
                    @endif

                     @if(!count($academic_details)||!count($course_details))

					<div class="col-md-3 ">
						<div class="card card-black text-xs-center helper_step10">
							<div class="card-block">
							<h4 class="card-title">
                             <i class="fa fa-user-plus" aria-hidden="true"></i>
                             {{ getPhrase('create_user')}}                         					</h4>


								<p class="card-text">&nbsp;</p>
							</div>
						<a class="card-footer text-muted"
							href="javascript:void(0);"  onclick="showMessage()">
								{{ getPhrase('create')}}
							</a>

						</div>
					</div>
                     @endif
 <div id="myUserModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><b>{{getPhrase('update_master_setup')}}</b></h4>
      </div>
      <div class="modal-body">

        <h4 style="color: #ffa616;" >{{getPhrase('please_update_master_setup_details')}}</h4 textalign="center">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">{{getPhrase('ok')}}</button>
      </div>

    </div>

  </div>
</div>

		<!-- /#page-wrapper -->

@stop

@section('footer_scripts')
<script >
 	 function showMessage(){

 			$('#myUserModal').modal('show');
 		}

 </script>
@stop
