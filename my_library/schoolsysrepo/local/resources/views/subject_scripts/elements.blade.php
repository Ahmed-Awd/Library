<div class="row">
    <fieldset class="form-group col-md-6">
        <label for="">{{getPhrase('academic_year')}}</label>
        <span class="text-red">*</span>
        <select name="year123" class="form-control"  required="required" ng-model="current_year_sc" ng-change="get_sems()">
            <option  ng-repeat="year in academic_years_sc" value="@{{ year.id }}">@{{ year.academic_year_title }}</option>
        </select>
    </fieldset>
    <fieldset class="form-group col-md-6">
        <label for="">{{getPhrase('Semester')}}</label>
        <span class="text-red">*</span>
        <select name="sem123" class="form-control" required="required" ng-model="current_sem_sc" ng-change="getCourses()">
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
        <select name="subject_id" class="form-control" required="required" ng-model="current_subject_sc" ng-change="setCategories(current_subject_sc)">
            <option ng-repeat="subject in academic_subjects_sc" value="@{{ subject.id }}">@{{ subject.subject_title }}</option>
        </select>
    </fieldset>
</div>