@extends($layout)

<?php
$data =  \App\Settings::get_message_extensions();
$extn = $data->value;

?>
@include('messaging-system.script')

<style>
    #progressbar {
        background-color: black;
        border-radius: 13px; /* (height of inner div) / 2 + padding */
        padding: 3px;
        margin-top: 3%;
    }

    #progressbar_2 {
        background-color: orange;
        width: 0%; /* Adjust with JavaScript */
        height: 20px;
        border-radius: 10px;
    }
</style>

<link rel="stylesheet" type="text/css" href="{{CSS}}select2.css">
@section('content')
<div id="page-wrapper" ng-controller="homeworkCtrl">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <ol class="breadcrumb">
                            <li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
                            <li><a href="{{URL_MESSAGES}}">{{getPhrase('send_messages')}}</a> </li>

                            <li class="active"> {{ $title }} </li>
                        </ol>
                    </div>
                </div>
<!-- <h1>Create a new message</h1> -->
<div class="panel panel-custom">
                    <div class="panel-heading">
                    <div class="pull-right messages-buttons">
                            <a class="btn btn-lg btn-info button" href="{{URL_MESSAGES}}"> {{getPhrase('inbox').'('.$messages_count.')'}} </a>
                            <a class="btn btn-lg btn-info button" href="{{URL_MESSAGES_CREATE}}">
                            {{getPhrase('compose')}}</a>


                        </div>
                        <h1>{{$title}}</h1>
                    </div>

                    <div class="panel-body packages">

                        <div class="row library-items">

{!! Form::open(['route' => 'messages.store']) !!}
<div class="col-md-6 col-md-offset-3">
<?php $tosentUsers = array(); ?>
 @if($users->count() > 0)

        <?php foreach($users as $user) {
                $tosentUsers[$user->id] = $user->name;
            }
        ?>
     {!! Form::label('select user', getphrase('select_user'), ['class' => 'control-label']) !!}
    {{Form::select('recipients[]', $tosentUsers, null, ['class'=>'form-control select2', 'name'=>'recipients[]', 'multiple'=>'true'])}}

 @endif


    <!-- Subject Form Input -->
    <div class="form-group">
        {!! Form::label(getphrase('message_subject'), getphrase('message_subject'), ['class' => 'control-label']) !!}
        {!! Form::text('subject', null, ['class' => 'form-control']) !!}
    </div>

    <!-- Message Form Input -->
    <div class="form-group">
        {!! Form::label(getphrase('message_description'), getphrase('message_description'), ['class' => 'control-label']) !!}
        {!! Form::textarea('message', null, ['class' => 'form-control']) !!}
    </div>


    <div class="row">
        <div class="col-md-8">
            <fieldset class="form-group col-md-8">
                {{--<div ng-if="!file_name">--}}
                <input class="form-control" id="upload1" type="file" accept="{{$extn}}" multiple ng-model="upload1" ngf-select="upload_file($files)">
                <span style="color: red" ng-if="valid == 'no'"> @{{ massage }}</span>
                <span style="color: green" ng-if="valid == 'ok'"> @{{ massage }}</span>
                {{getPhrase('supported_formats_are')}} {{$extn}}
                {{--</div>--}}
                <input type="hidden" name="question_file" ng-model="file_name" value="@{{file_name}}">
                <span ng-if="file_name"><p style="color: green">@{{ file_name.length  }} {{getPhrase('files_uploaded')}}</p></span>
                <span ng-if="file_name"><p ng-repeat="file in file_name">@{{file}}</p></span>
                <a    ng-if="file_name" class="btn btn-danger" ng-click="deleteFile()">{{getPhrase('delete')}}</a>
                <div id="progressbar" style="display: none">
                    <div id="progressbar_2"></div>
                </div>
            </fieldset>
        </div>

    </div>
    <!-- Submit Form Input -->
    <div class="text-right">
        <input type="submit" ng-disabled="bupload == false" value="{{getPhrase('send')}}" class="btn-lg btn btn-primary" style="float: left">
    </div>
</div>
{!! Form::close() !!}
  </div>
                </div>
            </div>

</div>
</div>
@stop

@section('footer_scripts')

    <script src="{{JS}}select2.js"></script>

    <script>
      $('.select2').select2({
       placeholder: "{{getphrase('add_user')}}",
    });
    </script>
@stop
