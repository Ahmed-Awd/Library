<script src="{{JS}}angular.js"></script>
<script>
    var app = angular.module('academia', []);
    app.controller('mainList', function($scope, $http,$location) {

        $scope.current_course_sc    = null;
        $scope.current_subject_sc   = null;
        $scope.academic_courses_sc  = [];
        $scope.academic_subjects_sc = [];

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
                    @if(isset($recored_subject))
                        $scope.current_subject_sc   = {{$recored_subject}};
                    $scope.current_subject_sc   = $scope.current_subject_sc.toString();
                    @endif
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
                window.location.href = "{{PREFIX}}exams/questionbank/view/"+$scope.year_slug+"/"+$scope.sem_slug+"/"+$scope.course_slug+"/"+$scope.subject_slug;
            }




    });
</script>