@extends($layout)
@include('messaging-system.script')
@section('content')
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
    <?php
    $data =  \App\Settings::get_message_extensions();
    $extn = $data->value;

    ?>
    <div id="page-wrapper" ng-controller="homeworkCtrl">
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
                        <li><a href="{{URL_MESSAGES}}">{{getPhrase('Messages')}}</a> </li>
                        <li class="active"> {{ $title }} </li>
                    </ol>
                </div>
            </div>
            <!-- <h1>Create a new message</h1> -->
            <div class="panel panel-custom">
                <div class="panel-heading">
                    <div class="pull-right messages-buttons">
                        <a href="{{URL_MESSAGES}}" class="btn  btn-primary button">{{ getPhrase('return_to_inbox')}}</a>
                    </div>
                    <h3>{{$title}}</h3>
                </div>
                <div id="historybox" class="panel-body packages inbox-messages-replay">

                    <div class="row library-items">

                        <div class="col-md-12">
                            <h1>{{ ucfirst($thread->subject) }}</h1>
                            <?php $current_user = Auth::user()->id; ?>
                            @foreach($thread->messages as $message)
                                <?php $class='message-sender';
                                if($message->user_id == $current_user)
                                {
                                    $class = 'message-receiver';
                                }


                                ?>
                                <div class="{{$class}}">
                                    <div class="media">
                                        <a class="pull-left" href="#">
                                            <img src="{{getProfilePath($message->user['image'])}}" alt="{!! $message->user['name'] !!}" class="img-circle">
                                        </a>
                                        <div class="media-body">
                                            @if(isAdmin())
                                                <a href="{{URL_USERS_EDIT . $message->user['slug']}}">
                                                   <h5 class="media-heading">{!! $message->user['name'] !!}</h5>
                                                </a>
                                                @else
                                                <h5 class="media-heading">{!! $message->user['name'] !!}</h5>
                                                @endif

                                            <h6 class="media-heading">{!! getPhrase(getRole($message->user['id'])) !!}</h6>
                                            <p>{!! $message->body !!}</p>
                                            <div class="text-muted"><small>Posted {!! getPhrase(str_replace(' ','_',$message->created_at->diffForHumans())) !!}</small></div>
                                            @foreach(getMessageFiles($message->id) as $file)
                                               <a href="{{MESSAGES_FILES.$file->file_name}}" download> <img width="35" height="35" src="{{IMAGE_FILE_ICON}}"></a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
            <h4>{{getPhrase('replay')}}</h4>
        {!! Form::open(['route' => ['messages.update', $thread->id], 'method' => 'PUT']) !!}
        <!-- Message Form Input -->
            <div class="form-group">
                {!! Form::textarea('message', null, ['class' => 'form-control']) !!}
            </div>
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


            <!-- Submit Form Input -->
            <div class="text-center">
                {!! Form::submit(getPhrase('send'), ['class' => 'btn btn-primary btn-lg']) !!}
            </div>
            {!! Form::close() !!}

        </div>
    </div>

@stop

@section('footer_scripts')
    <script>
        $('#historybox').scrollTop($('#historybox')[0].scrollHeight);
    </script>
@stop