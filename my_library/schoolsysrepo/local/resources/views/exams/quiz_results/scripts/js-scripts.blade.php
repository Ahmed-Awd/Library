<?php
use Illuminate\Support\Facades\Auth;
?>
@include('common.angular-factory')
<script>


    app.controller('academicAttendance', function ($scope, $http, httpPreConfig,$location) {
            $scope.current_year_sc      = {{default_year()}};
        $scope.current_sem_sc       = null;
        $scope.current_course_sc    = null;
        $scope.current_subject_sc   = null;
        $scope.current_teacher = null;
        $scope.attendance_date = new Date().toISOString().split("T")[0];
        $scope.academic_courses_sc  = [];
        $scope.academic_subjects_sc = [];
        $scope.lastPart = window.location.href.split("/").pop();
        @include('common.year_sems_js');
        @include('common.course_js');

        $scope.getSubjects = function () {
            if($scope.current_course_sc == null || $scope.current_year_sc == null || $scope.current_sem_sc == null){
                return false;
            }
                $http({
                    method:"GET",
                    url:'{{PREFIX}}'+'get_subjects/'+$scope.current_year_sc+'/'+$scope.current_sem_sc+'/'+$scope.current_course_sc,
                    dataType:"json",
                    headers:{'Content-Type': 'application/x-www-form-urlencoded'}
                })
                    .then(function (response) {
                        $scope.academic_subjects_sc = response.data;
                        $scope.get_exams();
                    })
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
                url:'{{PREFIX}}'+'get_exams/'+$scope.current_class_sc+'/'+$scope.current_subject_sc,
                dataType:"json",
                headers:{'Content-Type': 'application/x-www-form-urlencoded'}
            })
                .then(function (response) {
                    console.log(response.data);
                    $scope.quizs = response.data;
                })
        }



    });

</script>
