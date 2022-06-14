<script src="{{JS}}angular.js"></script>
<script src="{{JS}}angular-messages.js"></script>
<script src="{{JS}}ng-file-upload.js"></script>
<script src="{{JS}}angular-toastr.min.js"></script>
<script src="{{JS}}angular-toastr.tpls.min.js"></script>

<script>
    var app = angular.module('academia', ['ngMessages', 'satellizer', 'ngFileUpload', 'toastr']);
    app.controller('questionsController', function ($scope, $http, Upload, toastr) {


        $scope.explanation = false;
        $scope.topics_sc = [];
        $scope.main_topic = [];

        $scope.current_course_sc    = null;
        $scope.current_subject_sc   = null;
        $scope.academic_courses_sc  = [];
        $scope.academic_subjects_sc = [];


        $scope.first_time = 0;

        $scope.current_year_sc = {{default_year()}};
        $scope.current_year_sc = $scope.current_year_sc.toString();



        $scope.show_hide = function () {
            if($scope.explanation == true){
                $("#explain").show();
            }
            else{
                $("#explain").hide();
            }
        }

        $scope.lastPart = window.location.href.split("/").pop();

        $scope.ifEdit = function () {
            if($scope.lastPart != 'add-question'){
                $http({
                    method:"GET",
                    url:'{{PREFIX}}'+'get_question_data/'+$scope.lastPart,
                    dataType:"json",
                    headers:{'Content-Type': 'application/x-www-form-urlencoded'}
                })
                    .then(function (response) {
                        console.log(response.data);
                        $scope.current_year_sc    = response.data.academic_id.toString();
                        $scope.current_sem_sc     = response.data.sem_id.toString();
                        $scope.current_course_sc  = response.data.course_id.toString();
                        $scope.current_subject_sc = response.data.subject_id.toString();
                        $scope.getSubjects();
                        $scope.topic_id_sc    = response.data.topic_id.toString();
                    })
            }
        }


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
                    if(response.data.length != 0 && $scope.first_time != 1) {
                        $scope.current_subject_sc = response.data[0].subject_id.toString();
                    }
                    $scope.get_topics();
                })
        }


        $scope.get_topics = function () {
          //alert($scope.current_year_sc);
            $http({
                method: "GET",
                url: '{{PREFIX}}' + 'get_toopy/' +$scope.current_year_sc+'/'+ $scope.current_course_sc + '/' + $scope.current_subject_sc + '/' + $scope.current_sem_sc ,
                dataType: "json",
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            })
                .then(function (response) {

                    $scope.topics_sc = response.data;
                    angular.forEach($scope.topics_sc,function(item){
                        if(item.parent_id == 0){
                            item.topic_name = "- "+item.topic_name;
                            $scope.main_topic.push(item);
                        }
                    });
                    angular.forEach($scope.main_topic,function (item) {
                        item.sub_topics = [];
                        angular.forEach($scope.topics_sc,function(item2){
                           if(item.id == item2.parent_id){
                               item.sub_topics.push(item2);
                           }
                        });
                    });
//                    console.log($scope.main_topic);
                    if($scope.topics_sc.length != 0 && $scope.first_time != 1)
                    {
                        $scope.topic_id_sc = $scope.topics_sc[0].id.toString();
                    }
                    if($scope.first_time == 0){
                        $scope.ifEdit();
                        $scope.first_time++;
                    }
                }).then(function () {
                showHide($scope.current_subject_sc)
            })
        }


        //$scope.get_topics();

        $scope.file_name = null;

        $scope.uploadImage = function ($files) {
            var file = $files[0];
            $('#progressbar').show();
            Upload.upload({
                url: '{{PREFIX}}exams/questionbank/upload',
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
                }
                $scope.file_show = "{{ EXAM_UPLOADS}}" + response.data.file;
                $scope.file_name = response.data.file;
                $('#progressbar').hide();
                //$('#upload1').css({pointerEvents: "initial"});
                $('#upload1').val('');
            });
        }

        $scope.initAngData = function (data) {

            if (data == '') {

                return;
            }

            data = JSON.parse(data);


            $scope.question_type = data.question_type;
            $scope.correct_answers = data.correct_answers;
            $scope.total_correct_answers = parseInt(data.total_correct_answers);

            if (data.question_type == 'blanks') {
            }

            if (data.question_type == 'radio') {
                $scope.answers = data.answers;
            }

            if (data.question_type == 'checkbox') {
                $scope.answers = data.answers;
                $scope.correct_answers = data.correct_answers;
            }
            if (data.question_type == 'match') {

                $scope.answers = data.answers;
                $scope.correct_answers = data.correct_answers;
            }

            if (data.question_type == 'para' || data.question_type == 'video' || data.question_type == 'audio') {

                $scope.answers = data.answers;
                $scope.correct_answers = data.correct_answers;
            }

        }

        $scope.range = function (count) {
            var range = [];
            for (var i = 0; i < count; i++) {
                range.push(i)
            }
            return range;
        }

        $scope.answersChanged = function (selected_number) {
            $scope.total_answers = selected_number;

        }
        $scope.correctAnswersChanged = function (selected_number) {
            $scope.total_correct_answers = selected_number;

        }
        $scope.paraOptionsChanged = function (selected_number) {
            $scope.total_para_options = selected_number;

        }

        $scope.getToken = function () {
            return $('[name="_token"]').val();
        }

    });
</script>
<script>
    function showHide(subject) {
        if (parseInt($('.course').val()) < 23) {
            $('#skillsArea').css('display', 'block')
            getSkills(subject)

        } else {
            $('#skillsArea').css('display', 'none')
        }

    }

    function getSkills(subject) {
        if (subject == 'undef') {
            $('#skills').empty();
            return
        }
        $('#skills').empty();
        $.ajax({
            url: "{{url('getSkills')}}",
            type: 'get',
            data: {'course_id': $('.course').val(), 'subject_id': subject},
            success: function (result) {
                $('#skills').append('<option value="0">{{getPhrase("select")}}</option>');
                for (i = 0; i < result.length; i++) {
                    var id = "{{$record!=false ? $record->skill_id:''}}";
                    if (id == result[i].id) {
                        $('#skills').append('<option selected value="' + result[i].id + '">' + result[i].skill_title + '</option>');
                    }else {
                        $('#skills').append('<option  value="' + result[i].id + '">' + result[i].skill_title + '</option>');
                    }
                }
            }
        })
    }

    /*$(window).on('load', function(){
        showHide()
        alert($('.course').val())
    })
    $(document).ready(function () {
        $(document).on('change','.course',function () {
            showHide()
        })
        $(document).on('change','.subject',function () {
           getSkills()
        })
    })*/
</script>
