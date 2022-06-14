@extends($layout)
@section('header_scripts')

@stop
<style>
    .progressbar {
        background-color: black;
        border-radius: 13px; /* (height of inner div) / 2 + padding */
        padding: 3px;
        margin-top: 7%;
    }
    .progressbar_2{
        background-color: orange;
        width: 0%; /* Adjust with JavaScript */
        height: 20px;
        border-radius: 10px;
    }
</style>

<?php
$data =  \App\Settings::get_extensions();
$extn = $data->value;
?>
@section('content')
    <div id="page-wrapper" ng-controller="student_papers">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a></li>
                        @if($role=='staff')     
                       
                     <li><a href="{{URL_LESSION_PLANS_STUDENTLIST_DASHBOARD.Auth::user()->slug}}">{{ getPhrase('students')}}</a></li>
                     @else
                        <li><a href="{{PREFIX}}users/student">{{ getPhrase('students')}}</a></li>
                        @endif
                        <li class="active">{{isset($title) ? $title : ''}}</li>
                    </ol>
                </div>
            </div>

        <div class="panel panel-custom">
            <div class="panel-heading">
                <h1>{{ $title }}</h1>
            </div>
            <div class="panel-body packages" id="appy" style="display: none">
                {{ Form::model($records,array('url' => PREFIX.'student/papers/update/'.$user->slug,'method'=>'post','name'=>'formAcademics ', 'novalidate'=>'')) }}
                <div class="row">
                    <fieldset class="form-group col-md-6">
                        <a id="add-semester" ng-click="add_filled()" class="btn btn-primary">{{getphrase('add_document')}}</a>
                    </fieldset>
                </div>
                <div class="row show" ng-repeat="item in items" style="padding-bottom: 2%">
                    <div class="col-md-2">
                        <fieldset class="form-group">
                            <label for="name">{{getPhrase('name')}}</label>
                            <span class="text-red">*</span>
                            <input class="form-control" placeholder="{{getPhrase('name')}}" name="name[]" type="text" ng-model="item.name" required>
                        </fieldset>
                    </div>
                    <div class="col-md-3">
                        <label for="file">{{getPhrase('file')}}</label>
                        <span class="text-red">*</span>
                        <input type="file" class="form-control filer" ngf-select="file_upload($files,item)" accept="{{$extn}}">
                        <span>{{getPhrase('Supported_files')}}:{{$extn}}</span>
                        <input type="hidden" ng-model="item.file" value="@{{item.file}}"  name="file[]" required>
                        <input type="hidden" class="form-control"  name="type[]" value="@{{ item.type }}" id="" ng-model="item.type" required>
                    </div>
                    <div class="col-md-1">
                        <button type="button" ng-click="delete_item(item)" class="btn btn-danger" style="margin-top: 33%;">{{getPhrase('delete')}}</button>
                    </div>
                    <div class="col-md-2" style="margin-top: 2%;" ng-if="item.error">
                        <span style="font-size: 20px;color: red">{{getPhrase('invalid_file_type')}}</span>
                    </div>
                    <div class="col-md-4">
                        <div class="progressbar @{{ item.id }}" style="display: none;">
                            <div class="progressbar_2" id="@{{item.id}}"></div>
                        </div>
                        <div ng-if="item.type == 'image' && item.file != ''">
                            <a href="{{IMAGE_PATH_UPLOAD_STUDENT_PAPERS}}@{{ item.file }}" download>
                                <img src="{{IMAGE_PATH_UPLOAD_STUDENT_PAPERS}}@{{ item.file }}" width="200" height="120" alt="">
                            </a>
                        </div>
                        <div ng-if="item.type == 'video' && item.file != ''">
                            <video width="200" height="120" controls>
                                <source src="@{{ item.video }}">
                            </video>
                        </div>
                        <div ng-if="item.type == 'application' && item.file != ''">
                            <a href="{{IMAGE_PATH_UPLOAD_STUDENT_PAPERS}}@{{ item.file }}" download>
                                <img border="0" src="{{PREFIX}}images/document.jpg" alt="W3Schools" width="200" height="120">
                            </a>
                        </div>
                        <div ng-if="item.type == 'audio' && item.file != ''">
                            <audio controls style="margin-top: 6%;">
                                <source src="@{{ item.video }}">
                            </audio>
                        </div>
                    </div>
                    <br><br><br>
                </div>
                <div class="buttons text-center">
                    <button type="submit" class="btn btn-lg btn-primary button" ng-disabled="formAcademics.$invalid">{{getPhrase('Edit')}}</button>
                </div>
                {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection
@include('student.papers.script')
@section('footer_scripts')
@stop