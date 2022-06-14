<?php
    use Illuminate\Support\Facades\Auth;
?>
@include('common.angular-factory')
<script>


     app.controller('academicAttendance',function ($scope, $http, httpPreConfig,$location) {
    @include('common.js-script-year-selection',array('doCall'=>false))


        var current_teacher;
         $scope.current_sem_sc       = null;
         $scope.current_course_sc    = null;
         $scope.current_subject_sc   = null;
         $scope.current_teacher      = null;
         $scope.teacher_slug         = "{{Request::segment(3)}}"
         $scope.attendance_date      = new Date().toISOString().split("T")[0];
         $scope.academic_courses_sc  = [];
         $scope.academic_subjects_sc = [];
         $scope.role_sc        = {{Auth::user()->role_id}};
         $scope.lastPart = window.location.href.split("/").pop();


        @include('common.year_sems_js');


         $scope.getSubjects = function () {
             return false;
         }


         $scope.getCourses = function () {
             if($scope.role_sc == 9){
                 //supervisor/teacher-courses/{slug}
                 $http({
                     method:"GET",
                     url:'{{PREFIX}}'+'supervisor/teacher-courses/'+$scope.lastPart,
                     dataType:"json",
                     headers:{'Content-Type': 'application/x-www-form-urlencoded'}
                 })
                     .then(function (response) {
                         $scope.academic_courses_sc = response.data;
                         $scope.setCurrents();
                     })
             }else{

                if($scope.teacher_slug != null){

                     $http({
                             method:"GET",
                             url:'{{PREFIX}}'+'check_if_teacher_also__login/'+$scope.teacher_slug,
                             dataType:"json",
                             headers:{'Content-Type': 'application/x-www-form-urlencoded'}
                         })
                             .then(function (response) {

                                  if(response.data == '0'){
                                            
                                           $scope.current_teacher = $scope.teacher_slug;

                                             $http({
                                                 method:"GET",
                                                 url:'{{PREFIX}}'+'get_courses_2/'+$scope.current_year_sc+'/'+$scope.current_sem_sc+'/'+$scope.current_teacher,
                                                 dataType:"json",
                                                 headers:{'Content-Type': 'application/x-www-form-urlencoded'}
                                             })
                                                 .then(function (response) {
                                                     $scope.academic_courses_sc = response.data;
                                                 })


                                     }else if(response.data == '1' ||  response.data == '2'){

                                          $http({
                                                 method:"GET",
                                                 url:'{{PREFIX}}'+'get_courses_2/'+$scope.current_year_sc+'/'+$scope.current_sem_sc+'/'+$scope.current_teacher,
                                                 dataType:"json",
                                                 headers:{'Content-Type': 'application/x-www-form-urlencoded'}
                                             })
                                                 .then(function (response) {
                                                     $scope.academic_courses_sc = response.data;
                                                 })

                                     } 



                                  
                             }) 

                         }

                
             }
         }

         //$scope.getCourses();


         $scope.getSubjects = function () {
             if($scope.current_course_sc == null || $scope.current_year_sc == null || $scope.current_sem_sc == null){
                 return false;
             }

             if($scope.role_sc == 9){
                 $http({
                     method:"GET",
                     url:'{{PREFIX}}'+'supervisor/teacher-subjects/'+$scope.current_year_sc+'/'+$scope.current_sem_sc+'/'+$scope.current_class_sc+'/'+$scope.lastPart,
                     dataType:"json",
                     headers:{'Content-Type': 'application/x-www-form-urlencoded'}
                 })
                     .then(function (response) {
                         $scope.academic_subjects_sc = response.data;
                     })
             }else{
                 $http({
                     method:"GET",
                     url:'{{PREFIX}}'+'get_subjects_2/'+$scope.current_year_sc+'/'+$scope.current_sem_sc+'/'+$scope.current_class_sc+'/'+$scope.current_teacher,
                     dataType:"json",
                     headers:{'Content-Type': 'application/x-www-form-urlencoded'}
                 })
                     .then(function (response) {
                         $scope.academic_subjects_sc = response.data;
                     })
             }

         }

         $scope.getClasses = function () {
             $http({
                 method:"GET",
                 url:'{{PREFIX}}'+'teacher_classes/'+$scope.current_sem_sc+'/'+$scope.current_year_sc+'/'+$scope.current_teacher+'/'+$scope.current_course_sc,
                 dataType:"json",
                 headers:{'Content-Type': 'application/x-www-form-urlencoded'}
             })
                 .then(function (response) {
                     $scope.academic_classes_sc = response.data;
                 })
         }

         $scope.admin_classes = function () {
             $http({
                 method:"GET",
                 url:'{{PREFIX}}'+'get_classes/'+$scope.current_course_sc,
                 dataType:"json",
                 headers:{'Content-Type': 'application/x-www-form-urlencoded'}
             })
                 .then(function (response) {
                     $scope.academic_classes_sc = response.data;
                 })
         }
         
         $scope.get_exams = function () {
             $http({
                 method:"GET",
                 url:'{{PREFIX}}'+'get_exams/'+$scope.current_course_sc+'/'+$scope.current_subject_sc,
                 dataType:"json",
                 headers:{'Content-Type': 'application/x-www-form-urlencoded'}
             })
                 .then(function (response) {
                     console.log(response.data);
                     $scope.quizs = response.data;
                 })
         }

         $scope.getSubjectsTimetable = function () {

             if($scope.current_course_sc == null || $scope.current_subject_sc == null){
                 return false;
             }
             $http({
                 method:"GET",
                 url:'{{PREFIX}}'+'get_subjects_timetable/'+$scope.current_subject_sc+'/'+$scope.current_course_sc,
                 dataType:"json",
                 headers:{'Content-Type': 'application/x-www-form-urlencoded'}
             })
                 .then(function (response) {
                     $scope.time_subjects_sc = response.data;
                     if(response.data.length != 0) {
                         $scope.current_subject_sc = response.data[0].subject_id.toString();
                     }

                 })
         }

         $scope.test_change = function () {
             console.log("date changed");
         }

         $scope.test_change();

         $scope.file_upload = function ($files) {
             console.log($files);

         }

});

</script>
