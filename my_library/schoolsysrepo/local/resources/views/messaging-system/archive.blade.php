@extends($layout)
@include('messaging-system.script')
@section('content')
    <div id="page-wrapper">

        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a></li>
                    <li><a href="{{URL_MESSAGES}}">{{getPhrase('Messages')}}</a></li>

                </ol>
            </div>
        </div>
        <div class="panel panel-custom">
            <div class="panel-heading">


                <div class="pull-right messages-buttons">
                    <a class="btn btn-lg btn-info button"
                       href="{{URL_MESSAGES_ARCHIVE}}"> {{getPhrase('Archive').'('.$archive_count.')'}} </a>
                    <a class="btn btn-lg btn-info button"
                       href="{{URL_MESSAGES}}"> {{getPhrase('inbox').'('.$messages_count.')'}} </a>
                    <a class="btn btn-lg btn-info button" href="{{URL_MESSAGES_CREATE}}">
                        {{getPhrase('compose')}}</a>
                </div>
                <div class="col-md-3 col-md-offset-3 pull-right">
                    @if($inbox == true)
                        <fieldset class="form-group">
                            <input type="text" placeholder="{{getPhrase('search_in_inbox')}}" ng-model="key"
                                   ng-change="keyChanged()" name="search"
                                   class="form-control ng-valid ng-touched ng-dirty ng-valid-parse ng-empty">
                        </fieldset>
                    @endif
                </div>
                <h1>{{getPhrase('inbox')}}</h1>


            </div>

        </div>
        <?php $currentUserId = Auth::user()->id;?>
        <div class="panel-body packages">
            <div class="row">

                <div class="col-md-12">

                    <ul class="inbox-message-list inbox-message-nocheckbox">

                        @foreach($threads as $thread)
                            <li class="unread-message" style="margin-bottom: 2%;">

                                <img class="sender" src="{{ $thread->image }}" alt="">
                                <a href="{{URL_MESSAGES_SHOW}} {{ $thread->id }}" class="message-suject">
                                    <p style="font-size: 17px;">{{getPhrase('from')}} : {{ $thread->username }}
                                        ({{ $thread->role }})</p>
                                    <p style="font-size: 15px;">{{getPhrase('to')}} :
                                        @foreach($thread->to as $to)
                                            <span> {{ $to->name }};</span>
                                         @endforeach
                                    </p>
                                    <h3> {{ $thread->subject }}</h3>
                                    <p ng-bind-html-unsafe="head.body"> {{ $thread->body }}</p>
                                    @if($thread->hasfile == true)
                                    <img width="45" height="45"  src="{{IMAGE_FILE_ICON}}">
                                    @endif
                                </a>
                                <span class="receive-time"><i class="mdi mdi-clock"></i> {{ $thread->last_time }}</span>
                                <a ng-if="head.sender == me" href="javascript:void(0)" onclick="destroy({{$thread->id}})"
                                   class="btn btn-danger button helper_step1"
                                   style="margin-left: 1%;">{{getPhrase('delete')}}</a>
                                <br>
                            </li>
                        @endforeach

                        @if(count($threads) == 0)
                            <p>{{getPhrase('sorry_no_messages_available')}}.</p>
                        @endif


                    </ul>
                </div>
            </div>


        </div>
    </div>
    </div>
    <!-- /.container-fluid -->
    </div>

@stop
