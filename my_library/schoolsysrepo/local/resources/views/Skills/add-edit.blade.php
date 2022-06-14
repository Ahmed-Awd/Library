@extends($layout)
@section('content')
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li><a href="{{url('/')}}"><i class="mdi mdi-home"></i></a></li>
                        <li><a href="{{url('/mastersettings/skills')}}">{{getPhrase('skills')}}</a></li>
                        <li>{{ getPhrase($title) }}</li>
                    </ol>
                </div>
            </div>
            <div class="panel panel-custom">
                <div class="panel-heading">
                    <div class="pull-right messages-buttons">
                        <a href="{{url('mastersettings/skills')}}"
                           class="btn btn-primary button">{{ getPhrase('skills_menu')}}</a>
                    </div>
                    <h1>{{ $title }}  </h1>
                </div>
                <div class="panel-body packages" ng-controller="subjects_course">
                    @if($manpulation_kind == 'add')
                        {{ Form::open(array('url' => 'mastersettings/skills/store', 'method'=>'post')) }}
                    @else
                        {{ Form::open(array('url' => 'mastersettings/skills/update/'.$record->id, 'method'=>'post')) }}
                    @endif
                    {{csrf_field()}}
                    @include('subject_scripts.elements')
                    <div class="texts">
                        <div class="row">
                            <fieldset class="form-group col-md-11">
                                {{ Form::label('skill', getphrase('skill')) }}
                                <span class="text-red">*</span>
                                {{ Form::text('skills[]', $value = isset($record->skill_title)? $record->skill_title : null , $attributes = array('class'=>'form-control','required'=>'required','placeholder' => getPhrase('skill_name'),'id'=>'skill')) }}
                            </fieldset>
                            @if($manpulation_kind == 'add')
                                <fieldset class="form-group col-md-1" style="margin-top: 35px;">
                                    <button class="btn btn-primary new">{{getPhrase('new')}}</button>
                                </fieldset>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <fieldset class="form-group col-md-3">
                            <input type="submit" class="btn btn-success"
                                   style="margin-right: 600px; height: 70px; width: 70px;"
                                   value="{{getPhrase('save')}}">
                        </fieldset>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@stop
<script src="{{JS}}angular.js"></script>
@section('footer_scripts')
    @include('Skills.js-script',array('record'=>isset($record)?$record:null))
    @include('subject_scripts.main',array('id'=>$sid))
@stop
