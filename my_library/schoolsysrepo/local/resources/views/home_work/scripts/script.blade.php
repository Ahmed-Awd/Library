<script src="{{JS}}angular.js"></script>
<script src="{{JS}}angular-messages.js"></script>
<script src="{{JS}}ng-file-upload.js"></script>
<script src="{{JS}}angular-toastr.min.js"></script>
<script src="{{JS}}angular-toastr.tpls.min.js"></script>

<?php


?>
<script>
    var app = angular.module('academia', ['ngMessages', 'ngFileUpload', 'toastr']);
    app.controller('homeworkCtrl', function ($scope, $http, Upload, toastr) {

        $scope.current_sem_sc       = null;
        $scope.current_course_sc    = null;
        $scope.current_subject_sc   = null;
        $scope.current_teacher = null;
        $scope.attendance_date = new Date().toISOString().split("T")[0];
        $scope.academic_courses_sc  = [];
        $scope.academic_subjects_sc = [];
        $scope.role_sc        = {{Auth::user()->role_id}};
        $scope.lastPart = window.location.href.split("/").pop();


        $scope.ifEdit = function () {
            if($scope.lastPart != 'add-homework'){
                $http({
                    method:"GET",
                    url:'{{PREFIX}}'+'homework/get-homework-data/'+$scope.lastPart,
                    dataType:"json",
                    headers:{'Content-Type': 'application/x-www-form-urlencoded'}
                })
                    .then(function (response) {
                        console.log(response.data);
                        $scope.title = response.data.title;
                        if(response.data.file != ''){
                            $scope.uploaded_file = response.data.file;
                        }
                    })
            }
        }
        $scope.deleteFile = function () {
            $http({
                method:"delete",
                url:'{{GW_FILE_DELETE}}'+$scope.uploaded_file,
                dataType:"json",
                headers:{'Content-Type': 'application/x-www-form-urlencoded'}
            })
                .then(function (response) {
                    $scope.uploaded_file = false;
                    $scope.file_name     = false;
                })
        }
        $scope.ifEdit();
        $scope.bupload = true;
        $scope.upload_file = function ($files) {
            $scope.bupload = false;
            var file = $files[0];
            extn = "."+file.name.split(".").pop();
            $http({
                method:"GET",
                url:'{{PREFIX}}'+'available_HW_extn',
                dataType:"json",
                headers:{'Content-Type': 'application/x-www-form-urlencoded'}
            })
                .then(function (response) {
                    $scope.av_extn = response.data.split(',');
                    if($scope.av_extn.includes(extn)){
                        $('#progressbar').show();
                        $scope.valid = "ok";
                        Upload.upload({
                            url: '{{PREFIX}}homework/upload',
                            dataType: "json",
                            file: file,
                            method: "POST",
                            headers: {'Content-Type': undefined}
                        }).progress(function (e) {
                            var progress = (e.loaded / e.total) * 100;
                            $("#progressbar_2").css('width', progress + '%');
                        }).then(function (response, status, headers, config) {
                            if (response.data.state == "failed") {
                                toastr.error(response.data.desc, 'Error');
                            }
                            if (response.data.state == "success") {
                                toastr.success(response.data.desc);
                                $scope.bupload = true;
                                $scope.uploaded_file = response.data.file;
                            }
                            $scope.file_show = "{{ EXAM_UPLOADS}}" + response.data.file;
                            $scope.file_name = response.data.file;
                            //$scope.massage = "file uploaded successfully";
                            $('#progressbar').hide();
                            $('#upload1').val('');
                        });
                    }else{
                        $scope.valid = "no";
                        $scope.upload1 = "";
                        $scope.massage = "invalid file type";
                    }

                })


        }
    @include('common.year_sems_js')

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
                    })
            }else{
                console.log($scope.current_teacher);
                $http({
                    method:"GET",
                    url:'{{PREFIX}}'+'get_courses_2/'+$scope.current_year_sc+'/'+$scope.current_sem_sc+'/'+$scope.current_teacher,
                    dataType:"json",
                    headers:{'Content-Type': 'application/x-www-form-urlencoded'}
                })
                    .then(function (response) {
                        $scope.academic_courses_sc = response.data;
//                        $scope.setCurrents();

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
                    //$scope.setCurrents();

                })
        }


        $scope.getSubjects = function () {
            if($scope.current_course_sc == null || $scope.current_year_sc == null || $scope.current_sem_sc == null){
                return false;
            }

            if($scope.role_sc == 9){
                $http({
                    method:"GET",
                    url:'{{PREFIX}}'+'supervisor/teacher-subjects/'+$scope.current_year_sc+'/'+$scope.current_sem_sc+'/'+$scope.current_course_sc+'/'+$scope.lastPart,
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

        $scope.toTable = function () {


            $scope.teacher_slug = $scope.current_teacher;
            angular.forEach($scope.academic_classes_sc,function (item) {
                if(item.id == $scope.current_class_sc){
                    $scope.course_slug =  item.slug;
                }
            })

            angular.forEach($scope.academic_subjects_sc,function (item) {
                if(item.subject_id == $scope.current_subject_sc){
                    $scope.subject_slug =  item.slug;
                }
            })
            console.log($scope.teacher_slug);
            window.location.href = "{{PREFIX}}homework/get-homeworks/"+$scope.teacher_slug+"/"+$scope.course_slug+"/"+$scope.subject_slug+"/"+$scope.current_year_sc+"/"+$scope.current_sem_sc;
        }


    });
</script>