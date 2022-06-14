@extends($layout)

@section('content')
    <div id="page-wrapper">
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a></li>
                        <li><a href="{{URL_BRANCHES_LIST}}">{{ getPhrase('branches')}}</a> </li>
                        <li class="active">{{isset($title) ? $title : ''}}</li>
                    </ol>
                </div>
            </div>
            @include('errors.errors')
            <div class="panel panel-custom" >
                <div class="panel-heading">
                    <div class="pull-right messages-buttons">
                        <a href="{{URL_BRANCHES_LIST}}" class="btn  btn-primary button" >{{ getPhrase('list')}}</a>
                    </div>
                    <h1>{{ $title }}  </h1>
                </div>
                <div class="panel-body form-auth-style" >
                    <?php $button_name = getPhrase('create'); ?>
                    @if ($record)
                        <?php $button_name = getPhrase('update'); ?>
                        {{ Form::model($record,
                        array('url' => URL_BRANCHES_UPDATE.$record->slug,
                        'method'=>'patch','name'=>'formauthors ', 'novalidate'=>'')) }}
                    @else
                        {!! Form::open(array('url' => URL_BRANCHES_ADD, 'method' => 'POST','name'=>'formauthors ', 'novalidate'=>'')) !!}
                    @endif
                    <div class="row">
                        <fieldset class="form-group col-md-6">

                            {{ Form::label('name', getphrase('name')) }}
                            <span class="text-red">*</span>
                            {{ Form::text('name', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '',
                                'required'=> 'true',
                                'ng-class'=>'{"has-error": formLms.code.$touched && formLms.code.$invalid}',

                            )) }}
                        </fieldset>
                    </div>
                    <div class="row">
                        <div class="buttons text-center">
                            <button class="btn btn-lg btn-primary button"
                                    ng-disabled=''>{{ $button_name }}</button>
                        </div>
                    </div>


                    {!! Form::close() !!}
                </div>

            </div>
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /#page-wrapper -->
@stop
@section('footer_scripts')
@stop
@include('branch.script')