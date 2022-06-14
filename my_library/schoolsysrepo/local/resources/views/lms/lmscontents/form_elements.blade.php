<?php $settings = getSettings('lms');?>

<style>
    #progressbar {
        background-color: black;
        border-radius: 13px; /* (height of inner div) / 2 + padding */
        padding: 3px;
        margin-top: 3%;
    }

    #progressbar_2{
        background-color: orange;
        width: 0%; /* Adjust with JavaScript */
        height: 20px;
        border-radius: 10px;
    }
    #progressbar2 {
        background-color: black;
        border-radius: 13px; /* (height of inner div) / 2 + padding */
        padding: 3px;
        margin-top: 3%;
    }

    #progressbar2_2{
        background-color: orange;
        width: 0%; /* Adjust with JavaScript */
        height: 20px;
        border-radius: 10px;
    }

</style>

<div class="row">
    <fieldset class="form-group col-md-12">

        {{ Form::label('title', getphrase('title')) }}
        <span class="text-red">*</span>
        {{ Form::text('title', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 'C Programming',
        'ng-model'=>'title',
            'required'=> 'true',
            'ng-pattern' => getRegexPattern("name"),
            'ng-minlength' => '2',
            'id'=>'title',
            'ng-maxlength' => '60',
            'ng-class'=>'{"has-error": formLms.title.$touched && formLms.title.$invalid}',

        )) }}
        <div class="validation-error" ng-messages="formLms.title.$error" >
            {!! getValidationMessage()!!}
            {!! getValidationMessage('minlength')!!}
            {!! getValidationMessage('maxlength')!!}
            {!! getValidationMessage('pattern')!!}
        </div>
    </fieldset>

</div>

<div class="row">
    <fieldset class="form-group col-md-6">
        <label for="">{{getPhrase('academic_year')}}</label>
        <span class="text-red">*</span>
        <select name="year_id" class="form-control"  required="required" ng-model="current_year_sc" ng-change="get_sems()">
            <option  ng-repeat="year in academic_years_sc" value="@{{ year.id }}">@{{ year.academic_year_title }}</option>
        </select>
    </fieldset>
    <fieldset class="form-group col-md-6">
        <label for="">{{getPhrase('Semester')}}</label>
        <span class="text-red">*</span>
        <select name="sem_id" class="form-control" required="required" ng-model="current_sem_sc" ng-change="getClasses()">
            <option ng-repeat="sem in academic_sems_sc" id="@{{ sem.sem_num }}" value="@{{ sem.sem_num }}"> @{{ sem.title  }}</option>
        </select>
    </fieldset>
</div>

<div class="row">

    <fieldset class="form-group col-md-6">
        <label for="">{{getPhrase('branch')}}</label>
        <span class="text-red">*</span>
        <select name="course_id" class="form-control" required="required" ng-model="current_course_sc" ng-change="getSubjects()">
            <option ng-repeat="course in academic_courses_sc" value="@{{ course.id }}">@{{ course.course_title }}</option>
        </select>
    </fieldset>
    <fieldset class="form-group col-md-6">
        <label for="">{{getPhrase('subject')}}</label>
        <span class="text-red">*</span>
        <select name="subject_id" class="form-control" required="required" ng-model="current_subject_sc" ng-change="get_topics()">
            <option ng-repeat="subject in academic_subjects_sc" value="@{{ subject.subject_id }}">@{{ subject.subject_title }}</option>
        </select>
    </fieldset>

</div>

<fieldset class="form-group col-md-6">
    {{ Form::label('topic_id', getphrase('topic')) }} <span class="text-red">*</span>

    <select name="topic_id" class="form-control" required="required" ng-model="topic_id_sc">
        <optgroup ng-repeat="topic in main_topic" label="@{{ topic.topic_name }}">
            <option ng-repeat="subs in topic.sub_topics" value="@{{ subs.id }}">@{{ subs.topic_name }}</option>
        </optgroup>
        {{--<option ng-repeat="topic in topics_sc" ng-class="topic.parent_id == 0 ? 'optionGroup' : 'optionChild' " value="@{{ topic.id }}">@{{ topic.topic_name }}</option>--}}
    </select>

</fieldset>

