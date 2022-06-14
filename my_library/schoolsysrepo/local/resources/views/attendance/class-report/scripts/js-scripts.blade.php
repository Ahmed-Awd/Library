@include('common.angular-factory')

<script>

    app.controller('TabController', function ($scope, $http, httpPreConfig,$location)
    {
        @include('common.year_sems_js');
        @include('common.course_js');
            $scope.tab             = 1;

        $scope.users = [];
        $scope.exam_list      = [];
        $scope.selected_user  = null;
        $scope.certificates_issued  = [];
        $scope.form_show = false;
        $scope.button_text = 'Issue New';


        $scope.doCall = function(){

            academic_id          = $scope.current_year_sc;
            parent_course_id     = $scope.current_course_sc;
            course_id            = $scope.current_sub_course;

            year                 = $scope.current_year_sc;
            semister             = $scope.current_sem_sc;

            route   = '{{URL_STUDENT_CLASS_ATTENDANCE}}';
            data    = {   _method: 'post',
                '_token':httpPreConfig.getToken(),
                'academic_id': academic_id,
                'parent_course_id': parent_course_id,
                'course_id': course_id,
                'year': year,
                'semister': semister,
            };


            httpPreConfig.webServiceCallPost(route, data).then(function(result){
                console.log(result)
                result = result.data;
                $scope.result_data = [];
                angular.forEach(result, function(value, key) {
                    $scope.result_data.push(value);
                })

                $scope.users = $scope.result_data;

            });
        }

        $scope.printIt = function(){
            dta = $('#printable_data').html();
            $('#html_data').val(dta);
            $('#htmlform').submit();
        }


    });


</script>