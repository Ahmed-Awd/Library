@include('common.angular-factory')
<script>

    app.controller('TabController', function ($scope, $http, httpPreConfig,$location)
    {
        @include('common.year_sems_js');
        @include('common.course_js');
            $scope.tab                  = 1;
        $scope.users                = [];
        $scope.exam_list            = [];
        $scope.to_years             = [];
        $scope.to_selected_year     = '';
        $scope.to_semisters         = [];
        $scope.to_selected_semister = '';
        $scope.to_course            = [];
        $scope.to_course_id         = '';
        $scope.to_selected_course_id='';
        $scope.to_total_years = 0;
        $scope.to_total_semisters = 0;
        $scope.current_user = {};
        $scope.current_user_remarks = '';
        $scope.blank_columns = 1;






        $scope.start_report = function () {
            $scope.report = {
                'date_of_start' : null,
                'date_of_finish' : null,
                'academic_year' : null,
                'current_course' : null,
                'semisters' : null
            };
            $scope.report.date_of_start  = $scope.date_of_start;
            $scope.report.date_of_finish = $scope.date_of_finish;
            $scope.report.academic_year  = $scope.current_year_sc;
            $scope.report.current_course = $scope.current_course_sc;
            $scope.report.semisters      = $scope.current_sem_sc;
            console.log($scope.report);
            $http({
                method:"post",
                url:'{{PREFIX}}'+'student/attendance-report',
                data:$scope.report,
                dataType:"json",
                headers:{'Content-Type': 'application/x-www-form-urlencoded'}
            })
                .then(function (response) {
                    console.log(response.data);
                })
        }

        $scope.getSubjects = function () {
            return false;
        }

        $scope.doCall     = function () {
            console.log($scope.current_course_sc,$scope.date_of_finish,$scope.date_of_start);
            if($scope.date_of_start && $scope.date_of_finish && $scope.current_course_sc){
                $scope.ready = true;
            }
        }

        $scope.isNumber = angular.isNumber;

        $scope.addColumns = function(n)
        {
            $scope.blank_columns = [];
            for(i=0; i<n; i++)
                $scope.blank_columns.push(i);
        }

        $scope.printIt = function(){
            dta = $('#printable_data').html();
            $('#html_data').val(dta);
            $('#wes').empty();
            $('#wes').append("<input type='hidden' name='select_option' value='print' />");
            $('#htmlform').submit();
        }

        $scope.downlaodIt = function(){
            dta = $('#printable_data').html();
            $('#html_data').val(dta);
            $('#wes').empty();
            $('#wes').append("<input type='hidden' name='select_option' value='download' />");
            $('#htmlform').submit();
        }
    });




</script>