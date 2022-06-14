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
$data =  \App\Settings::get_HW_extensions();
$extn = $data->value;

?>


<div class="row">
<div class="col-md-8">
    <fieldset class="form-group col-md-8">
        <label for="">{{getPhrase('title')}}</label>
        <span class="text-red">*</span>
        <input name="title" ng-model="title" class="form-control" required>
    </fieldset>
</div>
</div>
@if(!isset($edit))
<div class="row">
@if(isset($teachers) && !is_teacher())
    <div class="col-md-8">
        <fieldset class="form-group col-md-8">
            <label for="">{{getPhrase('teachers')}}</label>
            <span class="text-red">*</span>
            <select name="teacherSlug" class="form-control" required="required"
                    ng-model="current_teacher" ng-change="getCourses()">
                <option ng-repeat="teacher in {{$teachers}}"
                        value="@{{teacher.slug}}">@{{ teacher.name }}</option>
            </select>
        </fieldset>
    </div>
@endif
</div>
<div class="row">
    <div class="col-md-8">
        <fieldset class="form-group col-md-8">
            <label for="">{{getPhrase('academic_year')}}</label>
            <span class="text-red">*</span>
            <select name="academic_id" class="form-control"  required="required" ng-model="current_year_sc" ng-change="get_sems()">
                <option  ng-repeat="year in academic_years_sc" value="@{{ year.id }}">@{{ year.academic_year_title }}</option>
            </select>
        </fieldset>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <fieldset class="form-group col-md-8">
            <label for="">{{getPhrase('Semester')}}</label>
            <span class="text-red">*</span>
            <select name="current_semister" class="form-control" required="required" ng-model="current_sem_sc" ng-change="getCourses()">
                <option ng-repeat="sem in academic_sems_sc" id="@{{ sem.sem_num }}" value="@{{ sem.sem_num }}"> @{{ sem.title  }}</option>
            </select>
        </fieldset>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <fieldset class="form-group col-md-8">
            <label for="">{{getPhrase('branch')}}</label>
            <span class="text-red">*</span>
            <select name="course_id" class="form-control" required="required"
                    ng-model="current_course_sc" ng-change="getClasses()">
                <option ng-repeat="course in academic_courses_sc"
                        value="@{{ course.id }}">@{{ course.course_title }}</option>
            </select>
        </fieldset>
            <fieldset class="form-group col-md-8">
                <label for="">{{getPhrase('class')}}</label>
                <span class="text-red">*</span>
                <select name="class_id[]" class="form-control" required="required" multiple
                        ng-model="current_class_sc" ng-change="getSubjects()">
                    <option ng-repeat="aclass in academic_classes_sc"
                            value="@{{ aclass.id }}">@{{ aclass.course_title }}</option>
                </select>
            </fieldset>

        <fieldset class="form-group col-md-8">
            <label for="">{{getPhrase('subject')}}</label>
            <span class="text-red">*</span>
            <select name="course_subject_id" class="form-control" required="required"
                    ng-model="current_subject_sc">
                <option ng-repeat="subject in academic_subjects_sc"
                        value="@{{ subject.subject_id }}">@{{ subject.subject_title }}</option>
            </select>
        </fieldset>
    </div>
</div>
@endif
<div class="row">
    <div class="col-md-10">
        <fieldset class="form-group col-md-10">
        {{ Form::label('explanation', getphrase('explanation')) }}
        <span class="text-red">*</span>

        {{--{{ Form::textarea('explanation', $value = null , $attributes = array('class'=>'form-control ckeditor', 'placeholder' => 'Your question', 'rows' => '5',--}}
        {{--'ng-model'=>'explanation',--}}
        {{--'id'=>'explanation',--}}
        {{--'ng-class'=>'{"has-error": formQuestionBank.question.$touched && formQuestionBank.question.$invalid}',--}}
        {{--'ng-minlength' => '0',--}}

        {{--)) }}--}}

            <textarea name="explanation" id="explanation" class="form-control ckeditor">@if(isset($record)) {{$record->explanation}}    @endif</textarea>
        </fieldset>
    </div>
</div>


<div class="row" ng-if="uploaded_file">
    <div class="col-md-6">
        <a class="btn" style="font-size: 30px" href="{{HOMEWORK_PATH}}@{{uploaded_file}}" download>@{{ uploaded_file }}</a>
        <a class="btn btn-danger" ng-click="deleteFile()">{{getPhrase('delete')}}</a>
    </div>

</div>

<div class="row" ng-if="!uploaded_file">

        <div class="col-md-6">
            <fieldset class="form-group">
                {{ Form::label('question_file', getPhrase('upload') ) }}

                <span>{{getPhrase('supported_formats_are')}} {{$extn}}
         </span>
                {{--{{Form::file('question_file', $attributes = array('class'=>'form-control'))}}--}}
                <input class="form-control" id="upload1" type="file" accept="{{$extn}}" ng-model="upload1" ngf-select="upload_file($files)">
                <span style="color: red" ng-if="valid == 'no'"> @{{ massage }}</span>
                <span style="color: green" ng-if="valid == 'ok'"> @{{ massage }}</span>
                <div id="progressbar" style="display: none">
                    <div id="progressbar_2"></div>
                </div>

            </fieldset>
        </div>
</div>
<input type="hidden" name="question_file" ng-model="file_name" value="@{{file_name}}">
<div class="buttons text-center">
    <button type="submit" ng-disabled="bupload == false" class="btn btn-lg btn-primary">{{$button_name}}</button>
</div>

