<div class="row" id="selection-view" ng-hide="showCalender">
 <div id="selection-view" ng-hide="showCalender">
  <div class="row">
   <div class="col-md-6">
    <fieldset class="form-group">
     <label for="">{{getPhrase('academic_year')}}</label>
     <span class="text-red">*</span>
     <select name="year_id" class="form-control"  required="required" ng-model="current_year_sc" ng-change="get_sems()">
      <option  ng-repeat="year in academic_years_sc" value="@{{ year.id }}">@{{ year.academic_year_title }}</option>
     </select>
    </fieldset>
   </div>
   <div class="col-md-6">
    <fieldset class="form-group">
     <label for="">{{getPhrase('Semester')}}</label>
     <span class="text-red">*</span>
     <select name="sem_id" class="form-control" required="required" ng-model="current_sem_sc" ng-change="getCourses()">
      <option ng-repeat="sem in academic_sems_sc" id="@{{ sem.sem_num }}" value="@{{ sem.sem_num }}"> @{{ sem.title  }}</option>
     </select>
    </fieldset>
   </div>
  </div>
  <div class="row">
   <div class="col-md-6">
    <fieldset class="form-group">
     <label for="">{{getPhrase('branch')}}</label>
     <span class="text-red">*</span>
     <select name="course_id" class="form-control" required="required"
             ng-model="current_course_sc" ng-change="get_sub_courses()">
      <option ng-repeat="course in academic_courses_sc"
              value="@{{ course.id }}">@{{ course.course_title }}</option>
     </select>
    </fieldset>
   </div>
   <div class="col-md-6">
    <fieldset class="form-group">
     <label for="">{{getPhrase('class')}}</label>
     <span class="text-red">*</span>
     <select name="class_id" class="form-control" required="required"
             ng-model="current_class_sc" ng-change="doCall()">
      <option ng-repeat="aclass in subcourses"
              value="@{{ aclass.id }}">@{{ aclass.course_title }}</option>
     </select>
    </fieldset>
   </div>
  </div>
 </div>
</div>