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
                    <h1>{{ $title }}</h1>
                </div>
                <div class="panel-body packages">
                    <div>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>{{getPhrase('name')}}</th>
                                    <th>{{getPhrase('created_at')}}</th>
                                    <th>{{getPhrase('updated_at')}}</th>
                                    <th>{{getPhrase('updated_by_ip')}}</th>
                                    <th>{{getPhrase('created_by_ip')}}</th>
                                    <th>{{getPhrase('created_by_user')}}</th>
                                    <th>{{getPhrase('updated_by_user')}}</th>
                                    <th>{{getPhrase('record_status')}}</th>
                                    <th>{{getPhrase('table_name')}}</th>
                                    <th>{{getPhrase('branch_id')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tables as $table)
                                    <tr>
                                        <td>{{$table->title}}</td>
                                        <td>
                                        @if($table->created_at == true)
                                            <i class="fa fa-check text-success"></i>
                                        @else
                                            <i class="fa fa-close text-danger"></i>
                                        @endif
                                        </td>
                                        <td>
                                            @if($table->updated_at == true)
                                                <i class="fa fa-check text-success"></i>
                                            @else
                                                <i class="fa fa-close text-danger"></i>
                                            @endif
                                        </td>
                                        <td>
                                            @if($table->updated_by_ip == true)
                                                <i class="fa fa-check text-success"></i>
                                            @else
                                                <i class="fa fa-close text-danger"></i>
                                            @endif
                                        </td>
                                        <td>
                                            @if($table->created_by_ip == true)
                                                <i class="fa fa-check text-success"></i>
                                            @else
                                                <i class="fa fa-close text-danger"></i>
                                            @endif
                                        </td>
                                        <td>
                                            @if($table->created_by_user == true)
                                                <i class="fa fa-check text-success"></i>
                                            @else
                                                <i class="fa fa-close text-danger"></i>
                                            @endif
                                        </td>
                                        <td>
                                            @if($table->updated_by_user == true)
                                                <i class="fa fa-check text-success"></i>
                                            @else
                                                <i class="fa fa-close text-danger"></i>
                                            @endif
                                        </td>
                                        <td>
                                            @if($table->record_status == true)
                                                <i class="fa fa-check text-success"></i>
                                            @else
                                                <i class="fa fa-close text-danger"></i>
                                            @endif
                                        </td>
                                        <td>
                                            @if($table->table_name == true)
                                                <i class="fa fa-check text-success"></i>
                                            @else
                                                <i class="fa fa-close text-danger"></i>
                                            @endif
                                        </td>
                                        <td>
                                            @if($table->branch_id == true)
                                                <i class="fa fa-check text-success"></i>
                                            @else
                                                <i class="fa fa-close text-danger"></i>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>


                        </table>
                    </div>
                    <div class="buttons text-center">

                        <a class="btn btn-lg btn-primary button" href="{{PREFIX}}tables/fix">{{getPhrase('fix_all')}}</a>

                    </div>

                </div>
            </div>

        </div>
    </div>

@endsection
@section('footer_scripts')
@stop