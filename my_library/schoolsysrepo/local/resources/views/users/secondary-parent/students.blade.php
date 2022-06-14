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

                        <li><a href="">{{$title}}</a></li>
                    </ol>
                </div>
            </div>
            <div class="panel panel-custom">
                <div class="panel-heading">

                    <div class="pull-right messages-buttons helper_step2">

                    </div>
                    <h1>{{getphrase('all_students')}}</h1>
                </div>
                <div class="panel-body packages">
                    <div>
                        <table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
                            <thead>
                            <tr>

                                <th id="helper_step2">{{ getPhrase('student_name')}}</th>
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
    @include('common.datatables', array('route'=>'students.dataTable'))
@stop
