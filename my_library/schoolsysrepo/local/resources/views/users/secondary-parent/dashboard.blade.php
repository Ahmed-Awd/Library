@extends('layouts.staff.stafflayout')
@section('header_scripts')
    {!! Charts::assets() !!}
@stop

@section('content')

        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <ol class="breadcrumb">

                            <li><i class="fa fa-home"></i> {{ $title}}</li>
                        </ol>
                    </div>
                </div>

                <div class="row">


                    <div class="col-md-6 ">
                        <div class="card card-yellow text-xs-center">
                            <div class="card-block">
                                <h4 class="card-title">
                                    <i class="fa fa-users"></i>
                                </h4>
                                <p class="card-text">{{ getPhrase('allocated_students') }}</p>
                            </div>
                            <a class="card-footer text-muted"
                               href="{{url('secondary-parent/student')}}">
                                {{ getPhrase('view_all')}}
                            </a>
                        </div>
                    </div>

                    <div class="col-md-6">
         						<div class="card card-orange text-xs-center helper_step3">
         							<div class="card-block">
         							<h4 class="card-title">
         							<i class="fa fa-assistive-listening-systems"></i>
         							</h4>
         								<p class="card-text">{{ getPhrase('Automatic_call')}}</p>
         							</div>
         							<a class="card-footer text-muted"
         							href="{{url('parent/autocall')}}">
         								{{ getPhrase('view_all')}}
         							</a>
         						</div>
         					</div>
            <!-- /#page-wrapper -->
        </div>
@stop

@section('footer_scripts')


@stop
