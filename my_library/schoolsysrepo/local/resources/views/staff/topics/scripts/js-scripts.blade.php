<script src="{{JS}}angular.js"></script>
<script src="{{JS}}angular-messages.js"></script>
<script src="{{JS}}select2.js"></script>
<script src="{{JS}}satellizer.min.js"></script>
<script>

    var app = angular.module('academia', ['ngMessages','satellizer']);


    app.controller('angTopicsController', function ($scope, $http) {

        $scope.academic_courses_sc  = [];
        @if(isset($record_course_id))
        $scope.current_course_sc    = {{$record_course_id}};
        $scope.current_course_sc    = $scope.current_course_sc.toString();
        @endif
        $scope.academic_years_sc    = [];
        $scope.current_subject_sc   = null;
        $scope.academic_subjects_sc = [];
        $scope.academic_topics_sc   = [];
        $scope.current_topic_sc     = null;


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
                    if($scope.academic_subjects_sc.length != 0){
                        $scope.current_subject_sc = $scope.academic_subjects_sc[0].subject_id.toString();
                    }
                    @if(isset($recored_subject))
                    $scope.current_subject_sc   = {{$recored_subject}};
                    $scope.current_subject_sc   = $scope.current_subject_sc.toString();
                        @if($recored_subject == 0)
                            $scope.current_subject_sc   = $scope.academic_subjects_sc[0].subject_id.toString();
                        @endif
                    @endif

                    $scope.getTopics();
                })
        }


        $scope.getTopics = function () {
            if($scope.current_course_sc == null || $scope.current_subject_sc == null || $scope.current_sem_sc == null){
                return false;
            }
            
            $http({
                method:"GET",
                url:'{{PREFIX}}'+'get_subject_topics/'+$scope.current_subject_sc+'/'+$scope.current_course_sc+'/'+$scope.current_sem_sc,
                dataType:"json",
                headers:{'Content-Type': 'application/x-www-form-urlencoded'}
            })
                .then(function (response) {
                    $scope.academic_topics_sc = response.data;
                    @if(isset($recored_parent))
                    $scope.current_topic_sc   = {{$recored_parent}};
                    @endif
                    $scope.current_topic_sc   = $scope.current_topic_sc.toString();

                })
        }

        $scope.toTable = function () {

            angular.forEach($scope.academic_years_sc,function (item) {
                if(item.id == $scope.current_year_sc){
                    $scope.year_slug =  item.slug;
                }
            })

            angular.forEach($scope.academic_sems_sc,function (item) {
                    if(item.sem_num == $scope.current_sem_sc){
                        $scope.sem_slug =  item.sem_num;
                    }
            })

            angular.forEach($scope.academic_courses_sc,function (item) {
                if(item.id == $scope.current_course_sc){
                    $scope.course_slug =  item.slug;
                }
            })

            angular.forEach($scope.academic_subjects_sc,function (item) {
                if(item.subject_id == $scope.current_subject_sc){
                    $scope.subject_slug =  item.slug;
                }
            })
            window.location.href = "{{PREFIX}}staff/topics/view/"+$scope.year_slug+"/"+$scope.sem_slug+"/"+$scope.course_slug+"/"+$scope.subject_slug;
        }


        /**
         * Gets the no. of years based on the dueration for that course
         * @return {[type]} [description]
         */
        $scope.getSubjectParents = function () {
            subject_id = $('#subject').val();
            route = '{{URL_TOPICS_GET_PARENT_TOPICS}}';


            data = {_method: 'post', '_token': $scope.getToken(), 'subject_id': subject_id};

            $http.post(route, data).then(function (result, status) {
                /*
                | Pouplate the subject parents based on selected subject
                 */
                $('#parent').empty();
                for (i = 0; i < result.data.length; i++)
                    $('#parent').append('<option value="' + result.data[i].id + '">' + result.data[i].text + '</option>');
            });
        }

        /**
         * Returns the token by fetching if from from form
         *
         */
        $scope.getToken = function () {
            return $('[name="_token"]').val();
        }

    });


    /**
     * Intilize select by default
     */
    $('.select2').select2({
        placeholder: "Select",
    });

    function getSubjectParents() {
        route = '/mastersettings/topics/get-parents-topics/' + $('#subject').val();
        var token = $('[name="_token"]').val();
        data = {_method: 'get', '_token': token};

        $.ajax.get(route, data).success(function (result, status) {
            /*
            | Pouplate the subject parents based on selected subject
             */
            $('#parent').empty();
            for (i = 0; i < result.length; i++)
                $('#parent').append('<option value="' + result[i].id + '">' + result[i].text + '</option>');
        });
    }


    function getSubjectParents(semester,topicName) {
        subject_id = $('#subject').val();
        route = '{{URL_TOPICS_GET_PARENT_TOPICS}}' + subject_id;

        var token = $('[name="_token"]').val();


        data = {_method: 'get', '_token': token, 'subject_id': subject_id, 'semester': semester};

        $.ajax({
            url: route,
            dataType: 'json',
            data: data,
            success: function (result) {
                $('#parent').empty();
                for (i = 0; i < result.length; i++)
                    if(topicName != null && topicName === result[i].text) {
                        $('#parent').append('<option value="' + result[i].id + '" selected>' + result[i].text + '</option>');
                    }else{
                        $('#parent').append('<option value="' + result[i].id + '">' + result[i].text + '</option>');
                    }
            }
        });
    }

    function getSubjectsParentsFromTopic(topicName) {
        getSubjectParents($('#semesters').val(),topicName)
    }

</script>
<script>
    $(document).ready(function () {
        if ($('#subject').val() != '') {
            @if(isset($topic_name))
            getSubjectsParentsFromTopic('<?php echo $topic_name?>');
            @endif
        }

    })

</script>

