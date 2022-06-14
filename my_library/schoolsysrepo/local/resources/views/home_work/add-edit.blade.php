@extends(getLayout())
@include('home_work.scripts.script')
@include('common.editor')
@section('content')
    <script type="text/css" src="{{CSS}}angular-toastr.css"></script>
    <div id="page-wrapper" ng-controller="homeworkCtrl">
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a></li>
                        <li><a href="{{URL_HOMEWORK_VIEW}}">{{ getPhrase('Home_Work') }}</a></li>
                        <li>{{ $title }}</li>
                    </ol>
                </div>
            </div>
            <div class="panel panel-custom  col-lg-12">

                <div class="panel-heading">
                    <div class="pull-right messages-buttons">
                        <a href="{{URL_HOMEWORK_VIEW}}" class="btn  btn-primary button">{{ getPhrase('list')}}</a>
                    </div>
                    <h1>{{ $title }}  </h1>
                </div>
                <div class="panel-body form-auth-style" id="app">
                    <?php $button_name = getPhrase('create'); ?>
                    @if (isset($record))
                        <?php $button_name = getPhrase('update'); ?>
                        {{ Form::model($record,
                        array('url' => URL_HOMEWORK_EDIT.'/'.$record->slug,
                        'method'=>'patch', 'files' => true, 'name'=>'formQuestionBank ', 'novalidate'=>'')) }}
                    @else
                        {!! Form::open(array('url' => URL_HOMEWORK_ADD, 'method' => 'POST', 'files' => true, 'name'=>'formQuestionBank ', 'novalidate'=>'')) !!}
                    @endif

                    @include('home_work.form-element',
                    array('button_name'=> $button_name,'record'=>isset($record)?$record:null))

                    {!! Form::close() !!}


                </div>

            </div>
        </div>
    </div>

    @endsection