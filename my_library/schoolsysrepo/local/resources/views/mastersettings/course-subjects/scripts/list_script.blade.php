<script>
    var app = angular.module('academia', []);
    app.controller('mainList', function($scope, $http,$location) {

        $scope.current_course_sc    = null;
        $scope.current_subject_sc   = null;
        $scope.academic_courses_sc  = [];
        $scope.academic_subjects_sc = [];
        @include('common.year_sems_js');
        @include('common.course_js');

        $scope.get_classes = function () {
            if($scope.current_course_sc == null || $scope.current_year_sc == null || $scope.current_sem_sc == null){
                return false;
            }
            $http({
                method:"GET",
                url:'{{PREFIX}}'+'get_classes/'+$scope.current_course_sc,
                dataType:"json",
                headers:{'Content-Type': 'application/x-www-form-urlencoded'}
            })
                .then(function (response) {
                    $scope.classes = response.data;
                    if($scope.classes.length != 0){
                        $scope.current_class_sc = $scope.classes[0].id.toString();
                    }
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

            angular.forEach($scope.classes,function (item) {
                if(item.id == $scope.current_class_sc){
                    $scope.class_slug =  item.slug;
                }
            })
            window.location.href = "{{URL_MASTERSETTINGS_COURSE_SUBJECTS}}"+$scope.year_slug+"/"+$scope.sem_slug+"/"+$scope.course_slug+"/"+$scope.class_slug;
        }




    });
</script>