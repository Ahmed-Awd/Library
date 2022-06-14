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
                        <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>

                                <th>{{ getPhrase('language')}}</th>
                                <th>{{ getPhrase('code')}}</th>
                                <th>Is RTL</th>
                                <th id="helper_step2">{{ getPhrase('default_language')}}</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($languages as $lang)
                                <tr>
                                    <td>{{$lang->language}}</td>
                                    <td>{{$lang->code}}</td>
                                    @if($lang->is_rtl == 1)
                                        <td><i class="fa fa-check text-success" title="{{getPhrase('enable')}}"></i></td>
                                    @else
                                        <td><i class="fa fa-close text-danger" title="{{getPhrase('disable')}}"></i></td>
                                    @endif
                                    <td>
                                        @if($lang->id == $default_lang)
                                            <i class="fa fa-check text-success" title="{{getPhrase('enable')}}"></i>
                                        @else
                                            <a href="{{URL_LANGUAGES_USER_MAKE_DEFAULT}}{{$lang->id}}" class="btn btn-info btn-xs">{{getPhrase('set_default')}}</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection


@section('footer_scripts')

    @include('common.datatables', array('route'=>'languages.dataTable'))
    @include('common.deletescript', array('route'=>URL_LANGUAGES_DELETE))

@stop