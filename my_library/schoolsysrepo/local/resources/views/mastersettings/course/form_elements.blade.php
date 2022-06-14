<fieldset class="form-group">
    <label for="category_id">{{getPhrase('category')}}</label>
    <span class="text-red">*</span>
    <select name="category_id" class="form-control"  required="required" ng-model="current_category" ng-change="get_courses()">
        <option  ng-repeat="category in categorties" value="@{{ category.id }}">@{{ category.category_name }}</option>
    </select>
</fieldset>

<fieldset class="form-group">
    <label for="parent_id">{{getPhrase('select_parent')}}</label>
    <span class="text-red">*</span>
    <select name="parent_id" class="form-control"  required="required" ng-model="current_course"  ng-change="show_graduate(current_course)">
        <option  ng-repeat="course in courses" value="@{{ course.id }}">@{{ course.course_title }}</option>
        <option value="0"> {{getPhrase('parentlevel')}}</option>
    </select>
    
</fieldset>
<fieldset class="form-group" ng-show="show_graduated_course"  >
     
    
    <input id="graduated_course" class="form-check-input" ng-model="graduated_course"  name="graduated_course" type="checkbox" 
     id="graduated_course">
    <label for="graduated_course"> 
    <span class="fa-stack radio-button" style="border: 2px solid #dcdee0;"> 
    <i  class="mdi mdi-check active"></i> </span> {{getPhrase('isgraduateyear')}}
                      </label>
    
</fieldset>


<fieldset class="form-group">

    {{ Form::label('course_title', getphrase('course_title')) }}

    <span class="text-red">*</span>

    {{ Form::text('course_title', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 'MBA',

        'ng-model'=>'course_title',

        'ng-pattern'=>getRegexPattern('name'),

        'required'=> 'true',

        'ng-class'=>'{"has-error": formCourses.course_title.$touched && formCourses.course_title.$invalid}',

        'ng-minlength' => '2',

        'ng-maxlength' => '60',

        )) }}

    <div class="validation-error" ng-messages="formCourses.course_title.$error">

        {!! getValidationMessage()!!}

        {!! getValidationMessage('pattern')!!}

        {!! getValidationMessage('minlength')!!}

        {!! getValidationMessage('maxlength')!!}

    </div>

</fieldset>




<div class="row ">

</div>

<fieldset class="form-group helper_step4">

    {{ Form::label('description', getphrase('description')) }}

    {{ Form::textarea('description', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 'description', 'rows'=>'5')) }}
</fieldset>

</fieldset>
<div class="buttons text-center">

    <button class="btn btn-lg btn-primary button"

            ng-disabled='!formCourses.$valid'>{{ $button_name }}</button>

</div>

		 