<fieldset class="form-group">
    <label for="">{{getPhrase('academic_year')}}</label>
    <span class="text-red">*</span>
    <select name="year" class="form-control"  required="required" ng-model="current_year_sc" ng-change="get_sems()">
        <option  ng-repeat="year in academic_years_sc" value="@{{ year.id }}">@{{ year.academic_year_title }}</option>
    </select>
</fieldset>

<fieldset class="form-group">
    <label for="">{{getPhrase('Semester')}}</label>
    <span class="text-red">*</span>
    <select name="semesters" class="form-control" required="required" ng-model="current_sem_sc" ng-change="getSubjects()">
        <option ng-repeat="sem in academic_sems_sc" id="@{{ sem.sem_num }}" value="@{{ sem.sem_num }}"> @{{ sem.title  }}</option>
    </select>
</fieldset>

<fieldset class="form-group">
    <label for="">{{getPhrase('branch')}}</label>
    <span class="text-red">*</span>
    <select name="course_id" class="form-control" required="required" ng-model="current_course_sc" ng-change="getSubjects()">
        <option ng-repeat="course in academic_courses_sc" value="@{{ course.id }}">@{{ course.course_title }}</option>
    </select>
</fieldset>

<fieldset class="form-group">
    <label for="">{{getPhrase('subject')}}</label>
    <span class="text-red">*</span>
    <select name="subject_id" class="form-control" required="required" ng-model="current_subject_sc" ng-change="getTopics()">
        <option ng-repeat="subject in academic_subjects_sc" value="@{{ subject.subject_id }}">@{{ subject.subject_title }}</option>
    </select>
</fieldset>

<fieldset class="form-group">
    <label for="">{{getPhrase('select_parent')}}</label>
    <span class="text-red">*</span>
    <select name="parent_id" class="form-control" required="required" ng-model="current_topic_sc">
        <option value="0">{{getPhrase('top_level')}}</option>
        <option ng-repeat="topic in academic_topics_sc" value="@{{ topic.id }}">@{{ topic.topic_name }}</option>
    </select>
</fieldset>


<fieldset class="form-group">

    {{ Form::label('topic_name', getphrase('topic_name')) }}
    <span class="text-red">*</span>
    {{ Form::text('topic_name', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 'Introduction',
        'ng-model'=>'topic_name',
        'required'=> 'true',
        'ng-class'=>'{"has-error": formTopics.topic_name.$touched && formTopics.topic_name.$invalid}',
     ))}}
    <div class="validation-error" ng-messages="formTopics.topic_name.$error">
        {!! getValidationMessage()!!}
    </div>
</fieldset>

<fieldset class="form-group">

    {{ Form::label('description', getphrase('description')) }}

    {{ Form::textarea('description', $value = null , $attributes = array('class'=>'form-control', 'rows'=>'5', 'placeholder' => 'Description of the topic')) }}
</fieldset>

<div class="buttons text-center">
    <button class="btn btn-lg btn-primary button"
            ng-disabled='!formTopics.$valid'
    >{{ $button_name }}</button>
</div>
