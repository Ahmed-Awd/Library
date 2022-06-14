@extends(getLayout())
@section('header_scripts')
    <link href="{{CSS}}ajax-datatables.css" rel="stylesheet">
    <link href="{{CSS}}sweet-alert.css" rel="stylesheet">
@stop
@section('content')

    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a></li>
                        <li class="active">{{isset($title) ? $title : ''}}</li>
                    </ol>
                </div>

            </div>

            <div class="panel panel-custom">
                <div class="panel-heading">
                    <div class="pull-right messages-buttons">
                        <a href="{{URL_BRANCHES_CREATE}}" class="btn  btn-primary button helper_step1" >{{ getPhrase('create')}}</a>
                    </div>
                    <h1>{{ $title }}</h1>
                </div>
                <div class="panel-body packages">
                    <div>
                        <table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>{{ getPhrase('id')}}</th>
                                <th>{{ getPhrase('name')}}</th>
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
@section('footer_scripts')
    @include('common.datatables', array('route'=>URL_BRANCHES_DATABLE, 'route_as_url' => TRUE))
    @include('common.deletescript', array('route'=>URL_BRANCHES_DELETE))
@stop