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
                        <li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a></li>

                        <li><a href="{{url('/academicoperations/dashboard')}}">{{ getPhrase('academics_operations')}}</a></li>
                        <li><a href="">{{ getPhrase('all_skills')}}</a></li>
                    </ol>
                </div>
            </div>
            <div class="panel panel-custom">
                <div class="panel-heading">

                    <div class="pull-right messages-buttons helper_step1">

                        <a href="{{url('/mastersettings/skills/create')}}" class="btn  btn-primary button" >{{ getPhrase('create')}}</a>

                    </div>
                    <h1>{{ $title }}</h1>
                </div>

                <div class="panel-body packages">
                    <div>
                        <table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th id="helper_step2">{{getPhrase('skill_name')}}</th>
                                <th>{{getPhrase('academic_year')}}</th>
                                <th>{{getPhrase('semester')}}</th>
                                <th>{{getPhrase('branch')}}</th>
                                <th>{{getPhrase('subject')}}</th>
                                <th id="helper_step3">{{getPhrase('action')}}</th>
                            </tr>
                            </thead>

                        </table>


                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('footer_scripts')
    @include('common.datatables', array('route'=>'skills.dataTable'))
    @include('Skills.deletescript', array('route'=>URL_MASTERSETTINGS_SKILLS_DELETE))
@stop
