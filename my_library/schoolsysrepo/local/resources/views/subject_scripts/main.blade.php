
<script>
    angular.module('academia', []).controller('subjects_course', function ($scope, $http, $timeout, $location) {
        $scope.current_year_sc = {{default_year()}};
        $scope.current_year_sc = $scope.current_year_sc.toString();
        $scope.current_sem_sc       = null;
        $scope.current_course_sc    = null;
        $scope.current_subject_sc   = null;
        $scope.subject_id_sc        = {{$id}};
        $scope.academic_years_sc    = [];
        $scope.academic_courses_sc  = [];
        $scope.academic_subjects_sc = [];
        $scope.first_time = true;

        $scope.get_edit_data = function () {

            if($scope.subject_id_sc != 0){
                $http({
                    method:"GET",
                    url:'{{PREFIX}}'+'get_subject_edit/'+$scope.subject_id_sc,
                    dataType:"json",
                    headers:{'Content-Type': 'application/x-www-form-urlencoded'}
                })
                    .then(function (response) {
                        $scope.current_year_sc      = response.data.year.id.toString();
                        $scope.current_sem_sc       = response.data.sem.toString();
                        $scope.current_course_sc    = response.data.course.id.toString();
                        $scope.current_subject_sc   = $scope.subject_id_sc.toString();
                        console.log(response.data);
                        $scope.getSubjects();


                    })
            }
        }



        
        @include('common.year_sems_js');
        @include('common.course_js');

        $scope.getSubjects = function (subject = 1) {
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
                    if($scope.first_time == true){
                        $scope.get_edit_data();
                        $scope.first_time = false;
                    }

                })
        }

        $scope.setCategories = function (id) {
            return false;
        }


    });
</script>