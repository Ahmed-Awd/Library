@extends(getLayout())
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
                        <li><a href="{{url('/')}}"><i class="mdi mdi-home"></i></a> </li>
                        <li>{{ $title }}</li>
                    </ol>
                </div>
            </div>

            <!-- /.row -->
            <div class="panel panel-custom">
                <div class="panel-heading">

                    <h1>{{ $title }}</h1>
                </div>
                <div class="panel-body packages">
                    <div >
                        <table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                            <th>{{getPhrase('student_name')}}</th>
                            <th>{{getPhrase('ID_number')}}</th>
                            <th>{{getPhrase('last_update')}}</th>
                            <th>{{getPhrase('show')}}</th>

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

    @include('common.datatables', array('route'=>PREFIX.'homework/students/datable/'.$homework->slug, 'route_as_url' => 'TRUE'))

@stop
