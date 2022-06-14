@extends($layout)

@section('header_scripts')
    <link href="{{CSS}}ajax-datatables.css" rel="stylesheet">
@stop
@section('content')

    <div id="page-wrapper" ng-controller="student_papers">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a></li>
                        <li><a href="{{PREFIX}}users/student">{{ getPhrase('students')}}</a></li>
                        <li class="active">{{isset($title) ? $title : ''}}</li>
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
                                <th>{{ getPhrase('name')}}</th>
                                <th>{{ getPhrase('type')}}</th>
                                <th>{{ getPhrase('file')}}</th>
                                <th>{{ getPhrase('action')}}</th>
                            </tr>
                            </thead>

                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>

@endsection
@include('student.papers.script')
@section('footer_scripts')
    @include('common.datatables', array('route'=>URL_STUDENT_PAPER_LIST.$user->slug, 'route_as_url' => TRUE))
@stop