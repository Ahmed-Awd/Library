@extends(getLayout())
@include('home_work.scripts.script')
@include('common.editor')
@section('header_scripts')

    <link href="{{CSS}}ajax-datatables.css" rel="stylesheet">
@stop
@section('content')

    <?php
    $data =  \App\Settings::get_HW_extensions();
    $extn = $data->value;

    ?>

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
    <div id="page-wrapper" ng-controller="homeworkCtrl">
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

                    <h1>{{ $title }} @if(!is_student()) - {{ getUserName($record->student_id) }} @endif </h1>
                </div>
                <div class="panel-body packages">
                    <div class="row">
                        <span>{!! $homework->explanation !!}</span>
                    </div>
                    @if($homework->file != '')
                    <div class="row">
                        <span>{{$homework->file}}</span>
                        <a class="btn btn-primary" href='{{HOMEWORK_PATH.$homework->file}}' download> {{getPhrase('download')}}</a>
                    </div>
                    @endif
                    <br><br><br>
                    <div class="row">
                        <h2>{{getPhrase('replay')}}</h2>

                        {!! Form::open(array('url' => URL_HOMEWORK_REPLAY.$record->slug, 'method' => 'POST', 'files' => true, 'name'=>'formQuestionBank ', 'novalidate'=>'')) !!}
                        <div class="row">
                            <div class="col-md-8">
                                <fieldset class="form-group col-md-8">
                                    <textarea class="ckeditor" rows="7" cols = "150" name="replay"></textarea>
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <fieldset class="form-group col-md-8">
                                    <input ng-if="!file_name" class="form-control" id="upload1" type="file" accept="{{$extn}}" ng-model="upload1" ngf-select="upload_file($files)">
                                    <span style="color: red" ng-if="valid == 'no'"> @{{ massage }}</span>
                                    <span style="color: green" ng-if="valid == 'ok'"> @{{ massage }}</span>
                                    <span ng-if="!file_name">{{getPhrase('supported_formats_are')}} {{$extn}}</span>
                                    <span ng-if="file_name" style="background-color: yellow;color: green"> @{{ file_name }}</span>
                                    <input type="hidden" name="question_file" ng-model="file_name" value="@{{file_name}}">
                                    <a ng-if="file_name" class="btn btn-danger" ng-click="deleteFile()">{{getPhrase('delete')}}</a>
                                    <div id="progressbar" style="display: none">
                                        <div id="progressbar_2"></div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <fieldset class="form-group col-md-8">
                                    <input type="submit" ng-disabled="bupload == false" value="{{getPhrase('send')}}" class="btn-lg btn btn-google-plus" style="float: left">
                                </fieldset>
                            </div>
                        </div>
                        {!! Form::close() !!}

                        @foreach($replays as $replay)
                    <div class="row">
                        <span><h3>{{$replay->name}} <span style="color: blue">({{ getPhrase(get_role_name($replay->role_id))}}) </span></h3></span>
                        <p>{!! $replay->massage !!}</p>
                        @if($replay->file != '')
                        <span style="background-color: yellow">{{$replay->file}}</span>
                        <a class="btn btn-primary" href='{{HOMEWORK_PATH.$replay->file}}' download> {{getPhrase('download')}}</a>
                        @endif
                        @if(!is_student())
                            <a href="{{url('homework/replay/delete\/').$replay->id}}" class="btn btn-danger" style="float: left;margin-right: 2%;">{{getPhrase('delete_replay')}}</a>
                        @endif
                        <h4 style="float: left">{{$replay->created_at}}</h4>

                    </div>
                            @endforeach

                    </div>
                </div>

            </div>
        </div>
        <!-- /.container-fluid -->
    </div>
@endsection

@section('footer_scripts')



@stop
