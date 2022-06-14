<script src="{{JS}}select2.js"></script>
<script src="{{JS}}angular-messages.js"></script>
<script src="{{JS}}satellizer.min.js"></script>
<script>

    var app = angular.module('academia', ['ngMessages','satellizer']);

    app.controller('importTopicCtrl', function ($scope, $http) {
        $scope.current_course_sc    = null;
        $scope.current_subject_sc   = null;
        $scope.academic_courses_sc  = [];
        $scope.academic_subjects_sc = [];
        $scope.topics_sc = [];
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
                    $scope.get_topics();
                })
        }

        $scope.get_topics = function () {
            $http({
                method: "GET",
                //url: '{{PREFIX}}' + 'get_toopy/' + $scope.current_course_sc + '/' + $scope.current_subject_sc + '/' + $scope.current_sem_sc ,
                url: '{{PREFIX}}' + 'get_toopy/' +$scope.current_year_sc+'/'+ $scope.current_course_sc + '/' + $scope.current_subject_sc + '/' + $scope.current_sem_sc ,

                dataType: "json",
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            })
                .then(function (response) {
                    console.log(response.data);
                    $scope.topics_sc = response.data;
                    angular.forEach($scope.topics_sc,function(item){
                        if(item.parent_id == 0){
                            item.topic_name = "- "+item.topic_name;
                        }
                    });
                    if($scope.topics_sc.length != 0)
                    {
                        $scope.topic_id_sc = $scope.topics_sc[0].id.toString();
                    }
                })
        }
    });
</script>