<div class="row">


    <fieldset class="form-group  col-md-6"   >
        {{ Form::label('image', getphrase('image')) }}
        {{--<input type="file" class="form-control" name="image"--}}
        {{--accept=".png,.jpg,.jpeg" id="image_input">--}}
        <input class="form-control" id="upload1" type="file" ngf-select="uploadImage($files)"/>
        <input type="hidden" name="image" ng-model="file_name" value="@{{file_name}}">
        <div id="progressbar" style="display: none">
            <div id="progressbar_2"></div>
        </div>

    </fieldset>
    <fieldset class="form-group col-md-6" ng-if="file_name"   >
        <img src="{{ IMAGE_PATH_UPLOAD_LMS_CONTENTS}}@{{file_name}}" height="100" width="100">
    </fieldset>

    <fieldset class="form-group col-md-6"   ng-if="!file_name">
        @if($record)
            @if($record->image)
                <img src="{{ IMAGE_PATH_UPLOAD_LMS_CONTENTS.$record->image }}" height="100" width="100">
            @endif
        @endif
    </fieldset>

</div>


<div  class="row">
    <fieldset class="form-group col-md-6" >
        {{ Form::label('content_type', getphrase('content_type')) }}
        <span class="text-red">*</span>
        {{Form::select('content_type', $settings->translated_content_types, null, ['placeholder' => getPhrase('select'),'class'=>'form-control',
        'ng-model'=>'content_type',
            'required'=> 'true',
             'id'=>'content_type',
            'ng-pattern' => getRegexPattern("name"),
            'ng-minlength' => '2',
            'ng-maxlength' => '20',
            'ng-class'=>'{"has-error": formLms.content_type.$touched && formLms.content_type.$invalid}',

        ]) }}
        <div class="validation-error" ng-messages="formLms.content_type.$error" >
            {!! getValidationMessage()!!}
        </div>


    </fieldset>

    <fieldset ng-if="content_type=='url' || content_type=='iframe' || content_type=='video_url'|| content_type=='audio_url'" class="form-group col-md-6">
        {{ Form::label('file_path', getphrase('resource_link')) }}
        <span class="text-red">*</span>
        {{ Form::text('file_path', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 'Resource URL',
            'ng-model'=>'file_path',
            'required'=> 'true',
            'ng-class'=>'{"has-error": formLms.file_path.$touched && formLms.file_path.$invalid}',

    )) }}
        <div class="validation-error" ng-messages="formLms.file_path.$error" >
            {!! getValidationMessage()!!}
        </div>
    </fieldset>



    <fieldset ng-if="content_type=='file' || content_type=='video' || content_type=='audio'" class="form-group col-md-6">
        {{ Form::label('lms_file', 'LMS file') }}
        <span class="text-red">*</span>
        <input class="form-control" id="upload2" type="file" ngf-select="uploadLMS($files)"/>
        <input type="hidden" name="lms_file" ng-model="file_name2" value="@{{file_name2}}">
        <div id="progressbar2" style="display: none">
            <div id="progressbar2_2"></div>
        </div>
    </fieldset>

    <fieldset class="form-group col-md-6"   ng-if="!file_name2">
        @if($record)
            @if($record->file_path && $record->content_type == 'video')
                <video width="320" height="240" controls>
                    <source src="{{IMAGE_PATH_UPLOAD_LMS_CONTENTS.$record->file_path }}">
                </video>
            @endif
        @endif
    </fieldset>
    <fieldset class="form-group col-md-6"   ng-if="!file_name2">
        @if($record)
            @if($record->file_path && $record->content_type == 'audio')
                <audio controls>
                    <source src="{{IMAGE_PATH_UPLOAD_LMS_CONTENTS.$record->file_path }}">
                </audio>
            @endif
        @endif
    </fieldset>

    <fieldset class="form-group col-md-6" ng-if="file_name2 && content_type=='video'">
        <video width="320" height="240" controls>
            <source src="@{{ file_show2 }}" type="video/mp4">
        </video>
    </fieldset>
    <fieldset class="form-group col-md-6" ng-if="file_name2 && content_type=='audio'">
        <audio controls>
            <source src="@{{ file_show2 }}">
        </audio>
    </fieldset>


    @if($record)
        @if($record->resource_link!='')
            <fieldset class="form-group col-md-6">
                <label>   &nbsp;</label>
                {{link_to_asset(IMAGE_PATH_UPLOAD_LMS_CONTENTS.$record->resource_link, getPhrase('download'))}}
            </fieldset>
        @endif
    @endif

</div>

<fieldset class="form-group">

    {{ Form::label('description', getphrase('description')) }}

    {{ Form::textarea('description', $value = null , $attributes = array('class'=>'form-control ckeditor', 'rows'=>'5', 'placeholder' => 'Fine description','id'=>'description', )) }}
</fieldset>




<div class="buttons text-center">
    <button class="btn btn-lg btn-primary button"
            ng-disabled='!formLms.$valid'>{{ $button_name }}</button>
</div>